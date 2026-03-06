<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserMediaController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-code', [AuthController::class, 'verifyCode']);
Route::post('/login', [AuthController::class, 'login']);
    Route::get('/media/trending', [MediaController::class, 'trending']);

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
    Route::get('/users', [UserController::class, 'index']);
    Route::patch('/users/{id}/change-profile', [UserController::class, 'changeProfile']);
    Route::patch('/users/{id}/change-password', [UserController::class, 'changePassword']);

    Route::get('/media/popular/{type}', [MediaController::class, 'popular']);
    Route::get('/media/search', [MediaController::class, 'search']);
    Route::get('/genres/{type}', [MediaController::class, 'genres']);
    Route::get('/media/genre/{type}/{genreId}', [MediaController::class, 'byGenre']);

    Route::get('/media/detail/{type}/{id}', [MediaController::class, 'details']);

    Route::prefix('user/lists')->group(function () {
        Route::get('{type}', [UserMediaController::class,'index']);
        Route::post('{type}', [UserMediaController::class,'store']);
        Route::delete('{type}/{media_type}/{tmdb_id}',
            [UserMediaController::class,'destroy']);
    });

    Route::get('/plans',[PlanController::class, 'index']);
    Route::get('/payments',[PaymentController::class, 'index']);
    Route::get('/purchases', [PurchaseController::class, 'index']);
    Route::post('/purchases', [PurchaseController::class, 'store']);
});

Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::patch('/purchases/{id}/approve', [PurchaseController::class, 'approve']);
    Route::patch('/purchases/{id}/reject', [PurchaseController::class, 'reject']);
    Route::patch('/plans/{id}', [PlanController::class, 'change']);
    Route::post('/plans', [PlanController::class, 'add']);
    Route::delete('/plans/{id}', [PlanController::class, 'delete']);
    Route::patch('/payments/{id}', [PaymentController::class, 'change']);
    Route::post('/payments', [PaymentController::class, 'add']);
    Route::delete('/payments/{id}', [PaymentController::class, 'delete']);
});