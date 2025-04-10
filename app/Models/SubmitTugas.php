<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmitTugas extends Model
{
    use SoftDeletes;

    protected $table = 'submit_tugas';

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'file_path',
        'mime_type',
        'file_size',
        'url',
        'skor',
        'status'
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }
    

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
