<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pertemuan extends Model
{
    use SoftDeletes;

    protected $table = 'pertemuan';

    protected $dates = [
        'created_at',
        'update_at',
        'deleted_at',
    ];

    protected $fillable = [
        'judul',
    ];

    public function pertemuanMateri()
    {
        return $this->hasMany(PertemuanMateri::class, 'pertemuan_id');
    }

    public function materi()
    {
        return $this->hasMany(PertemuanMateri::class, 'pertemuan_id');
    }

    public function pertemuanTugas()
    {
        return $this->hasMany(PertemuanTugas::class, 'pertemuan_id');
    }

    public function pertemuanKuis()
    {
        return $this->hasMany(PertemuanKuis::class, 'pertemuan_id');
    }

}
