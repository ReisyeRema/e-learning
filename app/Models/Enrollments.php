<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollments extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'enrollments';

    protected $fillable = [
        'siswa_id',
        'pembelajaran_id',
        'status',
    ];


    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

 
    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class, 'pembelajaran_id');
    }
}
