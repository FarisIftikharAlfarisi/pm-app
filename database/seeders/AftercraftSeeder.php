<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang;
use App\Models\ItemUnit;
use Illuminate\Database\Seeder;
use App\Models\BillOfMaterialBarang;
use App\Models\BillOfMaterialComponent;
use App\Models\BillOfMaterialComponents;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AftercraftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data barang aftercraft dengan urutan yang benar:
        // 1. Sengkang dulu
        // 2. Rangka yang menggunakan sengkang
        // 3. Produk lainnya
        $aftercraftItems = [
            // 1. SENGKANG D8 SLOOF (dibuat dulu karena dibutuhkan untuk rangka)
            [
                'barang' => [
                    'kode_barang' => 'AFC-001',
                    'nama_barang' => 'Sengkang D8 Sloof 18x25cm',
                    'kategori' => 'AFTERCRAFT',
                    'keterangan' => 'Sengkang sloof dari besi D8 ukuran dikurangi 2cm dari sloof 20x27cm',
                    'creator_id' => 1,
                ],
                'bom' => [
                    'kode_bom' => 'BOM-AFC-001',
                    'nama_bom' => 'BOM Sengkang D8 Sloof 18x25cm',
                    'quantity' => 50.00,
                    'unit_of_measure' => 'Pcs',
                    'status' => 'ACTIVE',
                    'catatan_produksi' => 'Bengkok presisi, kait 135 derajat minimum 6d, ukuran dalam 18x25cm',
                    'estimasi_waktu_produksi' => 360, // 6 jam dalam menit
                    'satuan_estimasi_waktu_produksi' => 'MENIT',
                    'creator_id' => 1,
                ],
                'components' => [
                    ['nama' => 'BESI D-8', 'quantity' => 15, 'unit_of_measure' => 'BATANG', 'toleransi' => 0, 'waktu_per_unit' => 20],
                    ['nama' => 'Kawat Bendrat', 'quantity' => 0.3, 'unit_of_measure' => 'KG', 'toleransi' => 20, 'waktu_per_unit' => 10],
                ]
            ],

            // 2. RANGKA BESI SLOOF (menggunakan sengkang yang sudah dibuat)
            [
                'barang' => [
                    'kode_barang' => 'AFC-002',
                    'nama_barang' => 'Rangka Besi Sloof D13-15 dengan Sengkang',
                    'kategori' => 'AFTERCRAFT',
                    'keterangan' => 'Rangka besi sloof diameter 13-15mm dengan sengkang D8',
                    'creator_id' => 1,
                ],
                'bom' => [
                    'kode_bom' => 'BOM-AFC-002',
                    'nama_bom' => 'BOM Rangka Besi Sloof D13-15',
                    'quantity' => 1.00,
                    'unit_of_measure' => 'Set',
                    'status' => 'ACTIVE',
                    'catatan_produksi' => 'Overlap tulangan minimum 40d, sengkang jarak 150mm',
                    'estimasi_waktu_produksi' => 240, // 4 jam dalam menit
                    'satuan_estimasi_waktu_produksi' => 'MENIT',
                    'creator_id' => 1,
                ],
                'components' => [
                    ['nama' => 'BESI D-13', 'quantity' => 4, 'unit_of_measure' => 'BATANG', 'toleransi' => 0, 'waktu_per_unit' => 30],
                    ['nama' => 'BESI D-14', 'quantity' => 2, 'unit_of_measure' => 'BATANG', 'toleransi' => 0, 'waktu_per_unit' => 20],
                    ['nama' => 'Sengkang D8 Sloof 18x25cm', 'quantity' => 20, 'unit_of_measure' => 'Pcs', 'toleransi' => 5, 'waktu_per_unit' => 5],
                    ['nama' => 'Kawat Bendrat', 'quantity' => 0.8, 'unit_of_measure' => 'KG', 'toleransi' => 10, 'waktu_per_unit' => 15],
                ]
            ],

            // 3. WIREMESH M8 150x150mm
            [
                'barang' => [
                    'kode_barang' => 'AFC-003',
                    'nama_barang' => 'Wiremesh M8 150x150mm - 2.1x5.4m',
                    'kategori' => 'AFTERCRAFT',
                    'keterangan' => 'Wiremesh M8 dengan jarak 150x150mm ukuran 2.1x5.4 meter',
                    'creator_id' => 1,
                ],
                'bom' => [
                    'kode_bom' => 'BOM-AFC-003',
                    'nama_bom' => 'BOM Wiremesh M8 2.1x5.4m',
                    'quantity' => 1.00,
                    'unit_of_measure' => 'Lembar',
                    'status' => 'ACTIVE',
                    'catatan_produksi' => 'Las titik setiap persilangan, kontrol kualitas dimensi',
                    'estimasi_waktu_produksi' => 180, // 3 jam dalam menit
                    'satuan_estimasi_waktu_produksi' => 'MENIT',
                    'creator_id' => 1,
                ],
                'components' => [
                    ['nama' => 'Kawat Las M8', 'quantity' => 45, 'unit_of_measure' => 'KG', 'toleransi' => 5, 'waktu_per_unit' => 3],
                    ['nama' => 'Welding Rod', 'quantity' => 2, 'unit_of_measure' => 'KG', 'toleransi' => 10, 'waktu_per_unit' => 5],
                ]
            ],

            // 4. WIREMESH M6 100x100mm
            [
                'barang' => [
                    'kode_barang' => 'AFC-004',
                    'nama_barang' => 'Wiremesh M6 100x100mm - 2.1x5.4m',
                    'kategori' => 'AFTERCRAFT',
                    'keterangan' => 'Wiremesh M6 dengan jarak 100x100mm untuk lantai',
                    'creator_id' => 1,
                ],
                'bom' => [
                    'kode_bom' => 'BOM-AFC-004',
                    'nama_bom' => 'BOM Wiremesh M6 2.1x5.4m',
                    'quantity' => 1.00,
                    'unit_of_measure' => 'Lembar',
                    'status' => 'ACTIVE',
                    'catatan_produksi' => 'Las titik berkualitas, galvanis coating optional',
                    'estimasi_waktu_produksi' => 150, // 2.5 jam dalam menit
                    'satuan_estimasi_waktu_produksi' => 'MENIT',
                    'creator_id' => 1,
                ],
                'components' => [
                    ['nama' => 'Kawat Las M6', 'quantity' => 35, 'unit_of_measure' => 'KG', 'toleransi' => 5, 'waktu_per_unit' => 3],
                    ['nama' => 'Welding Rod', 'quantity' => 1.5, 'unit_of_measure' => 'KG', 'toleransi' => 10, 'waktu_per_unit' => 5],
                ]
            ],

            // 5. BETON PRECAST K300 - Panel Dinding
            [
                'barang' => [
                    'kode_barang' => 'AFC-005',
                    'nama_barang' => 'Beton Precast K300 - Panel Dinding',
                    'kategori' => 'AFTERCRAFT',
                    'keterangan' => 'Panel beton precast K300 untuk dinding struktur',
                    'creator_id' => 1,
                ],
                'bom' => [
                    'kode_bom' => 'BOM-AFC-005',
                    'nama_bom' => 'BOM Beton Precast K300 Panel',
                    'quantity' => 1.00,
                    'unit_of_measure' => 'M3',
                    'status' => 'ACTIVE',
                    'catatan_produksi' => 'Steam curing 12 jam, kontrol suhu max 80°C, test slump 10-15cm',
                    'estimasi_waktu_produksi' => 2880, // 2 hari dalam menit
                    'satuan_estimasi_waktu_produksi' => 'MENIT',
                    'creator_id' => 1,
                ],
                'components' => [
                    ['nama' => 'Semen Portland', 'quantity' => 450, 'unit_of_measure' => 'KG', 'toleransi' => 3, 'waktu_per_unit' => 1],
                    ['nama' => 'Pasir Beton', 'quantity' => 650, 'unit_of_measure' => 'KG', 'toleransi' => 5, 'waktu_per_unit' => 1],
                    ['nama' => 'Batu Split', 'quantity' => 1200, 'unit_of_measure' => 'KG', 'toleransi' => 5, 'waktu_per_unit' => 1],
                    ['nama' => 'Air Bersih', 'quantity' => 180, 'unit_of_measure' => 'Liter', 'toleransi' => 10, 'waktu_per_unit' => 1],
                    ['nama' => 'Additive Superplasticizer', 'quantity' => 2.5, 'unit_of_measure' => 'KG', 'toleransi' => 5, 'waktu_per_unit' => 2],
                    ['nama' => 'Wiremesh M8 150x150mm - 2.1x5.4m', 'quantity' => 1, 'unit_of_measure' => 'Lembar', 'toleransi' => 0, 'waktu_per_unit' => 30],
                ]
            ],

            // 6. BETON PRECAST K250 - Balok
            [
                'barang' => [
                    'kode_barang' => 'AFC-006',
                    'nama_barang' => 'Beton Precast K250 - Balok',
                    'kategori' => 'AFTERCRAFT',
                    'keterangan' => 'Balok beton precast K250 dengan tulangan',
                    'creator_id' => 1,
                ],
                'bom' => [
                    'kode_bom' => 'BOM-AFC-006',
                    'nama_bom' => 'BOM Beton Precast K250 Balok',
                    'quantity' => 1.00,
                    'unit_of_measure' => 'M3',
                    'status' => 'ACTIVE',
                    'catatan_produksi' => 'Curing standar 28 hari, kontrol kualitas setiap batch',
                    'estimasi_waktu_produksi' => 1440, // 1 hari dalam menit
                    'satuan_estimasi_waktu_produksi' => 'MENIT',
                    'creator_id' => 1,
                ],
                'components' => [
                    ['nama' => 'Semen Portland', 'quantity' => 400, 'unit_of_measure' => 'KG', 'toleransi' => 3, 'waktu_per_unit' => 1],
                    ['nama' => 'Pasir Beton', 'quantity' => 680, 'unit_of_measure' => 'KG', 'toleransi' => 5, 'waktu_per_unit' => 1],
                    ['nama' => 'Batu Split', 'quantity' => 1150, 'unit_of_measure' => 'KG', 'toleransi' => 5, 'waktu_per_unit' => 1],
                    ['nama' => 'Air Bersih', 'quantity' => 190, 'unit_of_measure' => 'Liter', 'toleransi' => 10, 'waktu_per_unit' => 1],
                    ['nama' => 'Rangka Besi Sloof D13-15 dengan Sengkang', 'quantity' => 1, 'unit_of_measure' => 'Set', 'toleransi' => 0, 'waktu_per_unit' => 60],
                ]
            ],
        ];

        foreach ($aftercraftItems as $item) {
            // Create Barang
            $barang = Barang::create($item['barang']);

            // Create Item Units for this barang
            $this->createItemUnits($barang, $item['bom']['unit_of_measure']);

            // Create BOM
            $bomData = $item['bom'];
            $bomData['barang_id'] = $barang->id; // Tambahkan barang_id ke BOM
            $bom = BillOfMaterialBarang::create($bomData);

            // Create BOM Components
            foreach ($item['components'] as $component) {
                // Find component barang by exact name match first, then by LIKE
                $componentBarang = Barang::where('nama_barang', $component['nama'])->first();

                if (!$componentBarang) {
                    $componentBarang = Barang::where('nama_barang', 'LIKE', '%' . $component['nama'] . '%')->first();
                }

                if ($componentBarang) {
                    BillOfMaterialComponents::create([
                        'bom_id' => $bom->id,
                        'bahan_baku_id' => $componentBarang->id,
                        'quantity' => $component['quantity'],
                        'unit_of_measure' => $component['unit_of_measure'],
                        'toleransi_quantity' => $component['toleransi'],
                        'waktu_produksi_per_unit' => $component['waktu_per_unit'],
                        'creator_id' => 1,
                    ]);
                } else {
                    // Log missing component for reference
                    $this->command->warn('Component barang not found: ' . $component['nama']);
                }
            }
        }

        $this->command->info('Aftercraft items seeded successfully with correct order!');
    }

    /**
     * Create item units for aftercraft products
     */
    private function createItemUnits($barang, $primaryUnit)
    {
        $units = [];

        // Define unit conversions based on product type and primary unit
        switch ($primaryUnit) {
            case 'Pcs':
                $units = [
                    [
                        'unit_name' => 'PCS',
                        'conversion_factor' => 1,
                        'deskripsi_konversi' => '1 Pcs',
                        'is_default' => true,
                    ],
                    [
                        'unit_name' => 'LUSIN',
                        'conversion_factor' => 12,
                        'deskripsi_konversi' => '1 Lusin = 12 Pcs',
                        'is_default' => false,
                    ],
                    [
                        'unit_name' => 'GROSS',
                        'conversion_factor' => 144,
                        'deskripsi_konversi' => '1 Gross = 144 Pcs',
                        'is_default' => false,
                    ],
                ];
                break;

            case 'Set':
                $units = [
                    [
                        'unit_name' => 'SET',
                        'conversion_factor' => 1,
                        'deskripsi_konversi' => '1 Set',
                        'is_default' => true,
                    ],
                    [
                        'unit_name' => 'PCS',
                        'conversion_factor' => 1,
                        'deskripsi_konversi' => '1 Set = 1 Unit Rangka',
                        'is_default' => false,
                    ],
                ];
                break;

            case 'Lembar':
                $units = [
                    [
                        'unit_name' => 'LEMBAR',
                        'conversion_factor' => 1,
                        'deskripsi_konversi' => '1 Lembar (2.1x5.4m)',
                        'is_default' => true,
                    ],
                    [
                        'unit_name' => 'M2',
                        'conversion_factor' => 11.34, // 2.1 x 5.4
                        'deskripsi_konversi' => '1 Lembar = 11.34 M²',
                        'is_default' => false,
                    ],
                    [
                        'unit_name' => 'ROLL',
                        'conversion_factor' => 1,
                        'deskripsi_konversi' => '1 Roll = 1 Lembar',
                        'is_default' => false,
                    ],
                ];
                break;

            case 'M3':
                // Estimate weight for concrete products (K250 ≈ 2400 kg/m³, K300 ≈ 2450 kg/m³)
                $density = (strpos($barang->nama_barang, 'K300') !== false) ? 2450 : 2400;

                $units = [
                    [
                        'unit_name' => 'M3',
                        'conversion_factor' => 1,
                        'deskripsi_konversi' => '1 Meter Kubik',
                        'is_default' => true,
                    ],
                    [
                        'unit_name' => 'KG',
                        'conversion_factor' => $density,
                        'deskripsi_konversi' => "1 M³ ≈ {$density} Kg",
                        'is_default' => false,
                    ],
                    [
                        'unit_name' => 'TON',
                        'conversion_factor' => $density / 1000,
                        'deskripsi_konversi' => "1 M³ ≈ " . ($density/1000) . " Ton",
                        'is_default' => false,
                    ],
                ];
                break;

            default:
                // Default units for other cases
                $units = [
                    [
                        'unit_name' => strtoupper($primaryUnit),
                        'conversion_factor' => 1,
                        'deskripsi_konversi' => "1 {$primaryUnit}",
                        'is_default' => true,
                    ],
                ];
                break;
        }

        // Insert units for this barang
        foreach ($units as $unit) {
            ItemUnit::create([
                'barang_id' => $barang->id,
                'unit_name' => $unit['unit_name'],
                'conversion_factor' => $unit['conversion_factor'],
                'deskripsi_konversi' => $unit['deskripsi_konversi'],
                'is_default' => $unit['is_default'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Penambahan Barang Aftercraft dan Unit Satuan berhasil: ' . $barang->nama_barang);
    }
}
