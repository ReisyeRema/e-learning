<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HasilKuis extends Model
{
    use SoftDeletes;

    protected $table = 'hasil_kuis';

    protected $fillable = [
        'kuis_id', 'siswa_id', 'jawaban_user','status','skor_total', 'is_done',
    ];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanKuis::class, 'siswa_id', 'siswa_id')
            ->whereHas('soal', function ($q) {
                $q->whereColumn('kuis_id', 'hasil_kuis.kuis_id');
            });
    }

}
