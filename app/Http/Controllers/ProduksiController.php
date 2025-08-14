<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
     public function index()
    {
        // Ambil semua data produksi (nanti bisa ditambah filter)
        $produksis = Produksi::orderBy('tanggal_mulai', 'asc')->get();

        return view('Task.Produksi.index', compact('produksis'));
    }
}
