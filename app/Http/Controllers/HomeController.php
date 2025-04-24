<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Fetch published shows ordered by date and time.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function shows()
    {
        $publishedShows = Show::where('published', true)->orderBy('date_time', 'desc')->get()->toArray();

        return response()->json([$publishedShows]);

        // return view('frontend.index', compact('publishedShows'));
    }

    public function getShowDetails($id)
    {
        $show = Show::with(['seats.seat', 'reservedSeats.seat', 'tickets.seat', 'tickets.user', 'coupon'])->find($id);

        if (!$show) {
            return response()->json(['message' => 'Show not found'], 404);
        }

        return response()->json([
            'id' => $show->id,
            'name' => $show->name,
            'date' => $show->date,
            'time' => $show->time,
            'artist' => $show->artist,

            'coupon' => $show->coupon
                ? [
                    'code' => $show->coupon->code,
                    'discount' => $show->coupon->discount,
                    'expires_at' => $show->coupon->expires_at,
                ]
                : null,

            'seats' => $show->seats->map(function ($seat) {
                return [
                    'id' => $seat->id,
                    'seat_id' => $seat->seat_id,
                    'seat_number' => $seat->seat->number ?? null,
                    'seat_row' => $seat->seat->row ?? null,
                    'price' => $seat->price,
                    'is_reserved' => $seat->is_reserved,
                ];
            }),

            'vip_reserved_seats' => $show->reservedSeats->map(function ($seat) {
                return [
                    'seat_id' => $seat->seat_id,
                    'seat_number' => $seat->seat->number ?? null,
                    'seat_row' => $seat->seat->row ?? null,
                    'price' => $seat->price,
                ];
            }),

            'booked_seats' => $show->tickets->map(function ($ticket) {
                return [
                    'ticket_id' => $ticket->id,
                    'user' => [
                        'id' => $ticket->user->id,
                        'name' => $ticket->user->name,
                        'email' => $ticket->user->email,
                    ],
                    'seat_id' => $ticket->seat_id,
                    'seat_number' => $ticket->seat->number ?? null,
                    'seat_row' => $ticket->seat->row ?? null,
                    'booked_at' => $ticket->created_at->toDateTimeString(),
                ];
            }),
        ]);
    }

    public function showEventDetails(Show $show)
    {
        return view('frontend.show_details', compact('show'));
    }
}
