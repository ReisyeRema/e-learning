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

    public function kuis()
    {
        return $this->belongsTo('App\Models\Kuis', 'kuis_id');
    }

    // siswa kuuis session
    public function SiswaKuisSession()
    {
        return $this->hasMany(SiswaKuisSession::class, 'pertemuan_kuis_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pertemuanKuis) {
            // Cek dulu, apakah relasi tugas tersedia
            $kuis = $pertemuanKuis->kuis;

            if ($kuis) {
                // Hapus semua Submitkuis yang berkaitan dengan kuis ini
                if ($pertemuanKuis->isForceDeleting()) {
                    $kuis->hasilKuis()->forceDelete();
                } else {
                    $kuis->hasilKuis()->delete();
                }
            }
        });

        static::restoring(function ($pertemuanKuis) {
            $kuis = $pertemuanKuis->kuis;

            if ($kuis) {
                $kuis->hasilKuis()->withTrashed()->restore();
            }
        });
    }
}
