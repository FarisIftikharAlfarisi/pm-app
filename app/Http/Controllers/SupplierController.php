<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\SupplierBarang;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $supplierBarangs = Supplier::whereNull('deleted_at')->get();
        return view('supplier.index', compact('supplierBarangs'));
    }

    public function create()
    {
        $suppliers = Supplier::whereNull('deleted_at')->get();

        $barangs = Barang::with('satuan')->get();

        $kodeSupplier = Supplier::count() + 1; // Menghitung jumlah supplier
        $kodeSupplier = 'SUP-' . str_pad($kodeSupplier, 3, '0', STR_PAD_LEFT);

        return view('supplier.create', compact('suppliers', 'barangs', 'kodeSupplier'));
    }

    public function store(Request $request)
{
    // Validasi data minimal
    $request->validate([
        'kode_supplier' => 'required|string|unique:suppliers,kode_supplier',
        'nama_supplier' => 'required|string',
        'barang_id'     => 'required|array',
        'item_unit'     => 'required|array',
        'harga'         => 'required|array',
        'min_order'     => 'required|array',
    ]);

    // Simpan supplier
    $supplier = Supplier::create([
        'kode_supplier' => $request->kode_supplier,
        'nama_supplier' => $request->nama_supplier,
        'alamat'        => $request->alamat,
        'email'         => $request->email,
        'contact'       => $request->contact,
        'nama_contact_person' => $request->nama_contact_person,
        'no_rekening'   => $request->no_rekening,
        'bank'          => $request->bank,
        'atas_nama_bank' => $request->atas_nama_bank,
        'creator_id'    => Auth::user()->id,
    ]);

    // Loop simpan supplier_barang
    foreach ($request->barang_id as $index => $barangId) {
        SupplierBarang::create([
            'supplier_id' => $supplier->id,
            'barang_id'   => $barangId,
            'harga'       => $request->harga[$index],
            'kuantitas_minimum'         => $request->min_order[$index],
            'satuan_kuantitas_minimum'  => $request->item_unit[$index], // satuan harga dan satuan minimum sama
            'lama_waktu_pengiriman'     => $request->lama_waktu_pengiriman,
            'satuan_lama_waktu_pengiriman' => $request->satuan_lama_waktu_pengiriman,
            'jarak_pengiriman'          => $request->jarak_pengiriman,
            'satuan_jarak_pengiriman'   => $request->satuan_jarak_pengiriman,
            'creator_id'                => Auth::user()->id,
        ]);
    }

    return redirect()->route('supplier.index')->with('success', 'Supplier dan barang berhasil disimpan.');
}

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'kode_supplier' => 'required|unique:suppliers,kode_supplier,' . $supplier->id,
            'nama_supplier' => 'required',
        ]);

        $supplier->update($request->only([
            'kode_supplier', 'nama_supplier', 'alamat', 'email', 'contact',
            'nama_contact_person', 'no_rekening', 'bank', 'atas_nama_bank',
        ]));

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus (soft delete).');
    }
}
