<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailAbsensi extends Model
{
    use SoftDeletes;

    protected $table = 'detail_absensi';

    protected $fillable = [
        'absensi_id',
        'siswa_id',
        'keterangan',
    ];

    // Relasi ke absensi
    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
