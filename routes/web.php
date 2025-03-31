<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ShowManagerController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/admin/show-managers', [ShowManagerController::class, 'index'])->name('admin.show_managers.index');

    Route::get('/admin/show-managers/create', [ShowManagerController::class, 'create'])->name('admin.show_managers.create');
    Route::post('/admin/show-managers/create', [ShowManagerController::class, 'store'])->name('admin.show_managers.store');


});

require __DIR__.'/auth.php';
