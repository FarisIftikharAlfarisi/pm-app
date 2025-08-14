<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReportTask extends Model
{
    use HasFactory;

    protected $table = 'daily_report_tasks';

    protected $fillable = [
       'tugas_id',
       'sub_tugas_id',
       'status_pengerjaan',
       'keterangan',
       'kode_site',
       'foto'
    ];

    // Relasi ke tabel SiteTaskJob (Task yang dilaporkan)
    public function task()
    {
        return $this->belongsTo(SiteTaskJob::class, 'Kode_Task', 'id');
    }

    // Relasi ke tabel Sites (Lokasi pekerjaan)
    public function site()
    {
        return $this->belongsTo(Site::class, 'Kode_Site', 'id');
    }

    // Relasi ke User (Reporter = Orang yang membuat laporan)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'Reporter', 'id');
    }
}
