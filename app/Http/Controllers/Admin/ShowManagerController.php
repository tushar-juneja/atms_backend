<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ShowManagerController extends Controller
{
    public function index()
    {
        $showManagers = User::where('role', 'show_manager')->get();
        return view('admin.show_managers.index', compact('showManagers'));
    }

    // Other methods for show manager management (create, update, delete) can be added here
}