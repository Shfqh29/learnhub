@extends('Module1.auth.layout')

@section('title', 'Register - LearnHub')

@section('content')
<div class="form-box">
    <h2>Student Registration</h2>

    <form action="{{ route('register.post') }}" method="POST">
        @csrf

        {{-- Name --}}
        <div class="form-group">
            <label>NAME</label>
            <input type="text" name="name" value="{{ old('name') }}" required 
                   oninput="this.value = this.value.toUpperCase()">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label>EMAIL</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Form Level --}}
        <div class="form-group">
            <label>FORM</label>
            <select name="form" required>
                <option value="">-- Select Form --</option>
                <option value="1" {{ old('form') == '1' ? 'selected' : '' }}>Form 1</option>
                <option value="2" {{ old('form') == '2' ? 'selected' : '' }}>Form 2</option>
                <option value="3" {{ old('form') == '3' ? 'selected' : '' }}>Form 3</option>
                <option value="4" {{ old('form') == '4' ? 'selected' : '' }}>Form 4</option>
                <option value="5" {{ old('form') == '5' ? 'selected' : '' }}>Form 5</option>
            </select>
            @error('form')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>


        {{-- Password --}}
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
                <!-- Eye SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94L6.06 6.06M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </span>
            </div>

            {{-- Floating Password Rules Dialog --}}
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
                <!-- Eye SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94L6.06 6.06M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </span>
            </div>
            <span id="confirm-message"></span>
            @error('password_confirmation')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit Button --}}
        <button type="submit" id="register-button" disabled>REGISTER</button>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </form>
</div>
@endsection
