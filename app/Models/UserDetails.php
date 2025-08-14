<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDetails extends Model
{
    use HasFactory;

    protected $table = 'user_details'; // Nama tabel

    protected $fillable = [
        'ID_Pengguna',
        'nomor_telepon',
        'alamat',
        'jabatan',
        'Kode_Site',
        'Kategori_Pekerja',
        'foto'
    ];

    // Relasi ke User (Many-to-One)
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_Pengguna', 'id');
    }

    // Relasi ke Site (Many-to-One)
    public function site()
    {
        return $this->belongsTo(Site::class, 'Kode_Site', 'id');
    }
}
