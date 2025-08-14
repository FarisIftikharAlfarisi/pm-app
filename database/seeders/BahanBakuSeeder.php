<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\ItemUnit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BahanBakuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Besi D-11 sampai D-14
        $diameters = [8 => 11.8 ,14 => 16.2, 13 => 15.8, 12 => 15.2, 11 => 14.6]; // berat per batang (KG)

        foreach ($diameters as $dia => $berat) {
            $barang = Barang::create([
                'kode_barang' => 'BESI-D' . $dia,
                'nama_barang' => 'BESI D-' . $dia,
                'kategori' => 'BAHAN_BAKU',
                'merk' => 'Krakatau Steel',
                'keterangan' => 'Besi diameter ' . $dia . ' mm',
                'creator_id' => 1, // Ganti dengan ID creator yang sesuai
                'is_visible' => true,
            ]);

            ItemUnit::insert([
                [
                    'barang_id' => $barang->id,
                    'unit_name' => 'BATANG',
                    'conversion_factor' => 1,
                    'deskripsi_konversi' => '1 Batang',
                    'is_default' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'barang_id' => $barang->id,
                    'unit_name' => 'KG',
                    'conversion_factor' => $berat,
                    'deskripsi_konversi' => "BERAT ± {$berat} KG PER BATANG",
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'barang_id' => $barang->id,
                    'unit_name' => 'METER',
                    'conversion_factor' => 12,
                    'deskripsi_konversi' => "PANJANG 12 METER, BERAT ± {$berat} KG PER BATANG",
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 2. Semen
        $semen = Barang::create([
            'kode_barang' => 'SEMEN-PORTLAND',
            'nama_barang' => 'Semen Portland',
            'kategori' => 'BAHAN_BAKU',
            'merk' => 'Tiga Roda',
            'keterangan' => 'Semen untuk konstruksi umum',
            'creator_id' => 1,
            'is_visible' => true,
        ]);

        ItemUnit::insert([
            [
                'barang_id' => $semen->id,
                'unit_name' => 'SAK',
                'conversion_factor' => 1,
                'deskripsi_konversi' => '1 SAK = 50 KG',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => $semen->id,
                'unit_name' => 'KG',
                'conversion_factor' => 50,
                'deskripsi_konversi' => '1 SAK = 50 KG',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Batu Split
        $batu = Barang::create([
            'kode_barang' => 'BATU-SPLIT',
            'nama_barang' => 'Batu Split',
            'kategori' => 'BAHAN_BAKU',
            'merk' => 'Lokal',
            'keterangan' => 'Material batu pecah untuk cor',
            'creator_id' => 1,
            'is_visible' => true,
        ]);

        ItemUnit::insert([
            [
                'barang_id' => $batu->id,
                'unit_name' => 'TRUK',
                'conversion_factor' => 1,
                'deskripsi_konversi' => '1 Truk = ±300 KG',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => $batu->id,
                'unit_name' => 'KG',
                'conversion_factor' => 300,
                'deskripsi_konversi' => '1 Truk = ±300 KG',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 4. Welding Rod
        $welding = Barang::create([
            'kode_barang' => 'WELD-ROD',
            'nama_barang' => 'Welding Rod',
            'kategori' => 'BAHAN_BAKU',
            'merk' => 'Kobe Steel',
            'keterangan' => 'Elektroda las',
            'creator_id' => 1,
            'is_visible' => true,
        ]);

        ItemUnit::insert([
            [
                'barang_id' => $welding->id,
                'unit_name' => 'BOX',
                'conversion_factor' => 1,
                'deskripsi_konversi' => '1 Box isi 50 batang',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => $welding->id,
                'unit_name' => 'PCS',
                'conversion_factor' => 50,
                'deskripsi_konversi' => '1 Box = 50 pcs',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 5. Kawat Bendrat
        $kawatBendrat = Barang::create([
            'kode_barang' => 'KAWAT-BENDRAT',
            'nama_barang' => 'Kawat Bendrat',
            'kategori' => 'BAHAN_BAKU',
            'merk' => 'Lokal',
            'keterangan' => 'Digunakan untuk pengikatan tulangan',
            'creator_id' => 1,
            'is_visible' => true,
        ]);

        ItemUnit::insert([
            [
                'barang_id' => $kawatBendrat->id,
                'unit_name' => 'KG',
                'conversion_factor' => 1,
                'deskripsi_konversi' => 'Satuan dasar',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 6. Kawat Las M8
        $kawatLasM8 = Barang::create([
            'kode_barang' => 'KAWAT-LAS-M8',
            'nama_barang' => 'Kawat Las M8',
            'kategori' => 'BAHAN_BAKU',
            'merk' => 'Kobe Steel',
            'keterangan' => 'Kawat las ukuran M8',
            'creator_id' => 1,
            'is_visible' => true,
        ]);

        ItemUnit::insert([
            [
                'barang_id' => $kawatLasM8->id,
                'unit_name' => 'KG',
                'conversion_factor' => 1,
                'deskripsi_konversi' => 'Satuan dasar',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 7. Kawat Las M6
        $kawatLasM6 = Barang::create([
            'kode_barang' => 'KAWAT-LAS-M6',
            'nama_barang' => 'Kawat Las M6',
            'kategori' => 'BAHAN_BAKU',
            'merk' => 'Kobe Steel',
            'keterangan' => 'Kawat las ukuran M6',
            'creator_id' => 1,
            'is_visible' => true,
        ]);

        ItemUnit::insert([
            [
                'barang_id' => $kawatLasM6->id,
                'unit_name' => 'KG',
                'conversion_factor' => 1,
                'deskripsi_konversi' => 'Satuan dasar',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 8. Pasir Beton
        $pasirBeton = Barang::create([
            'kode_barang' => 'PASIR-BETON',
            'nama_barang' => 'Pasir Beton',
            'kategori' => 'BAHAN_BAKU',
            'merk' => 'Lokal',
            'keterangan' => 'Pasir halus untuk pengecoran',
            'creator_id' => 1,
            'is_visible' => true,
        ]);

        ItemUnit::insert([
            [
                'barang_id' => $pasirBeton->id,
                'unit_name' => 'M3',
                'conversion_factor' => 1,
                'deskripsi_konversi' => 'Satuan volume (kubik)',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => $pasirBeton->id,
                'unit_name' => 'KG',
                'conversion_factor' => 1400, // asumsi 1m3 pasir ±1400 KG
                'deskripsi_konversi' => '1 M3 = ±1400 KG',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 9. Air Bersih
        $airBersih = Barang::create([
            'kode_barang' => 'AIR-BERSIH',
            'nama_barang' => 'Air Bersih',
            'kategori' => 'BAHAN_BAKU',
            'merk' => 'PDAM',
            'keterangan' => 'Air untuk campuran beton',
            'creator_id' => 1,
            'is_visible' => true,
        ]);

        ItemUnit::insert([
            [
                'barang_id' => $airBersih->id,
                'unit_name' => 'LITER',
                'conversion_factor' => 1,
                'deskripsi_konversi' => 'Satuan dasar',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 10. Additive Superplasticizer
        $additive = Barang::create([
            'kode_barang' => 'ADDITIVE-SP',
            'nama_barang' => 'Additive Superplasticizer',
            'kategori' => 'BAHAN_BAKU',
            'merk' => 'Sika',
            'keterangan' => 'Bahan tambahan untuk meningkatkan mutu beton',
            'creator_id' => 1,
            'is_visible' => true,
        ]);

        ItemUnit::insert([
            [
                'barang_id' => $additive->id,
                'unit_name' => 'LITER',
                'conversion_factor' => 1,
                'deskripsi_konversi' => 'Satuan dasar',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
