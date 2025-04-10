<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tugas extends Model
{
    use SoftDeletes;

    protected $table = 'tugas';

    protected $dates = [
        'created_at',
        'update_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id','materi_id','judul', 'deskripsi', 'file_path', 'mime_type', 'file_size'
    ];

    public function materi()
    {
        return $this->belongsTo('App\Models\Materi', 'materi_id');
    }

    public function pertemuanTugas()
    {
        return $this->hasMany(PertemuanTugas::class, 'tugas_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($tugas) {
            foreach ($tugas->pertemuanTugas as $tugasPertemuan) {
                $tugasPertemuan->delete(); // Soft delete tugasPertemuan terkait
            }
        });        

        static::restoring(function ($tugas) {
            // Restore semua tugasPertemuan materi yang terkait saat materi di-restore
            $tugas->pertemuanTugas()->restore();
        });
    }


    // submit tugas
    public function submitTugas()
    {
        return $this->hasMany(SubmitTugas::class, 'tugas_id', 'id');
    }

}
