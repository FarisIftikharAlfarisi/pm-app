<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDocuments extends Model
{
    protected $table = 'business_documents'; // Nama tabel

    protected $fillable = [
        'kode_dokumen', 'project_id', 'site_id', 'nama_dokumen',
        'jenis_dokumen', 'file_path', 'user_id'
    ];

   public function project()
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
