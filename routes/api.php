<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\StatisticController;

// Route::middleware('throttle:auth')->group(function () {
    Route::post('/login',    [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
// });

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('books', BookController::class);

    Route::get('/books/popular', [BookController::class, 'popular']);
    Route::get('/books/recent', [BookController::class, 'recent']);
    Route::post('/books/{book}/reserve', [BookController::class, 'reserve']);
    Route::post('/books/{book}/cancel',  [BookController::class, 'cancel']);

    // Route::post('/books/{book}/reserve', [ReservationController::class, 'reserve']);

    // Admin
    // Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/dash', [StatisticController::class, 'index']);
});