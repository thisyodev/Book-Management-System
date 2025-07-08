<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookApiController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::get('/me', [AuthController::class, 'me']);

    // API-only CRUD
    Route::get('/books', [BookApiController::class, 'index']);
    Route::post('/books', [BookApiController::class, 'store']);
    Route::put('/books/{id}', [BookApiController::class, 'update']);
    Route::delete('/books/{id}', [BookApiController::class, 'destroy']);
});
