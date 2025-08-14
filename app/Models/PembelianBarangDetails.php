<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembelianBarangDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembelian_barang_details'; // Nama tabel

    protected $fillable = [
        'ID_Pembelian',
        'Kode_Pembelian',
        'ID_Barang',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'status',
        'Kode_Karyawan'
    ];

    // Relasi ke PembelianBarang (Many-to-One)
    public function pembelianBarang()
    {
        return $this->belongsTo(PembelianBarang::class, 'ID_Pembelian', 'id');
    }

    // Relasi ke Barang (Many-to-One)
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'ID_Barang', 'id');
    }

    // Relasi ke User/Karyawan (Many-to-One)
    public function karyawan()
    {
        return $this->belongsTo(User::class, 'Kode_Karyawan', 'id');
    }
}
