<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpectatorController;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmationMail; // Import your Mail class

    Route::get('/shows/{id}', [SpectatorController::class, 'getShowDetails'])->name('homepage.show');
Route::group(['middleware' => ['web']], function () {
    Route::get('/shows', [HomeController::class, 'shows'])->name('homepage.shows');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
        Route::post('/purchase/tickets', [SpectatorController::class, 'purchaseTickets'])->name('show.purchase.tickets');

        Route::post('/tickets/history', [SpectatorController::class, 'listPurchases'])->name('purchases.index');
    });
    // Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


    // Route::middleware('role:spectator')->group(function () {
        Route::get('/purchases/{id}/tickets', [SpectatorController::class, 'showPastPurchase'])->name('purchases.tickets');
    // });

});

            Route::get('/send/mail', function() {
                $purchase = App\Models\Purchase::with(['tickets.showSeat.show'])->find(9); // Fetch the purchase with tickets and show details
                Mail::to(env('MAIL_TO'))->send(new BookingConfirmationMail($purchase)); // Send confirmation email
                dd($purchase);
            });
        //     Route::get('/send/mail', function() {
        //         // $purchase = App\Models\Purchase::first(); // Fetch the purchase you want to send the email for
        //         // dd($purchase);
        // $purchase = App\Models\Purchase::with(['tickets.showSeat'])
        //     ->where('user_id', 5)
        //     ->whereHas('tickets.showSeat.show', function ($query) {
        //         $query->where('date_time', '<', \Carbon\Carbon::now());
        //     })
        //     ->orderBy('purchase_date', 'desc')
        //     ->first();
        //     // dd($purchase);
        //     // $show = $purchase->tickets()->first()->showSeat->first()->show;
        //         Mail::to(env('MAIL_TO'))->send(new BookingConfirmationMail($purchase)); // Send confirmation email
        //     });

// Route::middleware('auth:sanctum')->group(function () {
//     // Route::get('/user', [AuthController::class, 'user']);
//     // Route::post('/logout', [AuthController::class, 'logout']);
// });
