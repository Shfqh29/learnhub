<?php

namespace App\Http\Controllers\Module1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        switch ($user->role) {
            case 'admin':
                return view('Module1.administrator.dashboard');
            case 'teacher':
                return view('Module1.teacher.dashboard');
            default:
                return view('Module1.student.dashboard');
        }
    }

    public function editProfile()
    {
        if (!auth()->check()) {
            // Redirect to login if not logged in
            return redirect('/login');
        }

        $user = auth()->user();

        // Choose Blade based on user type
        switch ($user->role) {
            case 'teacher':
                $view = 'Module1.teacher.profile';
                break;
            case 'student':
                $view = 'Module1.student.profile';
                break;
            case 'admin':
                $view = 'Module1.administrator.profile';
                break;
            default:
                abort(403, 'Unauthorized');
        }

        return view($view, compact('user'));
    }

    public function updateProfile(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

}
