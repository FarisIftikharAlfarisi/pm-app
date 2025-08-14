<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Supplier
 *
 * akan digunakan untuk menyimpan data supplier
 * dipengaruhi oleh SupplierController
 */

class Supplier extends Model
{
    use SoftDeletes;

    protected $table = 'suppliers';

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'email',
        'contact',
        'nama_contact_person',
        'no_rekening',
        'bank',
        'atas_nama_bank',
        'creator_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
