<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;


class Kelas extends Model
{
    use SoftDeletes;

    protected $table = 'kelas';

    protected $dates = [
        'created_at',
        'update_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nama_kelas',
    ];

    public function siswa()
    {
        return $this->hasMany('App\Models\Siswa', 'kelas_id');
    }

    public function pembelajaran()
    {
        return $this->hasMany(Pembelajaran::class, 'kelas_id');
    }

    public function waliKelas()
    {
        return $this->hasMany(WaliKelas::class, 'kelas_id');
    }
}
