<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Site;
use App\Models\Projects;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\BusinessDocuments;
use Illuminate\support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProyekController extends Controller
{


    /**
     * =========================
     * INDEX, CREATE, STORE
     * =========================
     */

    public function index()
    {
        // Mengambil semua proyek dengan relasi site dan dokumen
        $projects = Projects::with(['site', 'businessDocuments'])->get();
        return view('Proyek.index', compact('projects'));
    }

    public function create()
    {
        $jenis_dokumen = [
            'Gambar Teknik (Gamtek)',
            'Dokumen MOU',
            'Surat Perjanjian Kerja',
            'Dokumen RAB',
            'Surat Izin Pembangunan',
            'Surat Izin Lingkungan',
            'Surat Izin Operasional',
            'Surat Izin Kerja',
            'Daftar Kebutuhan Material',
            'Rencana Kerja dan Syarat (RKS)',
        ];

        $kodeProyek = $this->generate_project_code();
        $kodeSite = $this->generate_site_code($kodeProyek);

        return view('Proyek.create', compact('jenis_dokumen', 'kodeProyek', 'kodeSite'));
    }

    /**
     * Menyimpan proyek baru ke database
     */

    public function store(Request $request)
    {
    DB::beginTransaction();

    try {

        $site = Site::create([
            'Kode_Site' => $request->site['Kode_Site'],
            'nama_site' => $request->site['nama_site'],
            'jenis_site' => $request->site['jenis_site'],
            'latitude' => $request->site['latitude'],
            'longitude' => $request->site['longitude'],
            'alamat' => $request->site['alamat'],
            'desa_kelurahan' => $request->site['desa_kelurahan'],
            'kecamatan' => $request->site['kecamatan'],
            'kabupaten_kota' => $request->site['kabupaten_kota'],
            'provinsi' => $request->site['provinsi'],
        ]);

        $project = Projects::create([
            'kode_project' => $request->kode_project,
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'anggaran' => $request->anggaran,
            'jenis_proyek' => $request->jenis_proyek,
            'jenis_proyek_lainnya' => $request->jenis_proyek_lainnya,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'penanggung_jawab' => $request->penanggung_jawab,
            'kontak_penanggung_jawab' => $request->kontak_penanggung_jawab,
            'klien' => $request->klien,
            'kontak_klien' => $request->kontak_klien,
            'catatan' => $request->catatan,
            'site_id' => $site->id,
            'created_by' => Auth::user()->id,
            'status' => 'planning', // Status awal proyek
        ]);

        // 3. Simpan dokumen jika ada
        if ($request->has('documents')) {
            foreach ($request->documents as $index => $doc) {
                $file = $doc['file_path'];
                $originalName = $file->getClientOriginalName();
                $timestamp = now()->timestamp;

                // Ambil info dari project/site
                $projectCode = $request->kode_project;
                $siteCode = $request->site['Kode_Site'];
                $docTypeCode = strtoupper(Str::slug(explode('(', $doc['jenis_dokumen'])[0]));
                if (Str::contains($doc['jenis_dokumen'], '(')) {
                    $docTypeCode = strtoupper(Str::between($doc['jenis_dokumen'], '(', ')'));
                }

                $date = now()->format('dmy');
                $docCountFormatted = str_pad($index + 1, 2, '0', STR_PAD_LEFT);

                // Format nama file tanpa garis miring (gunakan __)
                $customFileName = "{$projectCode}__{$siteCode}__{$docTypeCode}__{$date}__{$docCountFormatted}__{$timestamp}_{$originalName}";

                // Simpan file
                $storedPath = $file->storeAs('DokumenProyek', $customFileName, 'public');

                // Simpan metadata ke tabel dokumen
                BusinessDocuments::create([
                    'kode_dokumen' =>  $customFileName,
                    'project_id' => $project->id,
                    'site_id' => $site->id,
                    'nama_dokumen' => $doc['nama_dokumen'],
                    'jenis_dokumen' => $doc['jenis_dokumen'],
                    'file_path' => '/storage/DokumenProyek/' . $customFileName,
                    'user_id' => Auth::user()->id
                ]);
            }
        }

        DB::commit();

        // return response()->json([
        //     'message' => 'Proyek berhasil disimpan',
        //     'project' => $project,
        //     'site' => $site,
        // ], 201);

        return redirect()->route('projects.index')->with('success', 'Proyek '. $request->nama_project .'berhasil dibuat.');

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Gagal menyimpan proyek',
            'error' => $e->getMessage(),
        ], 500);
    }
}




    private function generate_site_code($projectCode)
    {
        $count = Site::count() + 1;
        $date = Carbon::now()->format('dmy');

        // Ambil angka dari kode project (PRJ-897 → 897)
        $projectNumber = explode('-', $projectCode)[1] ?? $count;

        return "ST__{$count}__{$projectCode}";
    }

    /**
     * Generate kode project
     * Format: PRJ-897/291125
     */
    private function generate_project_code()
    {
        $count = Projects::count() + 1;
        $date = Carbon::now()->format('dmy');
        $prefix = 'PRJ';

        return "{$prefix}__{$count}__{$date}__" . Auth::user()->id . "";
    }

    /**
     * Generate kode dokumen
     * Format: PRJ-897/ST-56/GAMTEK/291125/02
     */
    private function generate_document_code($projectCode, $siteCode, $docType, $docCount)
    {
        $date = Carbon::now()->format('dmy');

        // Ambil kode project dan site
        $projectPart = explode('-', $projectCode)[0]; // PRJ-
        $sitePart = explode('-', $siteCode)[1]; // ST-

        // Format jenis dokumen (Gambar Teknik (Gamtek) → GAMTEK)
        $docTypeCode = strtoupper(Str::slug(explode('(', $docType)[0]));
        if (strpos($docType, '(') !== false) {
            $docTypeCode = strtoupper(Str::between($docType, '(', ')'));
        }

        $docCountFormatted = str_pad($docCount, 2, '0', STR_PAD_LEFT);

        return "{$projectPart}__{$sitePart}__{$docTypeCode}__{$date}__{$docCountFormatted}";
    }

    /**
     * =========================
     * EDIT, UPDATE
     * =========================
     */

    public function edit($kodeProyek){
        // ambil id proyek berdasarkan kode proyek
        $project = Projects::where('kode_project', $kodeProyek)->firstOrFail();
        $site = $project->site; // Ambil relasi site
        $documents = $project->businessDocuments; // Ambil relasi dokumen

        return view('Proyek.edit', compact('project', 'site', 'documents'));
    }

    public function update(Request $request, $kodeProyek){
        $request->validate([
            // Validasi untuk project
            'nama_project' => 'required|max:255',
            'deskripsi' => 'nullable|string',
            'anggaran' => 'nullable|numeric',
            'status' => 'required|in:planning,in_progress,completed,on_hold,cancelled',
            'jenis_proyek' => 'required|in:konstruksi,renovasi,pengadaan,lainnya',
            'jenis_proyek_lainnya' => 'required_if:jenis_proyek,lainnya|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',

            // Validasi untuk site
            'site.Kode_Site' => 'required|unique:sites,Kode_Site,'.$request->input('site.id'),
            'site.nama_site' => 'required|max:255',
            'site.jenis_site' => 'required|in:LOKASI_PEMBANGUNAN,WORKSHOP,WAREHOUSE',
            'site.alamat' => 'required|string',
            'site.desa_kelurahan' => 'required|string|max:255',
            'site.kecamatan' => 'required|string|max:255',
            'site.kabupaten_kota' => 'required|string|max:255',
            'site.provinsi' => 'required|string|max:255',
            'site.latitude' => 'required|numeric',
            'site.longitude' => 'required|numeric',

            // Validasi untuk documents
            'documents.*.nama_dokumen' => 'required|max:255',
            'documents.*.jenis_dokumen' => 'required|max:255',
            'documents.*.file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:30720', // 30MB dalam KB
        ]);

        // Update Site
        $siteData = $request->input('site');
        $site = Site::findOrFail($siteData['id']);
        $site->update($siteData);

        // Update Project
        $project = Projects::where('kode_project', $kodeProyek)->firstOrFail();
        $projectData = $request->only([
            'nama_project',
            'deskripsi',
            'anggaran',
            'status',
            'jenis_proyek',
            'jenis_proyek_lainnya',
            'tanggal_mulai',
            'tanggal_selesai',
            'penanggung_jawab',
            'kontak_penanggung_jawab',
            'klien',
            'kontak_klien',
            'catatan'
        ]);
        $projectData['tanggal_mulai'] = Carbon::parse($projectData['tanggal_mulai'])->format('Y-m-d');
        $projectData['tanggal_selesai'] = Carbon::parse($projectData['tanggal_selesai'])->format('Y-m-d');
        $project->update($projectData);

        return redirect()->route('projects.index')->with('success', 'Data proyek berhasil diubah.');
    }


    /**
     * =========================
     * Soft Delete
     * =========================
     */

    public function destroy($kodeProyek)
    {
        // Cari proyek berdasarkan kode proyek
        $project = Projects::where('kode_project', $kodeProyek)->firstOrFail();

        // Soft delete proyek
        $project->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus.');
    }


    /**
     * =========================================
     *
     * FINANCE SUB CONTROLLER UNTUK MENGATUR PROYEK
     *
     * =========================================
     *
     *
     * */

    public function financeIndex($kodeProyek)
    {
        // Ambil proyek berdasarkan kode proyek
        $project = Projects::where('kode_project', $kodeProyek)->firstOrFail();

        // Ambil semua dokumen bisnis terkait proyek ini
        $documents = BusinessDocuments::where('project_id', $project->id)->get();


        return view('BARANG.SiteRequest.HalamanPreviewFinance.list_finance', compact('project', 'documents'));
    }
}
