<?php

namespace App\Http\Controllers;

use App\Models\PembelianBarang;
use App\Models\PembelianBarangDetails;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembelianBarangController extends Controller
{
    // Menampilkan halaman daftar pembelian barang
    public function index()
    {
        $pembelianBarangs = PembelianBarang::withTrashed()->get();

        // membuat kode pembelian otomatis

        $lastPembelian = PembelianBarang::orderBy('created_at', 'desc')->first();
        if ($lastPembelian) {
            $lastKode = $lastPembelian->Kode_Pembelian;
            $lastNumber = (int) substr($lastKode, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            $newKode = 'PB-' . date('Ymd') . '-' . $newNumber;
        } else {
            $newKode = 'PB-' . date('Ymd') . '-001';
        }

        return view('BARANG.PembelianBarang.index', compact('pembelianBarangs', 'newKode'));
    }

    // Menyimpan data pembelian baru (step 1)
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'Kode_Pembelian' => 'required|unique:pembelian_barangs,Kode_Pembelian',
        ]);

        $pembelian['kode_pembelian'] = $request['Kode_Pembelian'];
        $pembelian['creator_id'] = Auth::user()->id;

        $pembelianBarang = PembelianBarang::create($pembelian);

        // Redirect ke halaman list_barang dengan membawa ID_Pembelian
        return redirect()->route('pembelian_barang.list_barang', $pembelianBarang->id)->with('success', 'Pembelian berhasil dibuat. Silakan tambahkan detail barang.');
    }

    // Menampilkan halaman list_barang untuk menambahkan detail pembelian (step 2)
    public function listBarang($id)
    {
        $pembelianBarang = PembelianBarang::findOrFail($id);
        $barangs = Barang::all(); // Ambil semua data barang untuk dropdown
        return view('BARANG.PembelianBarang.list_barang', compact('pembelianBarang', 'barangs'));
    }

    // Menyimpan detail pembelian barang (step 2)
    public function storeDetail(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'ID_Barang' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|integer|min:1',
        ]);

        // Hitung total_harga
        $total_harga = $request->jumlah * $request->harga_satuan;

        // Simpan detail pembelian
        PembelianBarangDetails::create([
            'ID_Pembelian' => $id,
            'Kode_Pembelian' => PembelianBarang::find($id)->Kode_Pembelian,
            'ID_Barang' => $request->ID_Barang,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $total_harga,
            'status' => 'diproses',
            'Kode_Karyawan' => Auth::user()->id,
        ]);

        // Update total_belanja di tabel pembelian_barangs
        $pembelianBarang = PembelianBarang::find($id);
        $pembelianBarang->total_belanja = PembelianBarangDetails::where('ID_Pembelian', $id)->sum('total_harga');
        $pembelianBarang->save();

        return redirect()->back()->with('success', 'Detail barang berhasil ditambahkan.');
    }

    // Menghapus detail pembelian barang
    public function destroyDetail($id)
    {
        $detail = PembelianBarangDetails::findOrFail($id);
        $detail->delete();

        // Update total_belanja di tabel pembelian_barangs
        $pembelianBarang = PembelianBarang::find($detail->ID_Pembelian);
        $pembelianBarang->total_belanja = PembelianBarangDetails::where('ID_Pembelian', $detail->ID_Pembelian)->sum('total_harga');
        $pembelianBarang->save();

        return redirect()->back()->with('success', 'Detail barang berhasil dihapus.');
    }

    public function approve($id){
        $pembelianBarang = PembelianBarang::findOrFail($id);
        $pembelianBarang->status = 'disetujui';

        //update status pembelian barang dan masukan detailnya ke inventarisstokbarang



        return redirect()->back()->with('success', 'Pembelian barang berhasil disetujui.');
    }
}
