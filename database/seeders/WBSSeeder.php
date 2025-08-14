<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\TaskToDo;
use App\Models\KebutuhanBarangWbs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WBSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset data dulu (opsional, hati-hati untuk produksi)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // DB::table('kebutuhan_barang_wbs')->truncate();
        // DB::table('task_to_dos')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $projectId = 1; // sesuaikan dengan project yang ada
        $siteId = 1;    // sesuaikan dengan site yang ada
        $userId = 1;    // creator user id
        $accountingId = 2; // user akuntansi
        $projectLeaderId = 3; // project leader
        $now = Carbon::now();

        // Helper untuk insert dan ambil id
        $tasks = [];

        $createTask = function ($data) use (&$tasks) {
            $task = TaskToDo::create($data);
            $tasks[$data['kode_task']] = $task->id;
            return $task;
        };

        // Helper untuk menambah kebutuhan barang
        $addMaterialRequirement = function ($taskId, $materials) {
            foreach ($materials as $material) {
                KebutuhanBarangWbs::create([
                    'task_to_do_id' => $taskId,
                    'barang_id' => $material['barang_id'],
                    'satuan_id' => $material['satuan_id'],
                    'jumlah' => $material['jumlah'],
                ]);
            }
        };

        // === Level 1: Main WBS ===
        $task1 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'kode_task' => 'WBS-001',
            'nama_task' => 'Persiapan Proyek Rumah',
            'deskripsi' => 'Tahap persiapan awal sebelum konstruksi dimulai',
            'type' => 'general',
            'sort_order' => 1,
            'start_date' => $now,
            'end_date' => $now->copy()->addDays(5),
            'buffer_days' => 2,
            'status' => 'pending',
            'priority_level' => '2',
            'is_milestone' => false,
            'is_critical_path' => false, // Bukan critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task2 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'kode_task' => 'WBS-002',
            'nama_task' => 'Pekerjaan Struktur Rumah',
            'deskripsi' => 'Pekerjaan struktur utama rumah termasuk pondasi dan rangka',
            'type' => 'production',
            'sort_order' => 2,
            'start_date' => $now->copy()->addDays(6),
            'end_date' => $now->copy()->addDays(35),
            'buffer_days' => 3,
            'status' => 'pending',
            'priority_level' => '1', // Priority tinggi
            'is_milestone' => true, // Milestone penting
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task3 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'kode_task' => 'WBS-003',
            'nama_task' => 'Finishing & Utilitas Rumah',
            'deskripsi' => 'Pekerjaan finishing dan instalasi utilitas rumah',
            'type' => 'production',
            'sort_order' => 3,
            'start_date' => $now->copy()->addDays(36),
            'end_date' => $now->copy()->addDays(65),
            'buffer_days' => 5,
            'status' => 'pending',
            'priority_level' => '2',
            'is_milestone' => true, // Milestone akhir
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        // === Level 2: Sub Tasks untuk Persiapan ===

        $task1_1 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-001'],
            'kode_task' => 'WBS-001.1',
            'nama_task' => 'Pembersihan Lahan',
            'deskripsi' => 'Membersihkan lahan dari rerumputan dan puing-puing',
            'type' => 'general',
            'sort_order' => 1,
            'start_date' => $now,
            'end_date' => $now->copy()->addDays(2),
            'buffer_days' => 1,
            'status' => 'pending',
            'priority_level' => '3',
            'is_milestone' => false,
            'is_critical_path' => false,
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task1_2 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-001'],
            'kode_task' => 'WBS-001.2',
            'nama_task' => 'Pengukuran & Pematokan',
            'deskripsi' => 'Melakukan pengukuran dan pematokan untuk layout rumah',
            'type' => 'general',
            'sort_order' => 2,
            'start_date' => $now->copy()->addDays(3),
            'end_date' => $now->copy()->addDays(5),
            'buffer_days' => 1,
            'status' => 'pending',
            'priority_level' => '2',
            'is_milestone' => false,
            'is_critical_path' => false,
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        // === Level 2: Sub Tasks untuk Struktur ===

        $task2_1 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-002'],
            'kode_task' => 'WBS-002.1',
            'nama_task' => 'Galian Pondasi',
            'deskripsi' => 'Penggalian tanah untuk pondasi rumah',
            'type' => 'production',
            'sort_order' => 1,
            'start_date' => $now->copy()->addDays(6),
            'end_date' => $now->copy()->addDays(8),
            'buffer_days' => 1,
            'status' => 'pending',
            'priority_level' => '1',
            'is_milestone' => false,
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task2_2 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-002'],
            'kode_task' => 'WBS-002.2',
            'nama_task' => 'Pemasangan Tulangan Pondasi',
            'deskripsi' => 'Pemasangan besi tulangan dan bekisting pondasi',
            'type' => 'production',
            'sort_order' => 2,
            'start_date' => $now->copy()->addDays(9),
            'end_date' => $now->copy()->addDays(13),
            'buffer_days' => 2,
            'status' => 'pending',
            'priority_level' => '1',
            'is_milestone' => false,
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task2_3 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-002'],
            'kode_task' => 'WBS-002.3',
            'nama_task' => 'Pengecoran Pondasi',
            'deskripsi' => 'Pengecoran beton untuk pondasi',
            'type' => 'production',
            'sort_order' => 3,
            'start_date' => $now->copy()->addDays(14),
            'end_date' => $now->copy()->addDays(16),
            'buffer_days' => 1,
            'status' => 'pending',
            'priority_level' => '1',
            'is_milestone' => true, // Milestone pondasi selesai
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task2_4 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-002'],
            'kode_task' => 'WBS-002.4',
            'nama_task' => 'Struktur Kolom & Balok Lantai 1',
            'deskripsi' => 'Pembuatan kolom dan balok lantai 1',
            'type' => 'production',
            'sort_order' => 4,
            'start_date' => $now->copy()->addDays(17),
            'end_date' => $now->copy()->addDays(25),
            'buffer_days' => 2,
            'status' => 'pending',
            'priority_level' => '1',
            'is_milestone' => false,
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task2_5 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-002'],
            'kode_task' => 'WBS-002.5',
            'nama_task' => 'Struktur Kolom & Balok Lantai 2',
            'deskripsi' => 'Pembuatan kolom dan balok lantai 2',
            'type' => 'production',
            'sort_order' => 5,
            'start_date' => $now->copy()->addDays(26),
            'end_date' => $now->copy()->addDays(35),
            'buffer_days' => 3,
            'status' => 'pending',
            'priority_level' => '1',
            'is_milestone' => true, // Milestone struktur selesai
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        // === Level 2: Sub Tasks untuk Finishing ===

        $task3_1 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-003'],
            'kode_task' => 'WBS-003.1',
            'nama_task' => 'Pemasangan Dinding Bata',
            'deskripsi' => 'Pemasangan dinding bata merah dan plesteran',
            'type' => 'production',
            'sort_order' => 1,
            'start_date' => $now->copy()->addDays(36),
            'end_date' => $now->copy()->addDays(45),
            'buffer_days' => 2,
            'status' => 'pending',
            'priority_level' => '2',
            'is_milestone' => false,
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task3_2 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-003'],
            'kode_task' => 'WBS-003.2',
            'nama_task' => 'Pemasangan Atap',
            'deskripsi' => 'Pemasangan rangka dan genteng atap',
            'type' => 'production',
            'sort_order' => 2,
            'start_date' => $now->copy()->addDays(40), // Bisa paralel dengan dinding
            'end_date' => $now->copy()->addDays(50),
            'buffer_days' => 3,
            'status' => 'pending',
            'priority_level' => '1',
            'is_milestone' => false,
            'is_critical_path' => false, // Bukan critical path, bisa paralel
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task3_3 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-003'],
            'kode_task' => 'WBS-003.3',
            'nama_task' => 'Instalasi Listrik',
            'deskripsi' => 'Instalasi kabel listrik dan panel MCB',
            'type' => 'procurement',
            'sort_order' => 3,
            'start_date' => $now->copy()->addDays(46),
            'end_date' => $now->copy()->addDays(52),
            'buffer_days' => 2,
            'status' => 'pending',
            'priority_level' => '2',
            'is_milestone' => false,
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task3_4 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-003'],
            'kode_task' => 'WBS-003.4',
            'nama_task' => 'Instalasi Air & Sanitasi',
            'deskripsi' => 'Instalasi pipa air bersih dan sanitasi',
            'type' => 'procurement',
            'sort_order' => 4,
            'start_date' => $now->copy()->addDays(48), // Paralel dengan listrik
            'end_date' => $now->copy()->addDays(55),
            'buffer_days' => 2,
            'status' => 'pending',
            'priority_level' => '2',
            'is_milestone' => false,
            'is_critical_path' => false, // Bukan critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        $task3_5 = $createTask([
            'site_id' => $siteId,
            'project_id' => $projectId,
            'parent_id' => $tasks['WBS-003'],
            'kode_task' => 'WBS-003.5',
            'nama_task' => 'Finishing Akhir & Pengecatan',
            'deskripsi' => 'Pengecatan dan finishing akhir rumah',
            'type' => 'production',
            'sort_order' => 5,
            'start_date' => $now->copy()->addDays(56),
            'end_date' => $now->copy()->addDays(65),
            'buffer_days' => 3,
            'status' => 'pending',
            'priority_level' => '2',
            'is_milestone' => true, // Milestone akhir proyek
            'is_critical_path' => true, // Critical path
            'is_locked' => false,
            'accounting_id' => $accountingId,
            'project_leader_id' => $projectLeaderId,
            'accounting_approve' => 'pending',
            'project_leader_approve' => 'pending',
            'created_by' => $userId,
        ]);

        // === MENAMBAHKAN KEBUTUHAN BARANG PER TASK ===

        // Kebutuhan barang untuk Galian Pondasi (WBS-002.1)
        // Tidak memerlukan barang dari inventory

       // Kebutuhan barang untuk Pemasangan Tulangan Pondasi (WBS-002.2)
        $addMaterialRequirement($tasks['WBS-002.2'], [
            [
                'barang_id' => 2, // BESI-D13 (barang_id 2)
                'satuan_id' => 4, // BATANG (default unit untuk besi D13)
                'jumlah' => 30
            ],
            [
                'barang_id' => 3, // BESI-D12 (barang_id 3)
                'satuan_id' => 7, // BATANG (default unit untuk besi D12)
                'jumlah' => 50
            ],
            [
                'barang_id' => 8, // WELD-ROD (barang_id 8)
                'satuan_id' => 21, // PCS (dari 1 Box = 50 pcs)
                'jumlah' => 25 // 25 pcs welding rod
            ]
        ]);

        // Kebutuhan barang untuk Pengecoran Pondasi (WBS-002.3)
        $addMaterialRequirement($tasks['WBS-002.3'], [
            [
                'barang_id' => 6, // SEMEN-PORTLAND (barang_id 6)
                'satuan_id' => 16, // SAK (default unit untuk semen)
                'jumlah' => 20 // 20 sak semen
            ],
            [
                'barang_id' => 7, // BATU-SPLIT (barang_id 7)
                'satuan_id' => 18, // TRUK (default unit untuk batu split)
                'jumlah' => 2 // 2 truk batu split
            ],
            [
                'barang_id' => 12, // PASIR (barang_id 12)
                'satuan_id' => 25, // M3 (default unit untuk pasir)
                'jumlah' => 3 // 3 m3 pasir
            ]
        ]);

        // Kebutuhan barang untuk Struktur Kolom & Balok Lantai 1 (WBS-002.4)
        $addMaterialRequirement($tasks['WBS-002.4'], [
            [
                'barang_id' => 1, // BESI-D14 (barang_id 1)
                'satuan_id' => 1, // BATANG (default unit untuk besi D14)
                'jumlah' => 80 // 80 batang besi D14
            ],
            [
                'barang_id' => 2, // BESI-D13 (barang_id 2)
                'satuan_id' => 4, // BATANG (default unit untuk besi D13)
                'jumlah' => 60 // 60 batang besi D13
            ],
            [
                'barang_id' => 3, // BESI-D12 (barang_id 3)
                'satuan_id' => 7, // BATANG (default unit untuk besi D12)
                'jumlah' => 100 // 100 batang besi D12
            ],
            [
                'barang_id' => 6, // SEMEN-PORTLAND (barang_id 6)
                'satuan_id' => 16, // SAK (default unit untuk semen)
                'jumlah' => 35 // 35 sak semen
            ],
            [
                'barang_id' => 8, // WELD-ROD (barang_id 8)
                'satuan_id' => 21, // PCS (dari 1 Box = 50 pcs)
                'jumlah' => 40 // 40 pcs welding rod
            ],
            [
                'barang_id' => 17, // WIREMESH (barang_id 17)
                'satuan_id' => 34, // LEMBAR (default unit untuk wiremesh)
                'jumlah' => 8 // 8 lembar wiremesh
            ]
        ]);

        // Kebutuhan barang untuk Struktur Kolom & Balok Lantai 2 (WBS-002.5)
        $addMaterialRequirement($tasks['WBS-002.5'], [
            [
                'barang_id' => 1, // BESI-D14 (barang_id 1)
                'satuan_id' => 1, // BATANG (default unit untuk besi D14)
                'jumlah' => 70 // 70 batang besi D14
            ],
            [
                'barang_id' => 2, // BESI-D13 (barang_id 2)
                'satuan_id' => 4, // BATANG (default unit untuk besi D13)
                'jumlah' => 50 // 50 batang besi D13
            ],
            [
                'barang_id' => 3, // BESI-D12 (barang_id 3)
                'satuan_id' => 7, // BATANG (default unit untuk besi D12)
                'jumlah' => 80 // 80 batang besi D12
            ],
            [
                'barang_id' => 6, // SEMEN-PORTLAND (barang_id 6)
                'satuan_id' => 16, // SAK (default unit untuk semen)
                'jumlah' => 30 // 30 sak semen
            ],
            [
                'barang_id' => 8, // WELD-ROD (barang_id 8)
                'satuan_id' => 21, // PCS (dari 1 Box = 50 pcs)
                'jumlah' => 30 // 30 pcs welding rod
            ],
            [
                'barang_id' => 17, // WIREMESH (barang_id 17)
                'satuan_id' => 34, // LEMBAR (default unit untuk wiremesh)
                'jumlah' => 6 // 6 lembar wiremesh
            ]
        ]);

        // Kebutuhan barang untuk Pemasangan Dinding Bata (WBS-003.1)
        $addMaterialRequirement($tasks['WBS-003.1'], [
            [
                'barang_id' => 6, // SEMEN-PORTLAND (barang_id 6)
                'satuan_id' => 16, // SAK (default unit untuk semen)
                'jumlah' => 25 // 25 sak semen untuk plesteran
            ],
            [
                'barang_id' => 12, // PASIR (barang_id 12)
                'satuan_id' => 25, // M3 (default unit untuk pasir)
                'jumlah' => 5 // 5 m3 pasir untuk plesteran
            ],
            [
                'barang_id' => 19, // BATA MERAH (barang_id 19)
                'satuan_id' => 40, // M3 (default unit untuk bata)
                'jumlah' => 8 // 8 m3 bata merah
            ]
        ]);

        // Kebutuhan barang untuk Pemasangan Atap (WBS-003.2)
        $addMaterialRequirement($tasks['WBS-003.2'], [
            [
                'barang_id' => 16, // RANGKA ATAP (barang_id 16)
                'satuan_id' => 32, // SET (default unit untuk rangka atap)
                'jumlah' => 1 // 1 set rangka atap
            ],
            [
                'barang_id' => 20, // GENTENG (barang_id 20)
                'satuan_id' => 43, // M3 (default unit untuk genteng)
                'jumlah' => 2 // 2 m3 genteng
            ]
        ]);

        // Kebutuhan barang untuk Instalasi Listrik (WBS-003.3)
        $addMaterialRequirement($tasks['WBS-003.3'], [
            [
                'barang_id' => 15, // KABEL LISTRIK (barang_id 15)
                'satuan_id' => 29, // PCS (default unit untuk kabel)
                'jumlah' => 50 // 50 pcs kabel listrik
            ],
            [
                'barang_id' => 8, // WELD-ROD (barang_id 8) - untuk sambungan
                'satuan_id' => 21, // PCS (dari 1 Box = 50 pcs)
                'jumlah' => 10 // 10 pcs untuk sambungan listrik
            ]
        ]);

        // Kebutuhan barang untuk Instalasi Air & Sanitasi (WBS-003.4)
        $addMaterialRequirement($tasks['WBS-003.4'], [
            [
                'barang_id' => 13, // SOLAR/MINYAK TANAH (barang_id 13) - untuk lem pipa
                'satuan_id' => 27, // LITER (default unit)
                'jumlah' => 5 // 5 liter lem pipa
            ],
            [
                'barang_id' => 14, // BENSIN (barang_id 14) - untuk pembersih
                'satuan_id' => 28, // LITER (default unit)
                'jumlah' => 3 // 3 liter pembersih
            ]
        ]);

        // Kebutuhan barang untuk Finishing Akhir & Pengecatan (WBS-003.5)
        $addMaterialRequirement($tasks['WBS-003.5'], [
            [
                'barang_id' => 6, // SEMEN-PORTLAND (barang_id 6)
                'satuan_id' => 16, // SAK (default unit untuk semen)
                'jumlah' => 15 // 15 sak semen untuk finishing
            ],
            [
                'barang_id' => 12, // PASIR (barang_id 12)
                'satuan_id' => 25, // M3 (default unit untuk pasir)
                'jumlah' => 3 // 3 m3 pasir halus
            ]
        ]);

        $this->command->info('Seeding WBS dengan CPM/PERT structure berhasil!');
        $this->command->info('Critical Path: WBS-002.1 → WBS-002.2 → WBS-002.3 → WBS-002.4 → WBS-002.5 → WBS-003.1 → WBS-003.3 → WBS-003.5');
        $this->command->info('Non-Critical: WBS-001.1, WBS-001.2, WBS-003.2, WBS-003.4 (dapat dilakukan paralel)');
    }
}
