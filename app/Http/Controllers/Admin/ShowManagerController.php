<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Show;
use App\Models\ShowSeat; // Import the model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Import DB facade

class ShowManagerController extends Controller
{

    public function listShows()
    {
        $user = auth()->user();

        // Fetch shows for the show manager
        $shows = $user->shows;

        return view('show_managers.shows.index', compact('shows'));
    }

    public function configureShow(Show $show)
    {
        $config = config('auditorium');

        $user = auth()->user();

        // Check if the show manager is authorized to configure this show
        if ($show->show_manager_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return view('show_managers.shows.configure', compact('show', 'config'));
    }

    public function updateSeatingConfiguration(Request $request, Show $show)
    {
        // 1. Validation

        $config = config('auditorium');

        $validator = Validator::make($request->all(), [
            'rows.*.balcony' => 'required|in:0,1',
            'rows.*.price' => 'nullable|numeric|required_if:rows.*.vip,off',
            'rows.*.vip' => 'required|in:on,off',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Process and Store Data with Eloquent
        $rows = $request->input('rows');

        try {
            DB::beginTransaction(); // Start a database transaction

            $seat = 1;
            foreach ($rows as $rowData) {
                for ($i = 0; $i < $config['seats_per_row']; $i++) {
                    ShowSeat::create([
                        'show_id' => $show->id,
                        'seat_id' => $seat++,
                        'seat_type' => $rowData['balcony'] ? 'balcony' : 'ordinary',
                        'price' => $rowData['price'] ?? 0,
                        'is_reserved' => isset($rowData['vip']) ? 1 : 0,
                    ]);
                }
            }

            DB::commit(); // Commit the transaction
            return redirect()->route('show_manager.shows.configure', $show->id)->with('success', 'Show Manager Created Successfully!');
        } catch (\Exception $e) {
            DB::rollback(); // Rollback in case of error
            return redirect()->route('show_manager.shows.configure', $show->id)->with('failure', 'Error saving seats configuration: ' . $e->getMessage());
        }
    }
}
