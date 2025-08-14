<?php

namespace App\Http\Controllers;

use App\Models\ItemUnit;
use App\Models\Projects;
use App\Models\SiteRequest;
use Illuminate\Http\Request;
use App\Models\SupplierBarang;
use App\Models\SiteRequestDetails;
use App\Models\PurchaseRequisition;

class PurchaseRequisitionController extends Controller
{
    public function index($kode_proyek)
{
    $project = Projects::where('kode_project', $kode_proyek)->firstOrFail();
    $siteRequest = SiteRequest::where('project_id', $project->id)->firstOrFail();

    // Ambil Purchase Requisition untuk project ini
    $purchaseRequisition = PurchaseRequisition::where('project_id', $project->id)->first();
    $pr_id = $purchaseRequisition ? $purchaseRequisition->id : null;

    // Ambil site_id dari Purchase Requisition
    $siteId = SiteRequest::where('id', $siteRequest->id)->value('site_id');

    // 1. Get project requirements
    $details = SiteRequestDetails::with('unit')
        ->where('site_request_id', $siteRequest->id)
        ->get();
    $barangIds = $details->pluck('barang_id')->unique();

    // 2. Prepare needs data with proper units
    $needs = $this->prepareNeedsData($details);

    // 3. Get all required units and suppliers
    $itemUnits = ItemUnit::whereIn('barang_id', $barangIds)->get()->keyBy('id');
    $suppliers = SupplierBarang::with(['barang', 'supplier', 'minOrderUnit'])
        ->whereIn('barang_id', $barangIds)
        ->get()
        ->groupBy('barang_id');

    // 4. WP criteria weights
    $weights = [
        'harga' => 0.4,
        'waktu' => 0.35,
        'min_order' => 0.15,
        'jarak' => 0.05,
        'diskon' => 0.05
    ];

    // 5. Process each item
    $results = [];
    foreach ($needs as $barangId => $need) {
        if (!isset($suppliers[$barangId])) {
            continue;
        }

        $supplierData = $this->calculateSupplierScores(
            $suppliers[$barangId],
            $need,
            $itemUnits,
            $weights
        );

        if (!empty($supplierData)) {
            $results[$barangId] = [
                'nama_barang' => $suppliers[$barangId]->first()->barang->nama_barang,
                'kebutuhan' => $need,
                'suppliers' => $supplierData,
                'best_supplier' => $this->selectBestSupplier($supplierData)
            ];
        }
    }

    return view('BARANG.SiteRequest.PR.pr_approval', [
        'results' => $results,
        'project' => $project,
        'siteRequest' => $siteRequest,
        'purchaseRequisition' => $purchaseRequisition,
        'pr_id' => $pr_id,
        'siteId' => $siteId
    ]);
}


    // ============ HELPER METHODS ============ //

    private function prepareNeedsData($details)
    {
        $needs = [];
        foreach ($details as $detail) {
            $needs[$detail->barang_id] = [
                'jumlah' => $detail->jumlah,
                'unit_id' => $detail->satuan_id,
                'unit_name' => $detail->unit->unit_name ?? 'Unknown',
                'unit_data' => $detail->unit ?? null
            ];
        }
        return $needs;
    }

