<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilSekolah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "profile_sekolah"; 


    protected $dates = [
        'created_at',
        'update_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'akreditas',
        'email',
        'no_hp',
        'foto',
        'latitude',
        'longitude',
    ];
}
