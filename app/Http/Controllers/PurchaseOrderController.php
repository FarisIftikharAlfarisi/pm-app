<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Produksi;
use App\Models\Projects;
use App\Models\TaskToDo;
use App\Models\SiteRequest;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\SupplierBarang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderDetails;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
 public function automate(Request $request)
{
    $request->validate([
        'project_id'              => 'required',
        'site_id'                 => 'required',
        'site_request_id'         => 'required',
        'selected_vendor'         => 'required|array',
        'items'                   => 'required|array',
    ]);

    DB::transaction(function () use ($request) {

        // 1. Group barang berdasarkan supplier
        $groupedBySupplier = [];
        foreach ($request->selected_vendor as $barangId => $supplierId) {
            $groupedBySupplier[$supplierId][] = $barangId;
        }

        // 2. Loop per supplier
        foreach ($groupedBySupplier as $supplierId => $barangIds) {
            $kodePO = 'PO-' . strtoupper(uniqid());

            // Ambil estimasi waktu dari salah satu barang supplier ini
            $supplierBarang = SupplierBarang::where('supplier_id', $supplierId)
                ->where('barang_id', $barangIds[0])
                ->first();

            $estimasiSampai = now();
            if ($supplierBarang && $supplierBarang->lama_waktu_pengiriman) {
                $satuan = strtolower($supplierBarang->satuan_lama_waktu_pengiriman);
                switch ($satuan) {
                    case 'hari':
                        $estimasiSampai = now()->addDays($supplierBarang->lama_waktu_pengiriman);
                        break;
                    case 'minggu':
                        $estimasiSampai = now()->addWeeks($supplierBarang->lama_waktu_pengiriman);
                        break;
                    case 'bulan':
                        $estimasiSampai = now()->addMonths($supplierBarang->lama_waktu_pengiriman);
                        break;
                }
            }

            // 3. Buat PO untuk supplier ini
            $po = PurchaseOrder::create([
                'kode_purchase_order'        => $kodePO,
                'nama_purchase_order'        => 'PO Supplier ' . $supplierId,
                'site_request_id'            => $request->site_request_id,
                'project_id'                 => $request->project_id,
                'purchase_requisition_id'    => $request->purchase_requisition_id,
                'site_id'                    => $request->site_id,
                'supplier_id'                => $supplierId,
                'estimasi_sampai'            => $estimasiSampai,
                'approval_accounting_status' => 'APPROVED',
                'accounting_id'              => Auth::user()->id,
                'accounting_approval_date'   => now(),
                'tanggal_purchase_order'     => now(),
            ]);

            // 4. Simpan semua barang supplier ini ke PO detail
            foreach ($barangIds as $barangId) {
                if (!isset($request->items[$barangId])) continue;

                $detail = $request->items[$barangId];
                PurchaseOrderDetails::create([
                    'purchase_order_id' => $po->id,
                    'barang_id'         => $barangId,
                    'jumlah'            => $detail['jumlah'],
                    'satuan'            => $detail['satuan_id'],
                    'harga'             => $detail['harga']
                ]);

                /**
                 * ====== JADWAL PRODUKSI ======
                 * Start produksi: sehari setelah bahan baku sampai
                 * End produksi: maksimal tanggal kebutuhan dari WBS
                 */

                $project = Projects::find($request->project_id);

                // ambil site request
                $siteRequest = SiteRequest::find($po->site_request_id);

                // ambil semua barang AFTERCRAFT dari site request details
                $barangAftercrafts = Barang::join('site_request_details as srd', 'barangs.id', '=', 'srd.barang_id')
                    ->where('srd.site_request_id', $siteRequest->id)
                    ->where('barangs.kategori', 'AFTERCRAFT')
                    ->select('barangs.*')
                    ->get();

                foreach ($barangAftercrafts as $aftercraft) {

                    // ambil task WBS terkait barang ini via kebutuhanbarangWBS
                    $task = TaskToDo::where('project_id', $request->project_id)
                        ->whereHas('kebutuhanBarangWBS', function($q) use ($aftercraft) {
                            $q->where('barang_id', $aftercraft->id);
                        })
                        ->orderBy('start_date', 'asc')
                        ->first();

                    if (!$task) continue; // skip jika tidak ada task

                    $tanggalMulaiProduksi = $estimasiSampai->copy()->addDay();
                    $tanggalSelesaiProduksi = $task->deadline ?? $task->start_date;

                    Produksi::create([
                        'kode_produksi'     => $this->generateKodeProduksi(),
                        'nama_produksi'     => 'Produksi ' . $aftercraft->nama_barang . ' - ' .$project->nama_project,
                        'barang_id'         => $aftercraft->id,
                        'project_id'        => $request->project_id,
                        'site_id'           => $request->site_id,
                        'purchase_order_id' => $po->id,
                        'tanggal_mulai'     => $tanggalMulaiProduksi,
                        'tanggal_selesai'   => $tanggalSelesaiProduksi,
                        'status'            => 'TERJADWAL',
                    ]);
                }
            }
        }
    });

    return redirect()->back()->with('success', 'Purchase Order berhasil dibuat.');
}


    public function printSupplier($id)
    {
        $po = PurchaseOrder::with('details')->findOrFail($id);
        $supplier = $po->supplier;

        $company = [
            'nama'      => 'PT. Arta Trimurti Hartajaya Konstruksi',
            'alamat'    => 'Jl. Gedebage No.Jl. Gedebage No.144 Bandung Timur.',
            'telepon'   => '+6288217107198',
            'email'   => 'athakonstruksi@gmail.com',
        ];

        return view('BARANG.SiteRequest.PO.TemplateSurat.surat_kebutuhan_pengadaan', compact('po', 'supplier', 'company'));
    }

    public function index_by_po($kode_proyek)
{
    $project = Projects::where('kode_project', $kode_proyek)->firstOrFail();

    $purchaseOrders = PurchaseOrder::with(['supplier', 'details.barang'])
        ->where('project_id', $project->id)
        ->orderByDesc('tanggal_purchase_order')
        ->get();

    return view('BARANG.SiteRequest.PO.index', compact('purchaseOrders'));
}


