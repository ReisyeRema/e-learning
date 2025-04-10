<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Kurikulum extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kurikulum';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nama_kurikulum',
        'deskripsi',
        'icon',
    ];


    // Relasi ke Pembelajaran
    public function pembelajaran()
    {
        return $this->hasMany(Pembelajaran::class, 'kurikulum_id');
    }
}
