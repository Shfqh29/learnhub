<?php

namespace App\Http\Controllers\Module1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use App\Models\Student;


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
        $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        ]);

        // 1️⃣ Create teacher with random password
        $teacher = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(10)),
            'role' => 'teacher',      // if you use roles
            'status' => 'Active',
        ]);

        // 2️⃣ Send password reset link to teacher
        Password::sendResetLink([
            'email' => $teacher->email
        ]);

        return redirect()
            ->route('administrator.teacherslist')
            ->with('success', 'Teacher registered successfully. Password setup email sent.');
    }

    // Show edit form
    public function editTeacher($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        return view('Module1.administrator.editteacher', compact('teacher'));
    }

    // Update teacher
    public function updateTeacher(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required'
        ]);

        $teacher = User::where('role', 'teacher')->findOrFail($id);

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('administrator.teacherslist')
            ->with('success', 'Teacher updated successfully');
    }

    public function toggleStatus($id)
    {
        $teacher = User::findOrFail($id);

        $teacher->status = $teacher->status === 'Active'
            ? 'Inactive'
            : 'Active';

        $teacher->save();

        return redirect()->back()->with('success', 'Teacher status updated successfully.');
    }

    public function showStudentsList()
    {
        $students = User::where('role', 'student')->get();

        return view('Module1.administrator.studentslist', compact('students'));
    }

    public function editStudent($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        return view('Module1.administrator.editstudent', compact('student'));
    }

    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Inactive',
        ]);

        $student = User::where('role', 'student')->findOrFail($id);

        $student->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('administrator.students.index')
            ->with('success', 'Student status updated successfully');
    }

    public function toggleStudentStatus($id)
    {
        $student = User::findOrFail($id);

        // Optional: make sure the user is actually a student
        if ($student->role !== 'student') {
            return redirect()->back()->with('error', 'Only student accounts can be updated.');
        }

        // Toggle Active/Inactive status
        $student->status = $student->status === 'Active' ? 'Inactive' : 'Active';
        $student->save();

        return redirect()->back()->with('success', 'Student status updated successfully.');
    }



}
