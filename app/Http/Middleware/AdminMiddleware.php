<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Explicitly check the 'api' guard for JWT users
        $user = auth('api')->user();

        // 1. Check if user is logged in via JWT
        // 2. Check if the user has admin/super_admin role via the helper in User model
        if (!$user || !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: You do not have administrative privileges.',
                'code' => 403
            ], 403);
        }

        return $next($request);
    }
}