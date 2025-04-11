<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiswaKuisSession extends Model
{
    use SoftDeletes;

    protected $table = 'siswa_kuis_session';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pertemuan_kuis_id',
        'siswa_id',
        'jam_mulai',
        'jam_selesai',
        'token',
    ];

    public function pertemuanKuis()
    {
        return $this->belongsTo(PertemuanKuis::class, 'pertemuan_kuis_id');
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
