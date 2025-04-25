<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Show;
use App\Models\User;
use App\Models\ShowSeat;
use App\Models\Ticket;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmationMail; // Import your Mail class
use App\Mail\BookingCancellationMail; // Import your Mail class


class SpectatorController extends Controller
{
    public function listPurchases(Request $request)
    {
        // Fetch all purchases associated with the logged-in user, ordered by purchase date (descending)
        try {
        $purchases = Purchase::with(['tickets.showSeat'])
            ->where('user_id', $request->user_id)
            ->whereHas('tickets.showSeat.show', function ($query) {
                $query->where('date_time', '<', Carbon::now());
            })
            ->orderBy('purchase_date', 'desc')
            ->get();


        // Format the data for the response
        $formattedPurchases = $purchases->map(function ($purchase) {
            $show = $purchase->tickets()->first()->showSeat->first()->show;
            return [
                'purchase_id' => $purchase->id,
                'purchase_date' => $purchase->purchase_date,
                'original_amount' => $purchase->original_amount,
                'final_amount' => $purchase->final_amount,
                'status' => $purchase->status,
                'refund_amount' => $purchase->refund_amount,
                'show' => $show,
                'tickets' => $purchase->tickets->map(function ($ticket) {
                    return $ticket->showSeat;
                }),
                ];
            });

            // return response()->json($formattedPurchases, 200, [], JSON_PRETTY_PRINT); // for seeing the json in a readable format
            return response()->json($formattedPurchases, 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching user tickets: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch user tickets: ' . $e->getMessage()], 500);
        }
        // return view('frontend.tickets_history', compact('purchases'));
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

        $bookedSeatIds = Ticket::whereHas('showSeat', function ($query) use ($showId) {
            $query->where('show_id', $showId);
        })
        ->whereHas('purchase', function($query) {
            $query->where('status', '!=', 'cancelled');
        })
        ->pluck('show_seat_id')
        ->toArray();

        // Separate ordinary and balcony seats
        $ordinarySeats = [];
        $balconySeats = [];

        $discount = $show->coupon;

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
                'discount_amount' => $discount ? $discount->discount_amount : null,
                'discount_min_cart_value' => $discount ? $discount->minimum_cart_value : null,
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
                'original_amount' => $request['original_amount'],
                'final_amount' => $request['final_amount'],
                'show_discount_id' => null,
                // 'show_discount_id' => $request['show_discount_id'] ?? null,
            ]);

            // 4. Create tickets for each selected seat
            $tickets = [];

            foreach ($request['seats'] as $seatData) {
                // Find the show_seat_id.  Important to use the seat_id from the request.
                $showSeat = DB::table('show_seats')
                    ->where('show_id', $request['show_id'])->where('seat_id', $seatData['seat_id'])->where('seat_type', $seatData['type'])
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

            $purchase = Purchase::with(['tickets.showSeat.show'])->find($purchase->id); // Fetch the purchase with tickets and show details

            Mail::to(env('MAIL_TO'))->send(new BookingConfirmationMail($purchase)); // Send confirmation email

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

    public function cancelPurchase(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $purchase = Purchase::with('tickets.showSeat.show', 'user')
                ->findOrFail($request['purchase_id']); // Eager load relations, include user

            $totalTicketPrice = 0;
            $now = Carbon::now();

            // Calculate the total price of all tickets in the purchase
            foreach ($purchase->tickets as $ticket) {
                $totalTicketPrice += $ticket->showSeat->price; // Access price through the showSeat relationship
            }
            $showTime = Carbon::parse($purchase->tickets->first()->showSeat->show->date_time);
            $diffInHours = $now->diffInHours($showTime);
            // Calculate refund amount based on cancellation time
            if ($diffInHours < 24) {
                $refundPercentage = 0.5; // 50% refund
            } elseif ($diffInHours < 48) {
                $refundPercentage = 0.75; // 75% refund
            } else {
                $refundPercentage = 1; // 100% refund
            }
            $totalRefundAmount = $totalTicketPrice * $refundPercentage;
            $purchase->status = 'cancelled'; // Update purchase status
            $purchase->refund_amount = $totalRefundAmount; // Store the refund
            $purchase->save();

            // Send cancellation email
            Mail::to(env('MAIL_TO'))->send(new BookingCancellationMail($purchase, $totalRefundAmount));

            return response()->json([
                'message' => 'Your purchase has been cancelled.',
                'refund_amount' => $totalRefundAmount,
            ]); // Return JSON for API
        }); // Commit or rollback
    }
}
