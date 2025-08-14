<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budgeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'Kode_Budgeting',
        'Nama_Budgeting',
        'Jenis_Budgeting',
        'Total_Anggaran',
        'Sisa_Anggaran',
        'Keterangan',
        'Tanggal',
        'Kode_Karyawan',
    ];

    // Relasi ke tabel alokasi_pemasukan
    // public function alokasiPemasukan()
    // {
    //     return $this->hasMany(AlokasiPemasukan::class, 'budgeting_id');
    // }

    // Relasi ke tabel users (karyawan)
    public function karyawan()
    {
        return $this->belongsTo(User::class, 'Kode_Karyawan');
    }
}
