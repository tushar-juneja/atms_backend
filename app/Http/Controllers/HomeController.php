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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $publishedShows = Show::where('published', true)
            ->orderBy('date_time', 'desc')
            ->get();

        return view('frontend.index', compact('publishedShows'));
    }
}
