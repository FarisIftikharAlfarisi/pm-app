<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBarangSite;
use App\Models\PenerimaanBarangSiteDetails;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerimaanBarangSiteController extends Controller
{
    // Menampilkan daftar penerimaan barang
    public function index()
    {
        $penerimaanBarangSites = PenerimaanBarangSite::where('created_at', '>=', now()->subDays(7))->get();
        return view('BARANG.PenerimaanBarang.index', compact('penerimaanBarangSites'));
    }

    // Menyimpan data penerimaan barang baru (step 1)
    public function store(Request $request)
    {
        $request->validate([
            'Kode_Penerimaan' => 'required|unique:penerimaan_barang_sites,Kode_Penerimaan',
            'Penerima' => 'required|string',
        ]);

        // Tambahkan Kode_Karyawan dari user yang login
        $request->merge([
            'Kode_Karyawan' => Auth::user()->id,
            'site' => Auth::user()->userDetail->Kode_Site
            ]);

        // Simpan data penerimaan baru
        $penerimaanBarangSite = PenerimaanBarangSite::create($request->all());

        // Redirect ke halaman list_barang dengan membawa ID_Penerimaan
        return redirect()->route('penerimaan_barang_site.list_barang', $penerimaanBarangSite->id)->with('success', 'Penerimaan barang berhasil dibuat. Silakan tambahkan detail barang.');
    }

    // Menampilkan halaman list_barang untuk menambahkan detail penerimaan (step 2)
    public function listBarang($id)
    {
        $penerimaanBarangSite = PenerimaanBarangSite::findOrFail($id);
        $barangs = Barang::all(); // Ambil semua data barang untuk dropdown
        return view('BARANG.PenerimaanBarang.list_barang', compact('penerimaanBarangSite', 'barangs'));
    }

    // Menyimpan detail penerimaan barang (step 2)
    public function storeDetail(Request $request, $id)
    {
        $request->validate([
            'ID_Barang' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        // Simpan detail penerimaan
        PenerimaanBarangSiteDetails::create([
            'ID_Penerimaan' => $id,
            'ID_Barang' => $request->ID_Barang,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'Kode_Karyawan' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Detail barang berhasil ditambahkan.');
    }

    // Menghapus detail penerimaan barang
    public function destroyDetail($id)
    {
        $detail = PenerimaanBarangSiteDetails::findOrFail($id);
        $detail->delete();

        return redirect()->back()->with('success', 'Detail barang berhasil dihapus.');
    }
}
