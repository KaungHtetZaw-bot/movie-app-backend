<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\RecentlistController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PurchaseController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/media/trending', [MediaController::class, 'trending']);
    Route::get('/media/popular/{type}', [MediaController::class, 'popular']);
    Route::get('/media/search', [MediaController::class, 'search']);
    Route::get('/genres/{type}', [MediaController::class, 'genres']);
    Route::get('/media/genre/{type}/{genreId}', [MediaController::class, 'byGenre']);

    Route::get('/media/{type}/{id}', [MediaController::class, 'details']);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{type}/{tmdb_id}', [FavoriteController::class, 'destroy']);

    Route::get('/watchlist', [WatchlistController::class, 'index']);
    Route::post('/watchlist', [WatchlistController::class, 'store']);
    Route::delete('/watchlist/{type}/{tmdb_id}', [WatchlistController::class, 'destroy']);

    Route::get('/recentlist', [RecentlistController::class, 'index']);
    Route::post('/recentlist', [RecentlistController::class, 'store']);
    Route::delete('/recentlist/{type}/{tmdb_id}', [RecentlistController::class, 'destroy']);

    Route::get('/plans',[PlanController::class, 'index']);
    Route::get('/payments',[PaymentController::class, 'index']);

    Route::post('/purchases', [PurchaseController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::patch('/purchases/{id}/approve', [PurchaseController::class, 'approve']);
    Route::patch('/purchases/{id}/reject', [PurchaseController::class, 'reject']);
    Route::patch('/plans/{id}', [PlanController::class, 'change']);
    Route::post('/plans', [PlanController::class, 'add']);
    Route::delete('/plans/{id}', [PlanController::class, 'delete']);
    Route::patch('/payments/{id}', [PaymentController::class, 'change']);
    Route::post('/payments', [PaymentController::class, 'add']);
    Route::delete('/payments/{id}', [PaymentController::class, 'delete']);
});