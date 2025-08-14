<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'sites'; // Nama tabel

    protected $fillable = [
        'Kode_Site',
        'nama_site',
        'jenis_site',
        'alamat',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'latitude',
        'longitude'
    ];

    // Relasi ke UserDetail (One-to-Many)
    public function userDetails()
    {
        return $this->hasMany(UserDetails::class, 'Kode_Site', 'id');
    }

    // Relasi ke Projects (One-to-Many)
    public function projects()
    {
        return $this->hasMany(Projects::class, 'site_id', 'id');
    }

    public function businessDocuments()
    {
        return $this->hasMany(BusinessDocuments::class, 'site_id', 'id');
    }
}
