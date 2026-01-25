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

    public function edit(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);
        $user = User::findOrFail($request->id);
        $requestUser = Auth::user();
        if($requestUser->role_id <= $user->role_id){
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        };  
        $user->update([
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'user' => $user,
            'can_edit' => $requestUser->role_id > $user->role_id
        ]);
    }
}