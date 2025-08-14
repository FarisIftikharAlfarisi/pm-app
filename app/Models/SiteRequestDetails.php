<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteRequestDetails extends Model
{

    use HasFactory, SoftDeletes;

    protected $table = 'site_request_details';

    protected $fillable = [
        'site_request_id',
        'barang_id',
        'satuan_id',
        'jumlah',
        'approval_accounting_status',
        'accounting_comment',
        'accounting_id',
        'accounting_approval_date',
        'keterangan',
    ];

    public function siteRequest()
    {
        return $this->belongsTo(SiteRequest::class, 'site_request_id');
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
    public function unit()
    {
        return $this->belongsTo(ItemUnit::class, 'satuan_id');
    }


}
