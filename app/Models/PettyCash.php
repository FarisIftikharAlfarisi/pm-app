<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyCash extends Model
{
    use HasFactory;

    protected $table = 'petty_cashes';

    protected $fillable = [
        'Kode_Petty_Cash',
        'jumlah',
        'keterangan',
        'Kode_Karyawan'
    ];

    // Relasi ke User (Karyawan)
    public function karyawan()
    {
        return $this->belongsTo(User::class, 'Kode_Karyawan', 'id');
    }
}
