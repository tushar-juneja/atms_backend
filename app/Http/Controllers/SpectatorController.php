<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Show;
use App\Models\ShowSeat;
use App\Models\Ticket;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function getShowDetails($showId)
    {
        $show = Show::with(['seats'])->findOrFail($showId);

        // Get IDs of show seats that have been booked (from tickets table)
        $bookedSeatIds = Ticket::whereHas('showSeat', function ($query) use ($showId) {
            $query->where('show_id', $showId);
        })
            ->pluck('show_seat_id')
            ->toArray();

        // Map seat data with VIP and booked status
        $seats = $show->seats->map(function ($seat) use ($bookedSeatIds) {
            return [
                'seat_id' => $seat->seat_id,
                'price' => $seat->price,
                'is_vip' => $seat->is_reserved,
                'is_booked' => in_array($seat->id, $bookedSeatIds),
            ];
        });

        // Separate ordinary and balcony seats
        $ordinarySeats = [];
        $balconySeats = [];

        foreach ($show->seats as $seat) {
            $seatData = [
                'seat_id' => $seat->seat_id,
                'price' => $seat->price,
                'is_vip' => $seat->is_reserved,
                'is_booked' => in_array($seat->id, $bookedSeatIds),
            ];

            if ($seat->seat_type === 'balcony') {
                $balconySeats[] = $seatData;
            } else {
                $ordinarySeats[] = $seatData;
            }
        }

        return response()->json([
            'show' => [
                'id' => $show->id,
                'name' => $show->name,
                'date' => $show->date,
                'time' => $show->time,
                'artist' => $show->artist,
            ],
            'seats' => [
                'ordinary' => $ordinarySeats,
                'balcony' => $balconySeats,
            ],
            // 'coupons' => $show->coupons->map(function ($coupon) {
            //     return [
            //         'code' => $coupon->code,
            //         'discount' => $coupon->discount,
            //     ];
            // }),
        ]);
    }

    public function purchaseTickets(Request $request)
    {
        // 1. Validate the request data

        // $validator = Validator::make($request->all(), [
        //     'show_id' => 'required|integer|exists:shows,id', // Ensure show exists
        //     'seats' => 'required|array',
        //     'seats.*.seat_id' => 'required|integer|exists:show_seats,id', // Ensure show_seat exists
        //     'seats.*.row' => 'required|string',  // Add validation for row
        //     'seats.*.seatIndex' => 'required|integer', // Add validation for seatIndex
        //     'seats.*.type' => 'required|string|in:ordinary,balcony', // Add validation for type
        //     'seats.*.price' => 'required|numeric', // Add validation for price
        //     'user_id' => 'required|integer|exists:users,id', //Add user_id
        //     'original_amount' => 'required|numeric',
        //     'final_amount' => 'required|numeric',
        //     'show_discount_id' => 'nullable|integer|exists:show_discounts,id',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        // 2. Begin a database transaction (for data integrity)
        try {
            DB::beginTransaction();

            // $data = $validator->validated(); // Get the validated data.

            // 3. Create a new purchase
            $purchase = Purchase::create([
                'user_id' => $request['user_id'],
                'purchase_date' => now(),
                'original_amount' => $request['amount'],
                'final_amount' => 0,
                'show_discount_id' => null
                // 'final_amount' => $request['final_amount'],
                // 'show_discount_id' => $request['show_discount_id'] ?? null,
            ]);

            // 4. Create tickets for each selected seat
            $tickets = [];

            foreach ($request['seats'] as $seatData) {
                // Find the show_seat_id.  Important to use the seat_id from the request.
                $showSeat = DB::table('show_seats')
                    ->where('show_id', $request['show_id'])->where('seat_id', $seatData['seat_id'])
                    ->first();

                if (!$showSeat) {
                    throw new \Exception("Show seat with ID {$seatData['seat_id']} not found."); // Handle the case where the show_seat doesn't exist.
                }

                $tickets[] = [
                    'purchase_id' => $purchase->id,
                    'show_seat_id' => $showSeat->id, // Use the show_seat_id we found
                    // 'created_at' => now(), // Add created_at and updated_at for the database
                    // 'updated_at' => now(),
                ];
                // $tickets[] = $seatData;
            }

            // Use insert for efficiency
            Ticket::insert($tickets);

            // 5. Commit the transaction
            DB::commit();

            // 6. Return a success response
            return response()->json([
                'message' => 'Tickets purchased successfully',
                'purchase_id' => $purchase->id,
            ], 201);
        } catch (\Exception $e) {
            // 7. Rollback the transaction in case of an error
            DB::rollBack();

            // 8. Handle the error (log it, display a message, etc.)
            \Log::error('Error purchasing tickets: ' . $e->getMessage()); // Log the error
            return response()->json(['error' => 'Failed to purchase tickets: ' . $e->getMessage()], 500); // Return a user-friendly error message
        }
    }
}
