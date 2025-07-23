<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PertemuanTugas extends Model
{
    use SoftDeletes;

    protected $table = 'pertemuan_tugas';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pembelajaran_id',
        'pertemuan_id',
        'tugas_id',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];



    public function pembelajaran()
    {
        return $this->belongsTo('App\Models\Pembelajaran', 'pembelajaran_id');
    }

    public function pertemuan()
    {
        return $this->belongsTo('App\Models\Pertemuan', 'pertemuan_id');
    }

    public function tugas()
    {
        return $this->belongsTo('App\Models\Tugas', 'tugas_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pertemuanTugas) {
            // Cek dulu, apakah relasi tugas tersedia
            $tugas = $pertemuanTugas->tugas;

            if ($tugas) {
                // Hapus semua SubmitTugas yang berkaitan dengan tugas ini
                if ($pertemuanTugas->isForceDeleting()) {
                    $tugas->submitTugas()->forceDelete();
                } else {
                    $tugas->submitTugas()->delete();
                }
            }
        });

        static::restoring(function ($pertemuanTugas) {
            $tugas = $pertemuanTugas->tugas;

            if ($tugas) {
                $tugas->submitTugas()->withTrashed()->restore();
            }
        });
    }
}
