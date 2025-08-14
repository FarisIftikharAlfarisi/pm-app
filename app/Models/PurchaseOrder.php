<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'kode_purchase_order',
        'nama_purchase_order',
        'site_request_id',
        'project_id',
        'site_id',
        'supplier_id',
        'approval_accounting_status',
        'accounting_comment',
        'accounting_id',
        'estimasi_sampai',
        'tanggal_purchase_order',
        'accounting_approval_date',
    ];

    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function accounting()
    {
        return $this->belongsTo(User::class, 'accounting_id');
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetails::class);
    }

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetails::class);
    }

/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Supplier for this PurchaseOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
/*******  6de07109-b449-45bf-a82f-ecf65610cbd5  *******/
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }


    public function siteRequest()
    {
        return $this->belongsTo(SiteRequest::class);
    }

    public function goodsReceipts()
{
    return $this->hasMany(GoodsReceipts::class, 'purchase_order_id');
}

}
