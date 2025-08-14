<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'site_requests';

    protected $fillable = [
        'kode_request',
        'nama_request',
        'jenis_request',
        'approval_project_leader_status',
        'approval_accounting_status',
        'keterangan',
        'site_id',
        'project_id',
        'approval_accounting_id',
        'accounting_approval_date',
        'accounting_comment',
        'creator_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function details()
    {
        return $this->hasMany(SiteRequestDetails::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function projectLeader()
    {
        return $this->belongsTo(User::class, 'approval_project_leader_id');
    }
    public function accounting()
    {
        return $this->belongsTo(User::class, 'approval_accounting_id');
    }


    public function project()
    {
        return $this->belongsTo(Projects::class);
    }

}
