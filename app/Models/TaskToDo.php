<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TaskToDo
 * @package App\Models
 *
 * Model untuk tabel task_to_do
 * Penggunaan controller akan dipengaruhi oleh TaskController
 */

class TaskToDo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'task_to_dos';

    protected $fillable = [
        'site_id',
        'project_id',
        'kode_task',
        'nama_task',
        'deskripsi',
        'status',
        'parent_id',
        'priority_level',
        'start_date',
        'end_date',
        'completion_duration',
        'estimated_hours',
        'actual_hours',
        'is_locked',
        'accounting_approve',
        'accounting_id'
    ];

    // Relasi ke tabel User (Creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    // Relasi ke tabel Site
    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }

    // Relasi ke tabel Projects
    public function project()
    {
        return $this->belongsTo(Projects::class, 'project_id', 'id');
    }

    // Relasi ke tabel User (Accounting)
    public function accounting()
    {
        return $this->belongsTo(User::class, 'accounting_id', 'id');
    }

    // Relasi ke tabel TaskToDo untuk self-referencing
    public function parentTask()
    {
        return $this->belongsTo(TaskToDo::class, 'parent_id', 'id');
    }

    // relasi ke task to do ke kebutuhan barang wbs
    public function kebutuhanBarangWbs()
    {
        return $this->hasMany(KebutuhanBarangWbs::class, 'task_to_do_id', 'id');
    }


}
