<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard'); // single entry point
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

     public function logout()
    {
        Auth::logout();          // log the user out
        session()->invalidate(); // clear session
        session()->regenerateToken(); // regenerate CSRF token

        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}
