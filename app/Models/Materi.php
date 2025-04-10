<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materi extends Model
{
    use SoftDeletes;

    protected $table = 'materi';

    protected $dates = [
        'created_at',
        'update_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id','judul', 'deskripsi', 'file_path', 'mime_type', 'file_size'
    ];


    public function kuis()
    {
        return $this->hasMany(Kuis::class, 'materi_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'materi_id');
    }

    public function pertemuanMateri()
    {
        return $this->hasMany(PertemuanMateri::class, 'materi_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($materi) {
            foreach ($materi->pertemuanMateri as $materiPertemuan) {
                $materiPertemuan->delete(); // Soft delete materiPertemuan terkait
            }
        });        

        static::restoring(function ($materi) {
            // Restore semua materiPertemuan materi yang terkait saat materi di-restore
            $materi->pertemuanMateri()->restore();
        });
    }

}
