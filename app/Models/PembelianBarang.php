<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembelianBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembelian_barangs';

    protected $fillable = [
        'kode_pembelian',
        'supplier',
        'total_belanja',
        'status_pembayaran',
        'tanggal_pembelian',
        'bukti_pembayaran',
        'approval_status',
        'approval_user_id',
        'approval_date',
        'approval_comment',
        'creator_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

     // Relasi ke PembelianBarangDetail (One-to-Many)
     public function pembelianDetails()
     {
         return $this->hasMany(PembelianBarangDetails::class, 'ID_Pembelian', 'id');
     }
}
