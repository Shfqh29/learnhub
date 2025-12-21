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

{{-- Scripts --}}
<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const svg = document.querySelector(`#${fieldId} ~ .toggle-password svg`);

    if (field.type === 'password') {
        field.type = 'text';
        svg.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `; // normal eye
    } else {
        field.type = 'password';
        svg.innerHTML = `
            <path d="M17.94 17.94L6.06 6.06M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `; // eye-slash version
    }
}

// Elements
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('password_confirmation');
const confirmMessage = document.getElementById('confirm-message');
const registerButton = document.getElementById('register-button');
const passwordDialog = document.getElementById('password-dialog');

// Show/hide password dialog
passwordInput.addEventListener('focus', () => passwordDialog.classList.add('show'));
passwordInput.addEventListener('blur', () => {
    if (!isPasswordValid()) passwordDialog.classList.remove('show');
});

// Real-time password validation
passwordInput.addEventListener('input', () => {
    const value = passwordInput.value;
    const rules = {
        'rule-length': value.length >= 8,
        'rule-uppercase': /[A-Z]/.test(value),
        'rule-lowercase': /[a-z]/.test(value),
        'rule-number': /[0-9]/.test(value),
        'rule-symbol': /[@$!%*#?&]/.test(value),
    };
    Object.keys(rules).forEach(rule => {
        document.getElementById(rule).style.color = rules[rule] ? 'green' : 'red';
    });

    if (isPasswordValid()) passwordDialog.classList.remove('show');
    else passwordDialog.classList.add('show');

    checkConfirmPassword();
    validateForm();
});

// Confirm password real-time check
confirmInput.addEventListener('input', () => {
    checkConfirmPassword();
    validateForm();
});

function checkConfirmPassword() {
    const password = passwordInput.value;
    const confirm = confirmInput.value;

    if (confirm !== '') {
        if (password === confirm) {
            confirmMessage.textContent = 'Passwords match';
            confirmMessage.style.color = 'green';
            if (isPasswordValid()) confirmMessage.style.display = 'none';
            else confirmMessage.style.display = 'block';
        } else {
            confirmMessage.textContent = 'Passwords do not match';
            confirmMessage.style.color = 'red';
            confirmMessage.style.display = 'block';
        }
    } else {
        confirmMessage.style.display = 'none';
    }
}

function isPasswordValid() {
    const value = passwordInput.value;
    return (
        value.length >= 8 &&
        /[A-Z]/.test(value) &&
        /[a-z]/.test(value) &&
        /[0-9]/.test(value) &&
        /[@$!%*#?&]/.test(value)
    );
}

function validateForm() {
    registerButton.disabled = !(isPasswordValid() && passwordInput.value === confirmInput.value);
}
</script>
@endsection
