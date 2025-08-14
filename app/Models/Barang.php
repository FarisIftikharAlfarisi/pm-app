<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

     protected $dates = ['deleted_at'];

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'merk',
        'keterangan',
        'is_visible',
        'foto_path',
        'creator_id'
    ];

    protected $casts = [
        'is_visible' => 'boolean'
    ];

    // Relasi dengan satuan
    public function satuan()
    {
        return $this->hasMany(ItemUnit::class, 'barang_id');
    }

    public function kebutuhanBarangWbs()
    {
        return $this->hasMany(KebutuhanBarangWbs::class, 'barang_id');
    }

    public function siteRequest()
    {
        return $this->hasMany(SiteRequestDetails::class, 'barang_id');
    }

    // Relasi dengan creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Scope untuk bahan baku
    public function scopeBahanBaku($query)
    {
        return $query->where('kategori', 'BAHAN_BAKU');
    }
}
