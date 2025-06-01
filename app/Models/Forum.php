<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    use Sluggable, SoftDeletes;

    protected $table = 'forum';
    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'judul'
            ]
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function komentar()
    {
        return $this->hasMany(Komentar::class);
    }

    public function pembelajaran()
    {
        return $this->belongsTo(Pembelajaran::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($forum) {
            foreach ($forum->komentar as $komentarForum) {
                $komentarForum->delete(); // Soft delete komentarForum terkait
            }
        });

        static::restoring(function ($forum) {
            // Restore semua komentarForum kuis yang terkait saat kuis di-restore
            $forum->komentar()->restore();
        });
    }
}
