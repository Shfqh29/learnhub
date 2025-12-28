@extends('Module1.auth.layout')

@section('title', 'Reset Password - LearnHub')

@section('content')
<div class="form-box">
    <h2>Reset Password</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- New Password --}}
        <div class="form-group password-wrapper">
            <label>PASSWORD
                <span class="tooltip">!
                    <span class="tooltip-text">
                        Password must be at least 8 characters and contain uppercase, lowercase, number, and symbol.
                    </span>
                </span>
            </label>

            <div class="input-with-icon">
                <input type="password" name="password" id="password" required>
                <span class="toggle-password" onclick="togglePassword('password')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94L6.06 6.06M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </span>
            </div>

            {{-- Password Rules Dialog --}}
            <div id="password-dialog">
                <strong>Password must contain:</strong>
                <ul>
                    <li id="rule-length">Minimum 8 characters</li>
                    <li id="rule-uppercase">At least one uppercase letter</li>
                    <li id="rule-lowercase">At least one lowercase letter</li>
                    <li id="rule-number">At least one number</li>
                    <li id="rule-symbol">At least one symbol (@$!%*#?&)</li>
                </ul>
            </div>

            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="form-group password-wrapper">
            <label>CONFIRM PASSWORD</label>

            <div class="input-with-icon">
                <input type="password" name="password_confirmation" id="password_confirmation" required>
                <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94L6.06 6.06M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </span>
            </div>

            <span id="confirm-message"></span>
        </div>

        {{-- Submit --}}
        <button type="submit" id="reset-button" disabled>RESET PASSWORD</button>

        <div class="login-link">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </form>
</div>
@endsection
