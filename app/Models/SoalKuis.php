<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoalKuis extends Model
{
    use SoftDeletes;

    protected $table = 'soal_kuis';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'kuis_id', 'teks_soal', 'type_soal','gambar', 'jawaban_benar','pilihan_jawaban', 'skor'
    ];

    protected $casts = [
        'pilihan_jawaban' => 'array',
    ];    

    public function kuis()
    {
        return $this->belongsTo('App\Models\Kuis', 'kuis_id','id');
    }

    public function jawabanKuis()
    {
        return $this->hasMany(JawabanKuis::class, 'soal_id');
    }


}
