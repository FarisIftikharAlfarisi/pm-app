<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteTaskJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'site_task_jobs';

    protected $fillable = [
        'tugas_id',
        'sub_tugas_id',
        'site_id',
        'PIC',
        'Start_Date',
        'Target_Date',
        'Finish_Date',
        'creator_id',
        'approval_status',
        'approval_date',
        'approval_note',
        'first_validator_id',
        'second_validator_id',
        'thrid_validator_id',
        'is_finished',
        'is_visible',
    ];

    // Relasi ke tabel Sites
    public function site()
    {
        return $this->belongsTo(Site::class, 'Kode_Site', 'id');
    }

    // Relasi ke User (PIC = Person in Charge)
    public function pic()
    {
        return $this->belongsTo(User::class, 'PIC', 'id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    public function first_validator()
    {
        return $this->belongsTo(User::class, 'first_validator_id', 'id');
    }
    public function second_validator()
    {
        return $this->belongsTo(User::class, 'second_validator_id', 'id');
    }
    public function thrid_validator()
    {
        return $this->belongsTo(User::class, 'thrid_validator_id', 'id');
    }

    // Relasi ke tabel TaskToDo
    public function taskToDo()
    {
        return $this->belongsTo(TaskToDo::class, 'tugas_id', 'id');
    }
    public function subTaskToDo()
    {
        return $this->belongsTo(SubTaskToDo::class, 'subtugas_id', 'id');
    }
}
