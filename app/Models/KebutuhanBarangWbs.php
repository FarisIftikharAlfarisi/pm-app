<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KebutuhanBarangWbs extends Model
{

    use SoftDeletes, HasFactory;

    protected $table = 'kebutuhan_barang_wbs';

    protected $fillable = [
        'task_to_do_id',
        'barang_id',
        'satuan_id',
        'jumlah',
    ];

    // relasi ke barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function taskToDo()
    {
        return $this->belongsTo(TaskToDo::class);
    }

    // relasi ke satuan
    public function satuan()
    {
        return $this->belongsTo(ItemUnit::class, 'satuan_id');
    }

    // relasi ke kreator
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // relasu ke project
    public function project()
    {
        return $this->belongsTo(Projects::class, 'project_id');
    }

    // relasi ke task to do
    public function task()
    {
        return $this->belongsTo(TaskToDo::class, 'task_to_do_id');
    }




}
