<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Show;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AudiSecController extends Controller
{
    public function listShowManagers()
    {
        $showManagers = User::where('role', 'show_manager')->get();
        return view('admin.show_managers.index', compact('showManagers'));
    }

    public function listShows()
    {
        $shows = Show::all();
        return view('admin.shows.index', compact('shows'));
    }

    public function createShow()
    {
        $showManagers = User::where('role', 'show_manager')->get();
        return view('admin.shows.create', compact('showManagers'));
    }

    public function storeShow(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'date_time' => 'required|date_format:Y-m-d\TH:i',
            'artist' => 'required|string|max:255',
            'show_manager_id' => 'required|exists:users,id',
            'publish' => 'nullable|boolean',
        ]);

        $request['publish'] = isset($request['publish']) ? 1 : 0;

        // Create and save the show in the database
        Show::create($request->all());

        // Redirect with success message
        return redirect()->route('admin.shows.index')->with('success', 'Show created successfully!');
    }

    // Other methods for show manager management (create, update, delete) can be added here

    public function createShowManager()
    {
        return view('admin.show_managers.create');
    }

    public function storeShowManager(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Create a new Show Manager (Assuming 'role' column exists)
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'show_manager',
        ]);

        return redirect()->route('admin.show_managers.index')->with('success', 'Show Manager Created Successfully!');
    }

    public function editShowManager($id)
    {
        $showManager = User::findOrFail($id);
        return view('admin.show_managers.edit', compact('showManager'));
    }

    public function updateShowManager(Request $request, $id)
    {
        $showManager = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        $showManager->name = $request->name;

        if ($request->password) {
            $showManager->password = Hash::make($request->password);
        }

        $showManager->save();

        return redirect()->route('admin.show_managers.index', $id)->with('success', 'Show Manager Updated Successfully!');
    }
}
