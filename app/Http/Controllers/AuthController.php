<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Get the authenticated User.
     */
    public function profile()
    {
        // Explicitly use the api guard for JWT
        $user = auth('api')->user();
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->load('role');

        return response()->json([
            'success' => true,
            'user' => $user
        ], 200);
    }

    /**
     * Register logic with OTP (Keep as is, but ensure response is clean)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns|unique:users,email|regex:/^.+@gmail\.com$/i',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 422);
        }

        $verification = rand(100000, 600000);
        Cache::put('verification_code_' . $request->email, $verification, now()->addMinutes(5));

        try {
            Mail::raw("Your Cinema Admin Verification Code is: $verification", function ($message) use ($request) {
                $message->to($request->email)->subject('Verification Code');
            });
            return response()->json(['message' => 'Code sent to email']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send code', 'error' => $e->getMessage()], 500);
        }
    }

    // ... (Your verifyCode remains the same)

    /**
     * JWT Login
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        // attempt() returns the JWT string on success
        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(auth('api')->refresh());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Refresh token expired or invalid'], 401);
        }
    }

    /**
     * Format the JSON structure for the token response.
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // TTL is usually in minutes, we multiply by 60 for seconds (Vue/Mobile preference)
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()->load('role')
        ]);
    }
}