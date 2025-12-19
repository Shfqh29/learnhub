<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('Module1.auth.register');
    }

    public function register(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Z\s]+$/'],
            'email' => 'required|email|unique:users,email',
            'form' => 'required|in:1,2,3,4,5',
            'password' => [
                'required',
                'confirmed',          // ensures password_confirmation matches
                'min:8',              // minimum 8 characters
                'regex:/[a-z]/',      // at least one lowercase
                'regex:/[A-Z]/',      // at least one uppercase
                'regex:/[0-9]/',      // at least one number
                'regex:/[@$!%*#?&]/', // at least one special character
            ],
        ], [
            'name.required' => 'Name is required.',
            'name.regex' => 'Name must be uppercase letters only.',
            'email.required' => 'Email is required.',
            'email.email' => 'Must be a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and symbol.',
        ]);

        // Create user
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'form' => $validated['form'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',   // auto-assign role
            'status' => 'active',  // active by default
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully! You can now log in.');
    }
}

