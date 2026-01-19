<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'photo',
        'plan_id',
        'provider_id',
        'status',
    ];
}
