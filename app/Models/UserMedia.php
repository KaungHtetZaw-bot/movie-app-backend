<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMedia extends Model
{
    protected $fillable = [
        'user_id',
        'list_type',
        'media_type',
        'tmdb_id',
        'title',
        'poster_path',
        'vote_average'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
