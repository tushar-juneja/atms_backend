<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Redirect to different controllers based on role
        if ($user->role === 'audi_sec') {
            $response = app(\App\Http\Controllers\Admin\AudiSecController::class)->listShows($request);
        } elseif ($user->role === 'show_manager') {
            $response = app(\App\Http\Controllers\Admin\ShowManagerController::class)->listShows($request);
        }

        return response()->make($response);

        // abort(403, 'Unauthorized');
    }
}
