<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Komentar extends Model
{
    use SoftDeletes;
    
    protected $table = 'komentar';
    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function forum() {
        return $this->belongsTo(Forum::class);
    }

    public function childs() {
        return $this->hasMany(Komentar::class, 'parent');
    }
}
