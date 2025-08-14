<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceipts extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'goods_receipts';

    protected $fillable = [
        'kode_goods_receipt',
        'purchase_order_id',
        'purchase_requisition_id',
        'site_request_id',
        'site_id',
        'approval_project_leader',
        'approval_accounting',
        'project_leader_comment',
        'accounting_comment',
        'project_leader_id',
        'accounting_id',
        'project_leader_approval_date',
        'accounting_approval_date'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function siteRequest()
    {
        return $this->belongsTo(SiteRequest::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function projectLeader()
    {
        return $this->belongsTo(User::class, 'project_leader_id');
    }

    public function accounting()
    {
        return $this->belongsTo(User::class, 'accounting_id');
    }

    public function details()
{
    return $this->hasMany(GoodsReceiptsDetails::class, 'goods_receipt_id');
}
}
