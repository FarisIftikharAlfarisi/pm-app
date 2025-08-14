<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\GoodReceipt;
use App\Models\GoodReceiptItem;
use App\Models\GoodsReceipts;
use App\Models\GoodsReceiptsDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodReceiptsController extends Controller
{
    /**
     * Menampilkan daftar PO per supplier
     */
    public function index()
    {
        // Ambil PO yang sudah di-approve & belum dibuat Good Receipt
        $purchaseOrders = PurchaseOrder::with('supplier', 'details.barang')
            ->where('approval_accounting_status', 'APPROVED')
            ->get();

        return view('BARANG.SiteRequest.GR.index', compact('purchaseOrders'));
    }

    /**
     * Simpan Good Receipt
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Simpan header Good Receipt
            $goodReceipt = GoodsReceipts::create([
                'purchase_order_id' => $request->purchase_order_id,
                'supplier_id'       => $request->supplier_id,
                'tanggal_terima'    => $request->tanggal_terima ?? now(),
                'catatan'           => $request->catatan,
                'status'            => 'RECEIVED', // default
            ]);

            // 2. Simpan detail item penerimaan
            foreach ($request->items as $item) {
                GoodsReceiptsDetails::create([
                    'good_receipt_id'   => $goodReceipt->id,
                    'purchase_order_item_id' => $item['po_item_id'],
                    'qty_diterima'      => $item['qty_diterima'],
                    'status_penerimaan' => $item['status_penerimaan'], // full / partial / rejected
                    'catatan'           => $item['catatan'] ?? null,
                ]);

                // 3. Simpan detail barang penerimaan
                GoodsReceiptsDetails::create([
                    'good_receipt_id'   => $goodReceipt->id,
                    'purchase_order_item_id' => $item['po_item_id'],
                    'barang_id'         => $item['barang_id'],
                    'qty_diterima'      => $item['qty_diterima'],
                    'status_penerimaan' => $item['status_penerimaan'], // full / partial / rejected
                    'catatan'           => $item['catatan'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('good_receipts.index')->with('success', 'Good Receipt berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan Good Receipt: ' . $th->getMessage());
        }
    }

    /**
     * Ambil detail PO untuk modal
     */
    public function getPoItems($poId)
    {
        $po = PurchaseOrder::with('items.barang')->findOrFail($poId);
        return response()->json($po);
    }
}
