<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if account exists and is deactivated
        if ($user && $user->status !== 'Active') {
            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact the administrator.',
            ]);
        }

        // Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // regenerate session
            return redirect()->route('home'); // successful login
        }

        // If login fails
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
