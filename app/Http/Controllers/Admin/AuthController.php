<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login information'
            ], 401);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user->is_admin) {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed!!'
            ], 403);
        }

        if (!$user->is_active) {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'You are not active, Contact support!'
            ], 403);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Login successful'
        ], 200);
    }
}
