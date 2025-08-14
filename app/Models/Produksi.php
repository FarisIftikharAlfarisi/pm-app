<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produksi extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'produksis';

    protected $fillable = [

        'kode_produksi',
        'nama_produksi',
        'barang_id',
        'project_id',
        'site_id',
        'purchase_order_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',


    ];

    // relasi

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function project()
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

}