public function downloadPdf($id)
{
    $po = PurchaseOrder::with('details')->findOrFail($id);
    $supplier = $po->supplier;

    // Data perusahaan
    $company = (object)[
        'nama'              => 'PT. Arta Trimurti Hartajaya Konstruksi',
        'alamat'            => 'Jl. Gedebage No.Jl. Gedebage No.144 Bandung Timur.',
        'telepon'           => '+6288217107198',
        'email'             => 'athakonstruksi@gmail.com',
        'penanggung_jawab'  => 'Faris Iftikhar Alfarisi',
        'metode_pembayaran' => 'DP',
        'syarat_pembayaran' => 'Tidak ada syarat pembayaran',
    ];

    // Hitung total2
    $subtotal = $po->details->sum(fn($item) => $item->jumlah * $item->harga);

    $pajak = $subtotal * 0.11; // contoh pajak 11%
    $biaya_pengiriman = 0; // kalau ada bisa diisi dari DB
    $total = $subtotal + $pajak + $biaya_pengiriman;

    $totals = (object)[
        'subtotal'         => $subtotal,
        'pajak'            => $pajak,
        'biaya_pengiriman' => $biaya_pengiriman,
        'total'            => $total,
    ];

    $pdf = PDF::loadView(
        'BARANG.SiteRequest.PO.TemplateSurat.surat_kebutuhan_pengadaan',
        compact('po', 'supplier', 'company', 'totals')
    );

    return $pdf->download('PO-' . $po->kode_purchase_order . '.pdf');
    redirect()->back();
}

private function generateKodeProduksi() {
    $maxId = Produksi::max('id') ?? 0; // jika belum ada, pakai 0
    $nextId = $maxId + 1;
    return 'PROD-' . str_pad($nextId, 5, '0', STR_PAD_LEFT); // PROD-00001, PROD-00002, dst
}

}
