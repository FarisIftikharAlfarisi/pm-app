<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequisition extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase_requisitions';

    protected $fillable = [
        'kode_requisition',
        'project_id',
        'nama_requisition',
        'jenis_requisition',
        'request_id',
        'site_id',
        'approval_accounting',
        'accounting_id',
        'accounting_approval_date'
    ];
    public function siteRequest()
    {
        return $this->belongsTo(SiteRequest::class, 'request_id');
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

    public function purchaseRequisitionDetails()
    {
        return $this->hasMany(PurchaseRequisitionDetails::class);
    }
}
