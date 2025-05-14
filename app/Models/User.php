<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $guard = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'password_plain',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // auth
    public function guru()
    {
        return $this->hasOne('App\Models\Guru', 'user_id');
    }

    public function siswa()
    {
        return $this->hasOne('App\Models\Siswa', 'user_id');
    }

    // enroll
    public function enrollments()
    {
        return $this->hasMany(Enrollments::class, 'siswa_id');
    }

    public static function getSiswa()
    {
        return self::role('siswa')->get();
    }

    // Relasi ke Pembelajaran
    public function pembelajaran()
    {
        return $this->hasMany(Pembelajaran::class, 'guru_id');
    }

    public static function getGuru()
    {
        return self::role('guru')->get();
    }

    // submit tugas
    public function submitTugas()
    {
        return $this->hasMany(SubmitTugas::class, 'siswa_id');
    }

    // siswa kuuis session
    public function SiswaKuisSession()
    {
        return $this->hasMany(SiswaKuisSession::class, 'siswa_id');
    }


    public function jawabanKuis()
    {
        return $this->hasMany(JawabanKuis::class, 'siswa_id');
    }

    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class, 'siswa_id');
    }


    // Relasi dengan UserActivity
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function detailAbsensi()
    {
        return $this->hasMany(DetailAbsensi::class, 'siswa_id');
    }

}
