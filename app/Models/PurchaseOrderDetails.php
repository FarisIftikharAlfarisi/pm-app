<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderDetails extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'purchase_order_details';

    protected $fillable = [
        'purchase_order_id',
        'barang_id',
        'jumlah',
        'satuan',
        'harga',
];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
