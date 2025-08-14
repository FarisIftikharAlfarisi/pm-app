<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absensi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'absensis';

    protected $fillable = [
        'karyawan_id',
        'site_id',
        'latitude_masuk',
        'longitude_masuk',
        'latitude_pulang',
        'longitude_pulang',
        'jarak_absen_masuk',
        'jarak_absen_keluar',
        'absen_masuk',
        'absen_keluar',
        'jam_kerja',
        'status_kehadiran',
        'selfie_path',
        'selfie_path_pulang',
        'creator_id',
    ];

    // Relasi ke tabel Users (Karyawan)
    public function karyawan()
    {
        return $this->belongsTo(User::class, 'Kode_Karyawan', 'id');
    }

    // Relasi ke tabel Sites (Lokasi kerja)
    public function site()
    {
        return $this->belongsTo(Site::class, 'Kode_Site', 'id');
    }
}