    private function calculateSupplierScores($suppliers, $need, $itemUnits, $weights)
{
    $supplierData = [];

    // Kumpulkan data semua supplier dulu
    $allSupplierData = [];

    foreach ($suppliers as $supplier) {
        // Verify both units exist
        if (!$this->validateUnits($need['unit_id'], $supplier->satuan_kuantitas_minimum, $itemUnits)) {
            continue;
        }

        // Get both units data
        $requestUnit = $itemUnits[$need['unit_id']] ?? null;
        $supplierUnit = $itemUnits[$supplier->satuan_kuantitas_minimum] ?? null;

        // 1. Calculate conversion
        $conversion = $this->calculateUnitConversion(
            $requestUnit,
            $supplierUnit
        );

        // 2. Calculate purchase quantity
        $purchaseData = $this->calculatePurchaseData(
            $need['jumlah'],
            $supplier->kuantitas_minimum,
            $conversion['ratio']
        );

        // 3. Calculate costs
        $costData = $this->calculateCostData(
            $purchaseData['jumlah_dibeli'],      // jumlah yang dibeli dalam satuan supplier
            $supplier->harga,                    // harga per unit supplier
            $conversion['inverse_ratio'],        // rasio supplier → request
            $need['jumlah']                       // jumlah kebutuhan dalam unit request
        );

        $allSupplierData[$supplier->supplier_id] = [
            'supplier_id' => $supplier->supplier_id,
            'nama_supplier' => $supplier->supplier->nama_supplier,
            'harga_satuan' => $supplier->harga,
            'harga_konversi' => $costData['harga_konversi'],
            'min_order' => $supplier->kuantitas_minimum,
            'min_order_konversi' => $purchaseData['min_order_konversi'],
            'min_order_asli' => $purchaseData['min_order_asli'],
            'waktu_pengiriman' => $supplier->lama_waktu_pengiriman,
            'jarak' => $supplier->jarak_pengiriman,
            'diskon' => $supplier->diskon,
            'jumlah_dibeli' => $purchaseData['jumlah_dibeli'],
            'total_belanja' => $costData['total_belanja'],
            'konversi' => $conversion,
            'request_unit' => $requestUnit,
            'supplier_unit' => $supplierUnit
        ];
    }

    // Sekarang hitung WP score dengan normalisasi yang benar
    $supplierData = $this->calculateWPScores($allSupplierData, $weights);

    // Normalize and rank suppliers
    return $this->normalizeAndRankSuppliers($supplierData);
}

    private function validateUnits($requestUnitId, $supplierUnitId, $itemUnits)
    {
        if (!isset($itemUnits[$requestUnitId])) {
            logger()->error("Request unit not found", ['unit_id' => $requestUnitId]);
            return false;
        }

        if (!isset($itemUnits[$supplierUnitId])) {
            logger()->error("Supplier unit not found", ['unit_id' => $supplierUnitId]);
            return false;
        }

        return true;
    }

    private function calculatePurchaseData($jumlahRequest, $minOrderSupplier, $conversionRatio)
{
    // LOGIKA BENAR:
    // conversionRatio = supplier_factor / request_factor
    // Contoh: 1 BATANG = 11.8 KG, maka ratio = 11.8/1 = 11.8
    // Artinya: 1 unit request = 11.8 unit supplier

    // Konversi kebutuhan dari satuan request ke satuan supplier
    $kebutuhanDalamSatuanSupplier = $jumlahRequest * $conversionRatio;

    // Jumlah yang harus dibeli adalah MAX antara:
    // 1. Kebutuhan aktual (dalam satuan supplier)
    // 2. Minimum order supplier
    $jumlahDibeli = max($kebutuhanDalamSatuanSupplier, $minOrderSupplier);

    // Min order dalam satuan request (untuk ditampilkan)
    $minOrderKonversi = $minOrderSupplier / $conversionRatio;

    return [
        'jumlah_dibeli'        => $jumlahDibeli,        // dalam satuan supplier
        'min_order_konversi'   => $minOrderKonversi,    // min order dalam satuan request
        'min_order_asli'       => $minOrderSupplier,    // nilai asli dari DB
        'kebutuhan_konversi'   => $kebutuhanDalamSatuanSupplier // kebutuhan dalam satuan supplier
    ];
}

// Juga perlu update calculateCostData untuk menggunakan jumlah yang tepat
private function calculateCostData($jumlahDibeli, $hargaSupplier, $conversionRatio, $jumlahKebutuhan)
{
    // Harga per unit dalam satuan request
    $hargaKonversi = $hargaSupplier / $conversionRatio;

    // Total belanja berdasarkan jumlah kebutuhan aktual
    $totalBelanja = $jumlahKebutuhan * $hargaKonversi;

    return [
        'harga_konversi' => $hargaKonversi,
        'total_belanja' => $totalBelanja
    ];
}

// Dan update fungsi calculateUnitConversion untuk lebih jelas
private function calculateUnitConversion($requestUnit, $supplierUnit)
{
    $requestFactor = $requestUnit->conversion_factor;
    $supplierFactor = $supplierUnit->conversion_factor;

    // Rasio konversi: berapa unit supplier per 1 unit request
    $conversionRatio = $supplierFactor / $requestFactor;

    return [
        'from_unit' => $requestUnit->unit_name,
        'to_unit' => $supplierUnit->unit_name,
        'ratio' => $conversionRatio, // 1 request unit = X supplier unit
        'inverse_ratio' => $requestFactor / $supplierFactor, // 1 supplier unit = Y request unit
        'request_factor' => $requestFactor,
        'supplier_factor' => $supplierFactor
    ];
}


