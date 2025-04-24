<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpectatorController;
use Illuminate\Container\Attributes\Auth;

    Route::get('/shows/{id}', [SpectatorController::class, 'getShowDetails'])->name('homepage.show');
Route::group(['middleware' => ['web']], function () {
    Route::get('/shows', [HomeController::class, 'shows'])->name('homepage.shows');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::post('/purchase/tickets', [SpectatorController::class, 'purchaseTickets'])->name('show.purchase.tickets');
});

// Route::middleware('auth:sanctum')->group(function () {
//     // Route::get('/user', [AuthController::class, 'user']);
//     // Route::post('/logout', [AuthController::class, 'logout']);
// });
