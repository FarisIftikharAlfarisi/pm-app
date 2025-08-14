<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\ItemUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BillOfMaterialBarang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\BillOfMaterialComponents;

class BarangController extends Controller
{
    public function index()
    {
        // Ambil data barang yang tidak di-soft delete
        $barangs = Barang::withoutTrashed()->get();
        return view('BARANG.index', compact('barangs'));
    }


    /**
     * ===========================================================
     *
     * BAHAN BAKU
     *
     * Note: bagian ini digunakan untuk view dan crud bahan baku
     *
     * ===========================================================
     **/

    public function bahan_baku(){

        $kode_barang = 'B02/I' . '-' . str_pad(Barang::where('Kategori', 'BAHAN BAKU')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT);

        return view('BARANG.TambahBarang.Lainnya.bahan_baku', compact('kode_barang'));
    }
// Menyimpan bahan baku baru
    public function bahanBakuStore(Request $request)
    {
        $request->validate([
            'Kode_Barang' => 'required|unique:barangs,Kode_Barang',
            'Nama_Barang' => 'required|string|max:255',
            'Merk' => 'nullable|string|max:255',
            'Keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720', // Maksimal 30MB
            'satuan' => 'required|array|min:1',
            'satuan.*.nama' => 'required|string|max:50',
            'satuan.*.konversi' => 'nullable|numeric',
            'satuan.*.deskripsi' => 'nullable|string|max:255',
            'satuan.*.default' => 'nullable'
        ]);

        // dd($request->all());

        // Simpan data barang
        $barang = Barang::create([
            'kode_barang' => $request->Kode_Barang,
            'nama_barang' => $request->Nama_Barang,
            'kategori' => 'BAHAN_BAKU',
            'merk' => $request->Merk,
            'keterangan' => $request->Keterangan,
            'is_visible' => true,
            'creator_id' => Auth::id()
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('barang-photos', 'public');
            $barang->update(['foto_path' => $path]);
        }

        // Simpan satuan
        foreach ($request->satuan as $satuan) {
            $isDefault = isset($satuan['default']) && $satuan['default'] === 'on';

            ItemUnit::create([
                'barang_id' => $barang->id,
                'unit_name' => $satuan['nama'],
                'conversion_factor' => $satuan['konversi'] ?? null,
                'deskripsi_konversi' => $satuan['deskripsi'] ?? null,
                'is_default' => $isDefault
            ]);
        }

        return redirect()->route('barang.index')->with('success', 'Bahan baku berhasil ditambahkan');
    }

    // Menampilkan form edit bahan baku
    public function editBahanBaku($id)
    {
        $barang = Barang::with('satuan')->findOrFail($id);

        // Pastikan yang diedit adalah bahan baku
        if ($barang->kategori !== 'BAHAN_BAKU') {
            abort(404);
        }

        return view('BARANG.EditBarang.edit_bahan_baku', compact('barang'));
    }

    // Update bahan baku
    public function bahanBakuUpdate(Request $request, Barang $barang)
    {
        // Pastikan yang diupdate adalah bahan baku
        if ($barang->Kategori !== 'BAHAN_BAKU') {
            abort(404);
        }

        $request->validate([
            'Kode_Barang' => 'required|unique:barangs,Kode_Barang,'.$barang->id,
            'Nama_Barang' => 'required|string|max:255',
            'Merk' => 'nullable|string|max:255',
            'Keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Status' => 'required|in:AKTIF,NONAKTIF',
            'satuan' => 'required|array|min:1',
            'satuan.*.id' => 'nullable|exists:item_units,id',
            'satuan.*.nama' => 'required|string|max:50',
            'satuan.*.konversi' => 'nullable|numeric',
            'satuan.*.deskripsi' => 'nullable|string|max:255',
            'satuan.*.default' => 'nullable'
        ]);

        // Update data barang
        $barang->update([
            'Kode_Barang' => $request->Kode_Barang,
            'Nama_Barang' => $request->Nama_Barang,
            'Merk' => $request->Merk,
            'Keterangan' => $request->Keterangan,
            'Status' => $request->Status
        ]);

        // Update foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($barang->foto_path) {
                Storage::disk('public')->delete($barang->foto_path);
            }
            $path = $request->file('foto')->store('barang-photos', 'public');
            $barang->update(['foto_path' => $path]);
        }

