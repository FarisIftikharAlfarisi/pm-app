<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    // Menampilkan daftar site
    public function index()
    {
        $sites = Site::all();
        $kode_site = 'SITE-' . str_pad(Site::count() + 1, 3, '0', STR_PAD_LEFT);
        return view('site.index', compact('sites', 'kode_site'));
    }

    // Menyimpan data site baru
    public function store(Request $request)
    {
        $validate = $request->validate([
            'Kode_Site' => 'required|unique:sites,Kode_Site',
            'nama_site' => 'required|string',
            'alamat_site' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'jenis_site' => 'required|string',
        ]);

        if (Site::where('Kode_Site', $request->Kode_Site)->exists()) {
            return redirect()->back()->with('error', 'Kode Site '.$request->Kode_Site.' sudah ada.');
        }else{

            $data['Kode_Site'] = $request->Kode_Site;
            $data['nama_site'] = $request->nama_site;
            $data['jenis_site'] = $request->jenis_site;
            $data['alamat'] = $request->alamat_site;
            $data['desa_kelurahan'] = $request->desa_kelurahan;
            $data['kecamatan'] = $request->kecamatan;
            $data['kabupaten_kota'] = $request->kabupaten_kota;
            $data['provinsi'] = $request->provinsi;
            $data['latitude'] = $request->latitude;
            $data['longitude'] = $request->longitude;

            Site::create($data);
        }

        return redirect()->route('site.index')->with('success', 'Site '.$request->Kode_Site | $request->nama_site.' berhasil ditambahkan.');
    }

    // Mengambil data site untuk edit
    public function edit($id)
    {
        $site = Site::findOrFail($id);
        return response()->json($site);
    }

    // Mengupdate data site
    public function update(Request $request, $id)
    {
        $kode_site = Site::findOrFail($id)->Kode_Site;

        $request->validate([
            'Kode_Site' => 'required|unique:sites,Kode_Site,' . $kode_site,
            'site' => 'required|string',
            'alamat_site' => 'required|string',
            'desa_kelurahan' => 'required|string',
            'kecamatan' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'provinsi' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
        ]);

        $site = Site::findOrFail($id);
        $site->update($request->all());

        return redirect()->route('site.index')->with('success', 'Site berhasil ditambahkan.');
    }

    // Menghapus data site
    public function destroy($id)
    {
        $site = Site::findOrFail($id);
        $site->delete();

        return response()->json(['success' => 'Site berhasil dihapus.']);
    }

    // Mengembalikan data site yang dihapus
    public function restore($id)
    {
        $site = Site::withTrashed()->findOrFail($id);
        $site->restore();

        return response()->json(['success' => 'Site berhasil dikembalikan.']);
    }

    // Menghapus data site secara permanen
    public function forceDelete($id)
    {
        $site = Site::withTrashed()->findOrFail($id);
        $site->forceDelete();

        return response()->json(['success' => 'Site berhasil dihapus secara permanen.']);
    }
}
