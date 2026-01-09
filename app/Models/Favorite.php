<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'user_id',
        'type',
        'tmdb_id',
    ];

    // // Optional: relationship to user
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
