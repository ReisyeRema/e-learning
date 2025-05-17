<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absensi extends Model
{
    use SoftDeletes;

    protected $table = 'absensi';

    protected $fillable = [
        'pertemuan_id',
        'pembelajaran_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'is_multisession',
        'ulangi_pada',
        'ulangi_sampai',
        'aktif',
    ];

    protected $casts = [
        'tanggal'        => 'date',
        'jam_mulai'      => 'string',
        'jam_selesai'    => 'string',
        'is_multisession'=> 'boolean',
        'ulangi_pada'    => 'array',
        'ulangi_sampai'  => 'date',
        'aktif'  => 'boolean',
    ];
    

    // Relasi ke pertemuan
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    // Relasi ke pembelajaran
    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class);
    }

    // Relasi ke detail absensi
    public function detailAbsensi()
    {
        return $this->hasMany(DetailAbsensi::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($absensi) {
            foreach ($absensi->detailAbsensi as $absensiDetail) {
                $absensiDetail->delete(); // Soft delete kuisPertemuan terkait
            }
        });

        static::restoring(function ($absensi) {
            // Restore semua absensiPertemuan absensi yang terkait saat absensi di-restore
            $absensi->detailAbsensi()->restore();
        });
    }
}
