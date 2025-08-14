<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterialBarang extends Model
{
    protected $fillable = [
        'barang_id',
        'kode_bom',
        'nama_bom',
        'quantity',
        'unit_of_measure',
        'status',
        'creator_id',
        'catatan_produksi',
        'estimasi_waktu_produksi',
        'satuan_estimasi_waktu_produksi',
    ];

    // Relasi ke user (pembuat BOM)
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Relasi ke komponen-komponen BOM
    public function components()
    {
        return $this->hasMany(BillOfMaterialComponents::class, 'bom_id');
    }
}
