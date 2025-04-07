<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AudiSecController;
use App\Http\Controllers\Admin\ShowManagerController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    // return Inertia::render('Dashboard');
    return view('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::middleware(['roleRedirect', 'role:audi_sec'])->group(function () {
        Route::get('/admin/shows', [AudiSecController::class, 'listShows'])->name('admin.shows.index');
    });

    Route::middleware('role:audi_sec')->group(function () {
        Route::get('/admin/shows/create', [AudiSecController::class, 'createShow'])->name('admin.shows.create');
        Route::post('/admin/shows/create', [AudiSecController::class, 'storeShow'])->name('admin.shows.store');
        Route::get('/admin/shows/{id}/update', [AudiSecController::class, 'editShow'])->name('admin.shows.edit');
        Route::put('/admin/shows/{id}', [AudiSecController::class, 'updateShow'])->name('admin.shows.update');
        

        Route::get('/admin/show-managers', [AudiSecController::class, 'listShowManagers'])->name('admin.show_managers.index');

        Route::get('/admin/show-managers/create', [AudiSecController::class, 'createShowManager'])->name('admin.show_managers.create');
        Route::post('/admin/show-managers/create', [AudiSecController::class, 'storeShowManager'])->name('admin.show_managers.store');

        Route::get('/admin/show-managers/{id}/update', [AudiSecController::class, 'editShowManager'])->name('admin.show_managers.edit');
        Route::put('/admin/show-managers/{id}', [AudiSecController::class, 'updateShowManager'])->name('admin.show_managers.update');
    });

    Route::middleware(['roleRedirect', 'role:show_manager'])->group(function () {
        Route::get('/admin/shows', [ShowManagerController::class, 'listShows'])->name('admin.shows.index');
    });

    Route::middleware('role:show_manager')->group(function () {
        Route::get('/admin/shows/{show}/configure', [ShowManagerController::class, 'configureShow'])->name('show_manager.shows.configure');
        Route::post('/admin/shows/{show}/configure', [ShowManagerController::class, 'addSeatingConfiguration'])->name('show_manager.shows.add_update_seating_config');

        Route::post('/admin/shows/{show}/coupons/configure', [ShowManagerController::class, 'addUpdateCouponConfiguration'])->name('show_manager.shows.add_update_discount_config');

        Route::delete('/admins/shows/{show}/reset-seats', [ShowManagerController::class, 'resetSeatsConfiguration'])->name('show_manager.shows.reset_seats_config');
    });
});

require __DIR__.'/auth.php';
