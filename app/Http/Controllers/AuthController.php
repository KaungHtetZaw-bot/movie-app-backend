<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $verification = rand(100000,600000);

        Cache::put('verification_code_' . $request->email, $verification, now()->addMinutes(3));
        try {
            Mail::raw("Your Verification Code is: $verification", function ($message) use ($request) {
            $message->to($request->email)->subject('Verification Code');
            });
            return response()->json([
                'message' => 'Code sent to email'
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send verification code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyCode(Request $request)
    {
        $storedCode = Cache::get('verification_code_' . $request->email);
        if ($storedCode != $request->code) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $role = Role::where('name', 'user')->first();

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $role ? $role->id : 1, 
            'email_verified_at' => now(),
        ]);

        Cache::forget('verification_code_' . $request->email);

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'token' => $user->createToken('api')->plainTextToken,
            'user'  => $user
        ]);
    }
}

