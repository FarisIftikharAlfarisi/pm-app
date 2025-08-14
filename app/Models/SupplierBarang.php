<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierBarang extends Model
{
    use SoftDeletes;

    protected $table = 'supplier_barangs';

    protected $fillable = [
        'supplier_id',
        'barang_id',
        'lama_waktu_pengiriman',
        'satuan_lama_waktu_pengiriman',
        'kuantitas_minimum',
        'satuan_kuantitas_minimum',
        'jarak_pengiriman',
        'satuan_jarak_pengiriman',
        'harga',
        'harga_beli',
        'diskon',
        'creator_id',
    ];

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi ke User (pembuat)
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // satuan
    public function satuan(){
        return $this->belongsTo(ItemUnit::class, 'satuan_kuantitas_minimum', 'id');
    }
    public function minOrderUnit(){
        return $this->belongsTo(ItemUnit::class, 'satuan_kuantitas_minimum', 'id');
    }


}
