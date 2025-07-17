<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaliKelas extends Model
{
    use SoftDeletes;

    protected $table = 'wali_kelas';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'guru_id', 'kelas_id', 'tahun_ajaran_id','status'
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
