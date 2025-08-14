<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\SupplierBarang;
use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        // Buat suppliers
        $suppliers = [
            [
                'kode_supplier' => 'SUP-001',
                'nama_supplier' => 'PT Krakatau Steel Trading',
                'alamat' => 'Jl. Industri Raya No. 123, Cilegon, Banten',
                'email' => 'sales@krakatau-steel.co.id',
                'contact' => '0254-123456',
                'nama_contact_person' => 'Budi Santoso',
                'no_rekening' => '1234567890',
                'bank' => 'BCA',
                'atas_nama_bank' => 'PT Krakatau Steel Trading',
                'creator_id' => 1,
            ],
            [
                'kode_supplier' => 'SUP-002',
                'nama_supplier' => 'CV Bangunan Jaya Abadi',
                'alamat' => 'Jl. Raya Bogor KM 25, Depok, Jawa Barat',
                'email' => 'purchasing@bangunan-jaya.com',
                'contact' => '021-87654321',
                'nama_contact_person' => 'Siti Rahayu',
                'no_rekening' => '9876543210',
                'bank' => 'Mandiri',
                'atas_nama_bank' => 'CV Bangunan Jaya Abadi',
                'creator_id' => 1,
            ],
            [
                'kode_supplier' => 'SUP-003',
                'nama_supplier' => 'UD Sumber Makmur',
                'alamat' => 'Jl. Veteran No. 45, Bandung, Jawa Barat',
                'email' => 'admin@sumbermakmur.id',
                'contact' => '022-87654321',
                'nama_contact_person' => 'Ahmad Fauzi',
                'no_rekening' => '5678901234',
                'bank' => 'BRI',
                'atas_nama_bank' => 'UD Sumber Makmur',
                'creator_id' => 1,
            ],
            [
                'kode_supplier' => 'SUP-004',
                'nama_supplier' => 'PT Multi Guna Konstruksi',
                'alamat' => 'Jl. Gatot Subroto No. 88, Jakarta Selatan',
                'email' => 'sales@multiguna.co.id',
                'contact' => '021-55443322',
                'nama_contact_person' => 'Indra Wijaya',
                'no_rekening' => '3456789012',
                'bank' => 'BNI',
                'atas_nama_bank' => 'PT Multi Guna Konstruksi',
                'creator_id' => 1,
            ],
            [
                'kode_supplier' => 'SUP-005',
                'nama_supplier' => 'Toko Besi Berkah',
                'alamat' => 'Jl. Pasar Besi No. 12, Tangerang, Banten',
                'email' => 'tokoberkah@gmail.com',
                'contact' => '021-99887766',
                'nama_contact_person' => 'Mahmud',
                'no_rekening' => '7890123456',
                'bank' => 'BCA',
                'atas_nama_bank' => 'Mahmud',
                'creator_id' => 1,
            ],
        ];

        $createdSuppliers = [];
        foreach ($suppliers as $supplierData) {
            $supplier = Supplier::create($supplierData);
            $createdSuppliers[] = $supplier;
        }

        // Ambil semua barang yang sudah dibuat
        $barangs = Barang::whereIn('kode_barang', [
            'BESI-D14', 'BESI-D13', 'BESI-D12', 'BESI-D11', 'BESI-D10',
            'SEMEN-PORTLAND', 'BATU-SPLIT', 'WELD-ROD', 'PASIR', 'SOLAR',
            'BENSIN', 'KABEL-LISTRIK', 'RANGKA-ATAP', 'WIREMESH',
            'WATERPROOFING', 'BATA-MERAH', 'GENTENG'
        ])->get();

        // Helper untuk random harga berdasarkan jenis barang
        $getPriceRange = function($kode_barang) {
            switch($kode_barang) {
                case 'BESI-D14':
                    return ['harga' => rand(17000, 20000), 'harga_beli' => rand(15000, 18000)]; // per KG
                case 'BESI-D13':
                    return ['harga' => rand(16500, 19500), 'harga_beli' => rand(14500, 17500)]; // per KG
                case 'BESI-D12':
                    return ['harga' => rand(16000, 19000), 'harga_beli' => rand(14000, 17000)]; // per KG
                case 'BESI-D11':
                    return ['harga' => rand(15500, 18500), 'harga_beli' => rand(13500, 16500)]; // per KG
                case 'BESI-D10':
                    return ['harga' => rand(15000, 18000), 'harga_beli' => rand(13000, 16000)]; // per KG
                case 'SEMEN-PORTLAND':
                    return ['harga' => rand(70000, 80000), 'harga_beli' => rand(65000, 75000)]; // per SAK
                case 'BATU-SPLIT':
                    return ['harga' => rand(450000, 550000), 'harga_beli' => rand(400000, 500000)]; // per TRUK
                case 'WELD-ROD':
                    return ['harga' => rand(50000, 80000), 'harga_beli' => rand(45000, 75000)]; // per BOX
                case 'PASIR':
                    return ['harga' => rand(350000, 450000), 'harga_beli' => rand(300000, 400000)]; // per M3
                case 'SOLAR':
                    return ['harga' => rand(12000, 15000), 'harga_beli' => rand(10000, 13000)]; // per LITER
                case 'BENSIN':
                    return ['harga' => rand(10000, 12000), 'harga_beli' => rand(9000, 11000)]; // per LITER
                case 'KABEL-LISTRIK':
                    return ['harga' => rand(50000, 100000), 'harga_beli' => rand(45000, 90000)]; // per PCS
                case 'RANGKA-ATAP':
                    return ['harga' => rand(5000000, 8000000), 'harga_beli' => rand(4500000, 7500000)]; // per SET
                case 'WIREMESH':
                    return ['harga' => rand(150000, 250000), 'harga_beli' => rand(130000, 220000)]; // per LEMBAR
                case 'WATERPROOFING':
                    return ['harga' => rand(200000, 300000), 'harga_beli' => rand(180000, 270000)]; // per LEMBAR
                case 'BATA-MERAH':
                    return ['harga' => rand(800000, 1200000), 'harga_beli' => rand(700000, 1100000)]; // per M3
                case 'GENTENG':
                    return ['harga' => rand(1200000, 1800000), 'harga_beli' => rand(1000000, 1600000)]; // per M3
                default:
                    return ['harga' => rand(50000, 100000), 'harga_beli' => rand(45000, 90000)];
            }
        };

        // Helper untuk minimum order berdasarkan jenis barang dengan satuan_id yang tepat
        $getMinOrderData = function($kode_barang) {
            switch($kode_barang) {
                case 'BESI-D14':
                    return [
                        'kuantitas' => rand(100, 500), // dalam KG
                        'satuan_id' => 2 // KG (barang_id 1)
                    ];
                case 'BESI-D13':
                    return [
                        'kuantitas' => rand(100, 500), // dalam KG
                        'satuan_id' => 5 // KG (barang_id 2)
                    ];
                case 'BESI-D12':
                    return [
                        'kuantitas' => rand(100, 500), // dalam KG
                        'satuan_id' => 8 // KG (barang_id 3)
                    ];
                case 'BESI-D11':
                    return [
                        'kuantitas' => rand(100, 500), // dalam KG
                        'satuan_id' => 11 // KG (barang_id 4)
                    ];
                case 'BESI-D10':
                    return [
                        'kuantitas' => rand(100, 500), // dalam KG
                        'satuan_id' => 14 // KG (barang_id 5)
                    ];
                case 'SEMEN-PORTLAND':
                    return [
                        'kuantitas' => rand(5, 20),
                        'satuan_id' => 16 // SAK (barang_id 6)
                    ];
                case 'BATU-SPLIT':
                    return [
                        'kuantitas' => 1,
                        'satuan_id' => 18 // TRUK (barang_id 7)
                    ];
                case 'WELD-ROD':
                    return [
                        'kuantitas' => rand(1, 5),
                        'satuan_id' => 20 // BOX (barang_id 8)
                    ];
                case 'PASIR':
                    return [
                        'kuantitas' => rand(2, 10),
                        'satuan_id' => 25 // M3 (barang_id 12)
                    ];
                case 'SOLAR':
                    return [
                        'kuantitas' => rand(20, 100),
                        'satuan_id' => 27 // LITER (barang_id 13)
                    ];
                case 'BENSIN':
                    return [
                        'kuantitas' => rand(20, 100),
                        'satuan_id' => 28 // LITER (barang_id 14)
                    ];
                case 'KABEL-LISTRIK':
                    return [
                        'kuantitas' => rand(10, 100),
                        'satuan_id' => 29 // PCS (barang_id 15)
                    ];
                case 'RANGKA-ATAP':
                    return [
                        'kuantitas' => 1,
                        'satuan_id' => 32 // SET (barang_id 16)
                    ];
                case 'WIREMESH':
                    return [
                        'kuantitas' => rand(5, 20),
                        'satuan_id' => 34 // LEMBAR (barang_id 17)
                    ];
                case 'WATERPROOFING':
                    return [
                        'kuantitas' => rand(5, 20),
                        'satuan_id' => 37 // LEMBAR (barang_id 18)
                    ];
                case 'BATA-MERAH':
                    return [
                        'kuantitas' => rand(2, 10),
                        'satuan_id' => 40 // M3 (barang_id 19)
                    ];
                case 'GENTENG':
                    return [
                        'kuantitas' => rand(2, 8),
                        'satuan_id' => 43 // M3 (barang_id 20)
                    ];
                default:
                    return [
                        'kuantitas' => 1,
                        'satuan_id' => 29 // PCS as default
                    ];
            }
        };

        // Helper untuk data pengiriman berdasarkan lokasi supplier
        $getDeliveryData = function($supplierName) {
            $deliveryTimes = [
                'PT Krakatau Steel Trading' => ['waktu' => rand(2, 4), 'satuan' => 'HARI', 'jarak' => rand(80, 120)],
                'CV Bangunan Jaya Abadi' => ['waktu' => rand(1, 3), 'satuan' => 'HARI', 'jarak' => rand(30, 50)],
                'UD Sumber Makmur' => ['waktu' => rand(1, 2), 'satuan' => 'HARI', 'jarak' => rand(20, 40)],
                'PT Multi Guna Konstruksi' => ['waktu' => rand(1, 3), 'satuan' => 'HARI', 'jarak' => rand(25, 45)],
                'Toko Besi Berkah' => ['waktu' => rand(2, 5), 'satuan' => 'HARI', 'jarak' => rand(50, 80)],
            ];

            return $deliveryTimes[$supplierName] ?? ['waktu' => rand(1, 3), 'satuan' => 'HARI', 'jarak' => rand(20, 60)];
        };

        // Data supplier-barang dengan aturan:
        // 1. Setiap barang minimal ada 3 supplier
        // 2. Setiap supplier minimal jual 4 barang
        $supplierBarangData = [];

        // Pastikan setiap barang punya minimal 3 supplier
        foreach ($barangs as $barang) {
            // Ambil 3-4 supplier random untuk setiap barang
            $selectedSuppliers = $createdSuppliers;
            shuffle($selectedSuppliers);
            $suppliersForThisItem = array_slice($selectedSuppliers, 0, rand(3, 4));

            foreach ($suppliersForThisItem as $supplier) {
                $priceData = $getPriceRange($barang->kode_barang);
                $minOrderData = $getMinOrderData($barang->kode_barang);
                $deliveryData = $getDeliveryData($supplier->nama_supplier);

                $supplierBarangData[] = [
                    'supplier_id' => $supplier->id,
                    'barang_id' => $barang->id,
                    'lama_waktu_pengiriman' => $deliveryData['waktu'],
                    'satuan_lama_waktu_pengiriman' => $deliveryData['satuan'],
                    'kuantitas_minimum' => $minOrderData['kuantitas'],
                    'satuan_kuantitas_minimum' => $minOrderData['satuan_id'], // Menggunakan ID dari item_units
                    'jarak_pengiriman' => $deliveryData['jarak'],
                    'satuan_jarak_pengiriman' => 'KM',
                    'harga' => $priceData['harga'],
                    'harga_beli' => $priceData['harga_beli'],
                    'diskon' => rand(0, 15), // Diskon 0-15%
                    'creator_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Pastikan setiap supplier minimal jual 4 barang
        foreach ($createdSuppliers as $supplier) {
            $supplierItems = collect($supplierBarangData)->where('supplier_id', $supplier->id);

            if ($supplierItems->count() < 4) {
                // Tambahkan barang random hingga minimal 4
                $missingCount = 4 - $supplierItems->count();
                $availableBarangs = $barangs->whereNotIn('id', $supplierItems->pluck('barang_id'));

                if ($availableBarangs->count() > 0) {
                    $availableBarangs = $availableBarangs->shuffle()->take($missingCount);

                    foreach ($availableBarangs as $barang) {
                        $priceData = $getPriceRange($barang->kode_barang);
                        $minOrderData = $getMinOrderData($barang->kode_barang);
                        $deliveryData = $getDeliveryData($supplier->nama_supplier);

                        $supplierBarangData[] = [
                            'supplier_id' => $supplier->id,
                            'barang_id' => $barang->id,
                            'lama_waktu_pengiriman' => $deliveryData['waktu'],
                            'satuan_lama_waktu_pengiriman' => $deliveryData['satuan'],
                            'kuantitas_minimum' => $minOrderData['kuantitas'],
                            'satuan_kuantitas_minimum' => $minOrderData['satuan_id'], // Menggunakan ID dari item_units
                            'jarak_pengiriman' => $deliveryData['jarak'],
                            'satuan_jarak_pengiriman' => 'KM',
                            'harga' => $priceData['harga'],
                            'harga_beli' => $priceData['harga_beli'],
                            'diskon' => rand(0, 15),
                            'creator_id' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Insert semua data supplier-barang
        SupplierBarang::insert($supplierBarangData);

        $this->command->info('Supplier seeder berhasil!');
        $this->command->info('- Dibuat 5 supplier dengan data lengkap (rekening bank, contact person)');
        $this->command->info('- Setiap barang minimal ada 3 supplier');
        $this->command->info('- Setiap supplier minimal jual 4 barang');
        $this->command->info('- Total relasi supplier-barang: ' . count($supplierBarangData));
        $this->command->info('- Harga jual dan harga beli dengan margin realistis');
        $this->command->info('- Data pengiriman lengkap dengan jarak dan waktu');
        $this->command->info('- Satuan kuantitas minimum menggunakan foreign key ke item_units table');

        // Info mapping satuan
        $this->command->info('');
        $this->command->info('MAPPING SATUAN YANG DIGUNAKAN:');
        $this->command->info('- BESI D14: satuan_id 2 (KG)');
        $this->command->info('- BESI D13: satuan_id 5 (KG)');
        $this->command->info('- BESI D12: satuan_id 8 (KG)');
        $this->command->info('- BESI D11: satuan_id 11 (KG)');
        $this->command->info('- BESI D10: satuan_id 14 (KG)');
        $this->command->info('- SEMEN: satuan_id 16 (SAK)');
        $this->command->info('- BATU SPLIT: satuan_id 18 (TRUK)');
        $this->command->info('- WELDING ROD: satuan_id 20 (BOX)');
        $this->command->info('- PASIR: satuan_id 25 (M3)');
        $this->command->info('- WIREMESH: satuan_id 34 (LEMBAR)');
        $this->command->info('- RANGKA ATAP: satuan_id 32 (SET)');
        $this->command->info('- Dan lainnya sesuai default unit masing-masing barang');
    }
}
