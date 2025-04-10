<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PertemuanKuis extends Model
{
    use SoftDeletes;

    protected $table = 'pertemuan_kuis';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pembelajaran_id',
        'pertemuan_id',
        'kuis_id',
        'deadline',
        'token',
        // 'kategori_kuis',
    ];


    public function pembelajaran()
    {
        return $this->belongsTo('App\Models\Pembelajaran', 'pembelajaran_id');
    }

    public function pertemuan()
    {
        return $this->belongsTo('App\Models\Pertemuan', 'pertemuan_id');
    }

    public function kuis()
    {
        return $this->belongsTo('App\Models\Kuis', 'kuis_id');
    }
}