        // Update satuan
        $existingUnitIds = [];
        foreach ($request->satuan as $satuan) {
            $isDefault = isset($satuan['default']) && $satuan['default'] === 'on';

            if (isset($satuan['id'])) {
                // Update satuan yang sudah ada
                $unit = ItemUnit::find($satuan['id']);
                if ($unit) {
                    $unit->update([
                        'unit_name' => $satuan['nama'],
                        'conversion_factor' => $satuan['konversi'] ?? null,
                        'deskripsi_konversi' => $satuan['deskripsi'] ?? null,
                        'is_default' => $isDefault
                    ]);
                    $existingUnitIds[] = $unit->id;
                }
            } else {
                // Tambah satuan baru
                $newUnit = ItemUnit::create([
                    'barang_id' => $barang->id,
                    'unit_name' => $satuan['nama'],
                    'conversion_factor' => $satuan['konversi'] ?? null,
                    'deskripsi_konversi' => $satuan['deskripsi'] ?? null,
                    'is_default' => $isDefault
                ]);
                $existingUnitIds[] = $newUnit->id;
            }
        }

        // Hapus satuan yang tidak ada dalam request
        ItemUnit::where('barang_id', $barang->id)
                ->whereNotIn('id', $existingUnitIds)
                ->delete();

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil diperbarui');
    }

    // Soft delete bahan baku
    public function bahanBakuDestroy(Barang $barang)
    {
        // Pastikan yang dihapus adalah bahan baku
        if ($barang->Kategori !== 'BAHAN_BAKU') {
            abort(404);
        }

        $barang->delete();
        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil dihapus');
    }

    // Restore bahan baku yang di-soft delete
    public function bahanBakuRestore($id)
    {
        $barang = Barang::onlyTrashed()->findOrFail($id);

        // Pastikan yang direstore adalah bahan baku
        if ($barang->Kategori !== 'BAHAN_BAKU') {
            abort(404);
        }

        $barang->restore();
        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil dikembalikan');
    }


     /**
     * ===========================================================
     *  End of BAHAN BAKU
     * ===========================================================
     **/


    public function tools(){
        // Kode barang otomatis

        $kode_barang_pu = 'B01/I' . '-' . str_pad(Barang::where('Kategori', 'PERALATAN_UMUM')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT);

        $kode_barang_pk = 'B01/II' . '-' . str_pad(Barang::where('Kategori', 'PERALATAN_KANTOR')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT);

        $kode_barang_pp = 'B01/III' . '-' . str_pad(Barang::where('Kategori', 'PERALATAN_PROYEK')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT);

        $inventaris_kendaraan = 'KI/I' . '-' . str_pad(Barang::where('Kategori', 'KENDARAAN')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT);

        $alat_berat = 'KI/II' . '-' . str_pad(Barang::where('Kategori', 'ALAT_BERAT')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT);

        $barangs = Barang::where('Kategori', '!=', 'AFTERCRAFT')
                    ->orWhere('Kategori', '!=', 'BAHAN_BAKU')
                    ->orWhere('Kategori', '!=', 'JASA_INTERNAL')
                    ->orWhere('Kategori', '!=', 'JASA_EKSTERNAL')
                    ->get();

        return view('BARANG.TambahBarang.Lainnya.alat', compact('kode_barang_pu', 'kode_barang_pk', 'kode_barang_pp', 'inventaris_kendaraan', 'alat_berat', 'barangs'));
    }


    /**
     * ===========================================================
     *
     * AFTERCRAFT
     *
     * Note: bagian ini digunakan untuk view dan crud aftercraft
     */

    public function aftercraft()
    {
        $kode_barang = 'B02/II' . '-' . str_pad(Barang::where('Kategori', 'AFTERCRAFT')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT);

        $kode_bom = 'AC'.str_pad(Barang::where('Kategori', 'AFTERCRAFT')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT).'/'.$kode_barang;

        // Ambil semua barang dengan kategori AFTERCRAFT atau BAHAN BAKU
        $barangs = Barang::whereIn('kategori', ['AFTERCRAFT', 'BAHAN_BAKU'])
            ->with('satuan') // Ambil semua satuan tanpa filter
            ->get()
            ->map(function ($barang) {
                // Tetap simpan satuan default sebagai property tambahan
                $barang->default_uom = optional($barang->satuan->firstWhere('is_default', true))->unit_name;
                return $barang;
            });

        $kategori = $barangs->pluck('Nama_Barang');

        return view('BARANG.TambahBarang.Aftercraft.add', compact('kode_barang','kode_bom', 'barangs', 'kategori'));

    }

    public function storeAftercraft(Request $request)
    {
    try {
        // 1. Simpan barang kategori AFTERCRAFT ke tabel `barangs`
        $barang = Barang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            'kategori' => 'AFTERCRAFT',
            'keterangan' => $request->keterangan,
            'is_visible' => true,
            'creator_id' => Auth::id(),
        ]);

        // 2. Simpan data BOM ke `bill_of_material_barangs`
        $bom = BillOfMaterialBarang::create([
            'kode_bom' => $request->kode_bom,
            'nama_bom' => $request->nama_bom,
            'quantity' => $request->quantity,
            'unit_of_measure' => $request->unit_of_measure,
            'status' => $request->status ?? 'ACTIVE',
            'estimasi_waktu_produksi' => $request->estimasi_waktu_produksi,
            'satuan_estimasi_waktu_produksi' => $request->satuan_estimasi_waktu_produksi,
            'catatan_produksi' => $request->catatan_produksi,
            'creator_id' => Auth::id(),
        ]);

        // 3. Simpan komponen bahan baku ke `bill_of_material_components`
        foreach ($request->bahan_baku_id as $i => $bahanId) {
            BillOfMaterialComponents::create([
                'bom_id' => $bom->id,
                'bahan_baku_id' => $bahanId,
                'quantity' => $request->component_quantity[$i],
                'unit_of_measure' => $request->component_uom[$i],
                'toleransi_quantity' => $request->toleransi_quantity[$i] ?? 0,
                'creator_id' => Auth::id(),
            ]);
        }

        return redirect()->route('barang.index')
            ->with('success', 'Aftercraft dan komponennya berhasil ditambahkan!');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal menyimpan Aftercraft: ' . $e->getMessage());
    }
    }


    // tinggal function edit sama soft delete


    /**
     * ===========================================================
     *  End of AFTERCRAFT
     * ===========================================================
    */



    /**
     * ===========================================================
     *
     * Jasa Internal dan Jasa Eksternal
     *
     * ===========================================================
     *
    */

    public function service(){


        $kode_internal = 'B-K03/I/JI' . '-' . str_pad(Barang::where('Kategori', 'JASA')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT);

        $kode_eksternal = 'B-K03/II/JE' . '-' . str_pad(Barang::where('Kategori', 'JASA')->count('Kode_Barang') + 1, 3, '0', STR_PAD_LEFT);

        return view('BARANG.TambahBarang.Lainnya.jasa', compact('kode_internal', 'kode_eksternal'));

    }

    public function destroy(Barang $barang)
    {
        // Soft delete data barang
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function restore($id)
    {
        // Restore data barang yang di-soft delete
        Barang::onlyTrashed()->where('id', $id)->restore();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        // Hapus data barang secara permanen
        Barang::onlyTrashed()->where('id', $id)->forceDelete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus secara permanen.');
    }

    public function trashed()
    {
        // Ambil data barang yang di-soft delete
        $barangs = Barang::onlyTrashed()->get();
        return view('barang.trashed', compact('barangs'));
    }
}
