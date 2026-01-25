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

    public function user() {
    return $this->belongsTo(User::class, 'user_id');
    }

    public function plan() {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function admin() {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
