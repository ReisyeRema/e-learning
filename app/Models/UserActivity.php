<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserActivity extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang digunakan oleh model
    protected $table = 'user_activities';

    // Tentukan kolom-kolom yang dapat diisi
    protected $fillable = [
        'user_id',      
        'activity',     
        'details',      
        'user_agent',   
        'performed_at', 
    ];

    // Menentukan relasi dengan model User (satu aktivitas dimiliki oleh satu pengguna)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
