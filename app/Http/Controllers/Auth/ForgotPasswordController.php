<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // Show email form
    public function showLinkRequestForm()
    {
        return view('Module1.auth.forgot-password');
    }

    // Send reset link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        $resetLink = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

        Mail::raw(
            "Click the link to reset your password:\n\n$resetLink",
            function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Reset Password - LearnHub');
            }
        );

        return back()->with('success', 'Password reset link has been sent to your email.');
    }

    // Show reset form
    public function showResetForm(Request $request, $token)
    {
        return view('Module1.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Update password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Invalid or expired token']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password updated successfully. Please login.');
    }
}
