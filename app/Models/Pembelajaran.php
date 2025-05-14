<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelajaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pembelajaran';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nama_mapel',
        'guru_id',
        'kelas_id',
        'tahun_ajaran_id',
        'kurikulum_id',
        'semester',
        'cover',
    ];


    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'kelas_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo('App\Models\TahunAjaran', 'tahun_ajaran_id');
    }

    public function guru()
    {
        return $this->belongsTo('App\Models\User', 'guru_id');
    }

    public function kurikulum()
    {
        return $this->belongsTo('App\Models\Kurikulum', 'kurikulum_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollments::class, 'pembelajaran_id');
    }

    public function pertemuanMateri()
    {
        return $this->hasMany(PertemuanMateri::class, 'pembelajaran_id');
    }

    public function pertemuanTugas()
    {
        return $this->hasMany(PertemuanTugas::class, 'pembelajaran_id');
    }

    public function pertemuanKuis()
    {
        return $this->hasMany(PertemuanKuis::class, 'pembelajaran_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'pembelajaran_id');
    }
}
