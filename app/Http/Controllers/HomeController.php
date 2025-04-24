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
        $publishedShows = Show::where('published', true)
            ->orderBy('date_time', 'desc')
            ->get()->toArray();

        return response()->json([$publishedShows]);
        
        // return view('frontend.index', compact('publishedShows'));
    }

    public function showEventDetails(Show $show)
    {
        return view('frontend.show_details', compact('show'));
    }
}
