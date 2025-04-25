<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // use password_confirmation field
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log in the user (optional)
        Auth::login($user);

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // return response()->json(['cookies' => $request->cookies->all(), 'session_id' => $request->session()->getId(), 'csrf token from request' => $request->session()->token(), 'expected_token' => csrf_token()]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $user = Auth::user();

        return response()->json(
            [
                'message' => 'Login successful',
                'user' => $user,
            ],
            200,
        );
    }

    public function user(Request $request)
    {
        // Return the authenticated user
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        // Auth::logout();
        Auth::guard('web')->logout(); // Explicitly use web guard

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
