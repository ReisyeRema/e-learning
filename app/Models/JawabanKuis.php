<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JawabanKuis extends Model
{
    use SoftDeletes;

    protected $table = 'jawaban_kuis';

    protected $fillable = [
        'soal_id',
        'siswa_id',
        'jawaban_user',
        'status',
    ];

    public function soal()
    {
        return $this->belongsTo(SoalKuis::class, 'soal_id');
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
