<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kuis extends Model
{
    use SoftDeletes;

    protected $table = 'kuis';

    protected $dates = [
        'created_at',
        'update_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'materi_id',
        'judul',
        'kategori',
    ];

    public function materi()
    {
        return $this->belongsTo('App\Models\Materi', 'materi_id');
    }

    public function soalKuis()
    {
        return $this->hasMany('App\Models\SoalKuis', 'kuis_id','id');
    }

    public function pertemuanKuis()
    {
        return $this->hasMany(PertemuanKuis::class, 'kuis_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($kuis) {
            foreach ($kuis->pertemuanKuis as $kuisPertemuan) {
                $kuisPertemuan->delete(); // Soft delete kuisPertemuan terkait
            }
        });        

        static::restoring(function ($kuis) {
            // Restore semua kuisPertemuan kuis yang terkait saat kuis di-restore
            $kuis->pertemuanKuis()->restore();
        });
    }
}
