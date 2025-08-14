<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'unit_name',
        'conversion_factor',
        'deskripsi_konversi',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'conversion_factor' => 'decimal:2'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function kebutuhanWBS(){
        return $this->hasMany(KebutuhanBarangWbs::class, 'satuan_id', 'id');
    }
}
