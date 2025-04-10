<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PertemuanMateri extends Model
{
    use SoftDeletes;

    protected $table = 'pertemuan_materi';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pembelajaran_id',
        'pertemuan_id',
        'materi_id',
    ];


    public function pembelajaran()
    {
        return $this->belongsTo('App\Models\Pembelajaran', 'pembelajaran_id');
    }

    public function pertemuan()
    {
        return $this->belongsTo('App\Models\Pertemuan', 'pertemuan_id');
    }

    public function materi()
    {
        return $this->belongsTo('App\Models\Materi', 'materi_id');
    }
}
