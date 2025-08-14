<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HitungWPVendor extends Model
{
    protected $table = 'hitung_w_p_vendors';

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
        'nilai_s',
        'nilai_v'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
