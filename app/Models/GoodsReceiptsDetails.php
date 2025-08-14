<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsReceiptsDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'goods_receipts_details';

    protected $fillable = [
        'goods_receipt_id',
        'barang_id',
        'jumlah',
        'approved_by_project_leader',
        'approved_by_accounting',
        'project_leader_comment',
        'accounting_comment',
        'project_leader_id',
        'accounting_id',
        'project_leader_approval_date',
        'accounting_approval_date'
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipts::class, 'goods_receipt_id');
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
}
