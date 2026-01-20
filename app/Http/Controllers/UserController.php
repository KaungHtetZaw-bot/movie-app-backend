<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(
            User::select(
                'id',
                'name',
                'email',
                'is_vip',
                'vip_expires_at',
                'created_at'
            )->get()
        );
    }
}
