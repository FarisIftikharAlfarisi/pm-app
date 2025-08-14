<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Projects extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_project',
        'nama_project',
        'site_id',
        'deskripsi',
        'anggaran',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_proyek',
        'jenis_proyek_lainnya',
        'penanggung_jawab',
        'kontak_penanggung_jawab',
        'klien',
        'kontak_klien',
        'catatan',
        'created_by'
    ];

    protected $casts = [
        'anggaran' => 'decimal:2',
    ];

    public function businessDocuments()
    {
        return $this->hasMany(BusinessDocuments::class, 'project_id'); // Spesifikasikan foreign key
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id', 'id'); // Spesifikasikan foreign key
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // =======================================
    // query untuk menambahkan WBS dan Dashboard (Dashboard Keuangan & Dashboard progress)
    //
    // fungsi hasdMoUDocument, hasTechnicalDrawing, hasWbs
    //
    public function hasMouDocument()
    {

        // digunakan untuk mengecek apakah project ini memiliki dokumen MOU

        return $this->businessDocuments()
            ->where('jenis_dokumen', 'Dokumen MOU')
            ->exists();
    }

    public function hasTechnicalDrawing()
    {
        // digunakan untuk mengecek apakah project ini memiliki dokumen Gambar Teknik (Gamtek)
        return $this->businessDocuments()
            ->where('jenis_dokumen', 'Gambar Teknik (Gamtek)')
            ->exists();
    }

    public function hasWbs()
    {
        return $this->wbsTasks()->exists();
    }

    public function wbsTasks()
    {
        // Mengembalikan relasi ke model TaskToDo = WBS
        return $this->hasMany(TaskToDo::class);
    }

    /***
     * Relasi Berikutnya
     *
     * Relasi Ke Barang custom yang ada di dalam project
     * */

    // public function barangCustom(){

    // }

}

