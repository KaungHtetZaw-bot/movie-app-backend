<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function edit(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $authUser = Auth::user();

        $this->authorize('update',$targetUser);

        $rules = [
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email:rfc,dns|unique:users,email,'.$id.'|regex:/^.+@gmail\.com$/i',
        ];

        if ($authUser->role_id > 1 && $authUser->id !== $targetUser->id) {
            $rules['role_id'] = 'sometimes|integer|in:1,2,3';
        }

        $isSelfOrEqual = $authUser->role_id <= $targetUser->role_id;
        if ($isSelfOrEqual && ($request->has('email') || $request->has('password'))) {
            $request->validate(['current_password' => 'required|string']);
            
            if (!Hash::check($request->current_password, $targetUser->password)) {
                return response()->json(['message' => "Incorrect password"], 422);
            }
        }

        $validated = $request->validate($rules);
        $targetUser->fill($validated);

        if($targetUser->isDirty('email')){
            $targetUser->email_verified_at = null;
        }
        $targetUser->save();

        return response()->json([
            'message' => 'Updated successfully',
            'user'    => $targetUser->load('role')
        ]);
    }

    public function changePassword(Request $request,$id)
    {
        $targetUser =User::findOrFail($id);
        $authUser = Auth::user();

        $this->authorize('update',$targetUser);

        $needCurrentPassword = ($authUser->id === $targetUser->id) && ($authUser->role_id === $targetUser->role_id);
        $rules = [
            'password' => 'required|string|min:8|confirmed',
        ];

        if($needCurrentPassword){
            $rules['current_password'] = 'required|string';
        }

        $request->validate($rules);

        if ($needCurrentPassword) {
            if (!Hash::check($request->current_password, $targetUser->password)) {
                return response()->json(['message' => 'The current password you entered is incorrect.'], 422);
            }
        }

        $targetUser->password = Hash::make($request->password);
        $targetUser->save();

        return response()->json([
            'message' => 'Password has been updated successfully.'
        ], 200);
    }
}