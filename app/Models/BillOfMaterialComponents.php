<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterialComponents extends Model
{
    protected $table = 'bill_of_material_components';

    protected $fillable = [
        'bom_id',
        'bahan_baku_id',
        'quantity',
        'unit_of_measure',
        'toleransi_quantity',
        'waktu_produksi_per_unit',
        'creator_id',
    ];

    // Relasi ke BOM induk
    public function bom()
    {
        return $this->belongsTo(BillOfMaterialBarang::class, 'bom_id');
    }

    // Relasi ke barang bahan baku
    public function bahanBaku()
    {
        return $this->belongsTo(Barang::class, 'bahan_baku_id');
    }

    // Relasi ke user yang membuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
