<?php

namespace App\Http\Controllers\Module1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}
