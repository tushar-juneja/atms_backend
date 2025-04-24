<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Container\Attributes\Auth;

Route::group(['middleware' => ['web']], function () {
    Route::get('/shows', [HomeController::class, 'shows'])->name('homepage.shows');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});

// Route::middleware('auth:sanctum')->group(function () {
//     // Route::get('/user', [AuthController::class, 'user']);
//     // Route::post('/logout', [AuthController::class, 'logout']);
// });
