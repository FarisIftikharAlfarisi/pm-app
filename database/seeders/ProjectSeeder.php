<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Site;
use App\Models\Projects;
use App\Models\BusinessDocuments;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            $now = Carbon::now();
            $userId = 1; // sesuaikan dengan user yang ada

            // 1. Buat Site terlebih dahulu
            $site = Site::create([
                'id' => 1, // Explicitly set ID untuk konsistensi dengan WBS seeder
                'Kode_Site' => 'SITE-001',
                'nama_site' => 'Site Perumahan Green Valley',
                'jenis_site' => 'LOKASI_PEMBANGUNAN',
                'latitude' => '-6.9175',
                'longitude' => '107.6191',
                'alamat' => 'Jl. Raya Bandung No. 123',
                'desa_kelurahan' => 'Sukajadi',
                'kecamatan' => 'Sukajadi',
                'kabupaten_kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
            ]);

            // 2. Buat Project yang terintegrasi dengan WBS
            $project = Projects::create([
                'id' => 1, // Explicitly set ID untuk konsistensi dengan WBS seeder
                'kode_project' => 'PROJ-2025-001',
                'nama_project' => 'Pembangunan Rumah Tipe 60',
                'deskripsi' => 'Proyek pembangunan rumah minimalis modern tipe 60 dengan 2 lantai, dilengkapi dengan fasilitas modern dan design kontemporer.',
                'anggaran' => 500000000, // 500 juta
                'jenis_proyek' => 'konstruksi',
                'jenis_proyek_lainnya' => null,
                'tanggal_mulai' => $now, // Sama dengan start_date WBS pertama
                'tanggal_selesai' => $now->copy()->addDays(70), // 5 hari buffer setelah WBS terakhir (65 + 5)
                'penanggung_jawab' => 'Ir. Ahmad Suryadi',
                'kontak_penanggung_jawab' => '081234567890',
                'klien' => 'PT. Green Valley Development',
                'kontak_klien' => '081987654321',
                'catatan' => 'Proyek prioritas dengan timeline ketat. Koordinasi intensif diperlukan untuk memastikan semua milestone tercapai sesuai jadwal.',
                'site_id' => $site->id,
                'created_by' => $userId,
                'status' => 'planning', // Status awal proyek
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // 3. Buat sample dokumen proyek
            $documents = [
                [
                    'kode_dokumen' => 'PROJ-2025-001__SITE-001__IMB__' . $now->format('dmy') . '__01__' . $now->timestamp . '_IMB_Rumah_Tipe_60.pdf',
                    'nama_dokumen' => 'Izin Mendirikan Bangunan',
                    'jenis_dokumen' => 'Izin Mendirikan Bangunan (IMB)',
                    'file_path' => '/storage/DokumenProyek/PROJ-2025-001__SITE-001__IMB__' . $now->format('dmy') . '__01__' . $now->timestamp . '_IMB_Rumah_Tipe_60.pdf',
                ],
                [
                    'kode_dokumen' => 'PROJ-2025-001__SITE-001__RAB__' . $now->format('dmy') . '__02__' . $now->timestamp . '_RAB_Detail_Proyek.xlsx',
                    'nama_dokumen' => 'Rencana Anggaran Biaya Detail',
                    'jenis_dokumen' => 'Rencana Anggaran Biaya (RAB)',
                    'file_path' => '/storage/DokumenProyek/PROJ-2025-001__SITE-001__RAB__' . $now->format('dmy') . '__02__' . $now->timestamp . '_RAB_Detail_Proyek.xlsx',
                ],
                [
                    'kode_dokumen' => 'PROJ-2025-001__SITE-001__GAMBAR__' . $now->format('dmy') . '__03__' . $now->timestamp . '_Gambar_Teknik_Arsitektural.dwg',
                    'nama_dokumen' => 'Gambar Teknik Arsitektural',
                    'jenis_dokumen' => 'Gambar Teknik',
                    'file_path' => '/storage/DokumenProyek/PROJ-2025-001__SITE-001__GAMBAR__' . $now->format('dmy') . '__03__' . $now->timestamp . '_Gambar_Teknik_Arsitektural.dwg',
                ],
                [
                    'kode_dokumen' => 'PROJ-2025-001__SITE-001__KONTRAK__' . $now->format('dmy') . '__04__' . $now->timestamp . '_Kontrak_Kerja_Konstruksi.pdf',
                    'nama_dokumen' => 'Kontrak Kerja Konstruksi',
                    'jenis_dokumen' => 'Kontrak',
                    'file_path' => '/storage/DokumenProyek/PROJ-2025-001__SITE-001__KONTRAK__' . $now->format('dmy') . '__04__' . $now->timestamp . '_Kontrak_Kerja_Konstruksi.pdf',
                ],
                [
                    'kode_dokumen' => 'PROJ-2025-001__SITE-001__SPESIFIKASI__' . $now->format('dmy') . '__05__' . $now->timestamp . '_Spesifikasi_Teknis_Material.pdf',
                    'nama_dokumen' => 'Spesifikasi Teknis Material',
                    'jenis_dokumen' => 'Spesifikasi Teknis',
                    'file_path' => '/storage/DokumenProyek/PROJ-2025-001__SITE-001__SPESIFIKASI__' . $now->format('dmy') . '__05__' . $now->timestamp . '_Spesifikasi_Teknis_Material.pdf',
                ]
            ];

            foreach ($documents as $doc) {
                BusinessDocuments::create([
                    'kode_dokumen' => $doc['kode_dokumen'],
                    'project_id' => $project->id,
                    'site_id' => $site->id,
                    'nama_dokumen' => $doc['nama_dokumen'],
                    'jenis_dokumen' => $doc['jenis_dokumen'],
                    'file_path' => $doc['file_path'],
                    'user_id' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::commit();

            $this->command->info('Seeding Projects dan Site berhasil!');
            $this->command->info('Project ID: ' . $project->id);
            $this->command->info('Site ID: ' . $site->id);
            $this->command->info('Project Duration: ' . $now->format('d/m/Y') . ' - ' . $now->copy()->addDays(70)->format('d/m/Y'));
            $this->command->info('Total Dokumen: ' . count($documents) . ' dokumen');
            $this->command->info('');
            $this->command->info('PENTING: Pastikan untuk menjalankan seeder ini SEBELUM WBSSeeder!');
            $this->command->info('Urutan yang benar:');
            $this->command->info('1. php artisan db:seed --class=ProjectsSeeder');
            $this->command->info('2. php artisan db:seed --class=WBSSeeder');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Gagal seeding Projects: ' . $e->getMessage());
            throw $e;
        }
    }
}
