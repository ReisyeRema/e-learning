<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class TahunAjaran extends Model
{
    use SoftDeletes;

    protected $table = 'tahun_ajaran';

    protected $dates = [
        'created_at',
        'update_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nama_tahun',
    ];

    public function siswa()
    {
        return $this->hasMany('App\Models\Siswa', 'kelas_id');
    }

    public function pembelajaran()
    {
        return $this->hasMany(Pembelajaran::class, 'tahun_ajaran_id');
    }

    public function waliKelas()
    {
        return $this->hasMany(WaliKelas::class, 'tahun_ajaran_id');
    }
}
