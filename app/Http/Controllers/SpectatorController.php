<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SpectatorController extends Controller
{
    public function listPurchases()
    {
        $user = Auth::user();

        // Fetch all purchases associated with the logged-in user, ordered by purchase date (descending)

        $purchases = Purchase::with(['tickets.showSeat.show'])
            ->where('user_id', $user->id)
            ->whereHas('tickets.showSeat.show', function ($query) {
                $query->where('date', '<', Carbon::now());
            })
            ->orderBy('purchase_date', 'desc')
            ->get();

        return view('frontend.tickets_history', compact('purchases'));
    }

    public function showPastPurchase($id)
    {
        $user = Auth::user();

        $purchase = Purchase::with(['tickets.showSeat.show'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->whereHas('tickets.showSeat.show', function ($query) {
                $query->where('date', '<', Carbon::now());
            })
            ->firstOrFail();
    }
}
