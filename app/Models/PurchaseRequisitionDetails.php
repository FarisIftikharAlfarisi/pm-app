<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseRequisitionDetails extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'purchase_requisition_details';

    protected $fillable = [
        'pr_id',
        'barang_id',
        'jumlah',
        'satuan_id',
        'approved_by_accounting',
        'accounting_id',
        'accounting_approval_date',
        'keterangan',
    ];

    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'pr_id');
    }
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
    public function projectLeader()
    {
        return $this->belongsTo(User::class, 'project_leader_id');
    }
    public function accounting()
    {
        return $this->belongsTo(User::class, 'accounting_id');
    }

    public function satuan()
    {
        return $this->belongsTo(ItemUnit::class, 'satuan_id');
    }
}
