<?php

namespace App\Http\Controllers\Module1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    // List all teachers
    public function showTeachersList()
    {
        // Get all users with role 'teacher'
        $teachers = User::where('role', 'teacher')->get();

        // Pass to view
        return view('Module1.administrator.teacherslist', compact('teachers'));
    }

    // Show the Add Teacher form
    public function showAddTeacherForm()
    {
        return view('Module1.administrator.addteacher');
    }

    // Handle Add Teacher form submission
    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'form' => null, // teacher does not have form
            'password' => Hash::make($validated['password']),
            'role' => 'teacher',      // automatically set role
            'status' => 'Active',     // active by default
        ]);

        return redirect()->route('administrator.teacherslist')
                         ->with('success', 'Teacher added successfully!');
    }
}