  private function calculateWPScores($supplierData, $weights)
{
    if (empty($supplierData)) {
        return [];
    }

    // Ambil nilai min/max untuk normalisasi
    $hargaValues = array_column($supplierData, 'harga_konversi');
    $waktuValues = array_column($supplierData, 'waktu_pengiriman');
    $minOrderValues = array_column($supplierData, 'min_order_konversi');
    $jarakValues = array_column($supplierData, 'jarak');
    $diskonValues = array_column($supplierData, 'diskon');

    $maxHarga = max($hargaValues);
    $minHarga = min($hargaValues);
    $maxWaktu = max($waktuValues);
    $minWaktu = min($waktuValues);
    $maxMinOrder = max($minOrderValues);
    $minMinOrder = min($minOrderValues);
    $maxJarak = max($jarakValues);
    $minJarak = min($jarakValues);
    $maxDiskon = max($diskonValues);
    $minDiskon = min($diskonValues);

    foreach ($supplierData as &$data) {
        // Normalisasi (0-1 range) - handle case ketika min = max
        $normHarga = ($maxHarga > $minHarga) ?
            1 - (($data['harga_konversi'] - $minHarga) / ($maxHarga - $minHarga)) : 0.5;

        $normWaktu = ($maxWaktu > $minWaktu) ?
            1 - (($data['waktu_pengiriman'] - $minWaktu) / ($maxWaktu - $minWaktu)) : 0.5;

        $normMinOrder = ($maxMinOrder > $minMinOrder) ?
            1 - (($data['min_order_konversi'] - $minMinOrder) / ($maxMinOrder - $minMinOrder)) : 0.5;

        $normJarak = ($maxJarak > $minJarak) ?
            1 - (($data['jarak'] - $minJarak) / ($maxJarak - $minJarak)) : 0.5;

        $normDiskon = ($maxDiskon > $minDiskon) ?
            ($data['diskon'] - $minDiskon) / ($maxDiskon - $minDiskon) : 0.5;

        // Pastikan nilai tidak 0 untuk pangkat
        $normHarga = max($normHarga, 0.001);
        $normWaktu = max($normWaktu, 0.001);
        $normMinOrder = max($normMinOrder, 0.001);
        $normJarak = max($normJarak, 0.001);
        $normDiskon = max($normDiskon, 0.001);

        // Calculate weighted product score
        $wpScore = (
            pow($normHarga, $weights['harga']) *
            pow($normWaktu, $weights['waktu']) *
            pow($normMinOrder, $weights['min_order']) *
            pow($normJarak, $weights['jarak']) *
            pow($normDiskon, $weights['diskon'])
        );

        $data['wp_score'] = $wpScore;

        // Debug info
        $data['debug'] = [
            'norm_harga' => $normHarga,
            'norm_waktu' => $normWaktu,
            'norm_min_order' => $normMinOrder,
            'norm_jarak' => $normJarak,
            'norm_diskon' => $normDiskon
        ];
    }

    return $supplierData;
}

    private function normalizeAndRankSuppliers($supplierData)
    {
        $totalScore = array_sum(array_column($supplierData, 'wp_score'));

        foreach ($supplierData as &$data) {
            $data['wp_normalized'] = $totalScore > 0 ? $data['wp_score'] / $totalScore : 0;
        }

        uasort($supplierData, function ($a, $b) {
            return $b['wp_normalized'] <=> $a['wp_normalized'];
        });

        return $supplierData;
    }

    private function selectBestSupplier($supplierData)
    {
        return collect($supplierData)
            ->sortByDesc('wp_normalized')
            ->first();
    }
}
