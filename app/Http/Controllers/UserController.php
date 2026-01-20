<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        $user = User::query()->when($currentUser->role_id == 2,function ($query){
            return $query->where('role_id',1);
        })->when($currentUser->role_id == 3,function ($query){
            return $query->where('role_id',[1,2]);
        })->get();
        
        return response()->json([
            'status'=>true,
            'data'=>$user
        ]);
    }
}