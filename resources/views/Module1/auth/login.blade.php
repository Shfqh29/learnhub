@extends('Module1.auth.layout')

@section('title', 'Login - LearnHub')

@section('content')
<div class="form-box">
    <h2>Login</h2>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label>EMAIL</label>
            <input type="email" name="email" required placeholder="Enter email">
        </div>

        {{-- Password --}}
        <div class="form-group password-wrapper">
            <label>PASSWORD</label>
            <div class="input-with-icon">
                <input type="password" name="password" id="login-password" required placeholder="Enter password">
                <span class="toggle-password" onclick="togglePassword('login-password')">
                    <!-- Eye SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17.94 17.94L6.06 6.06M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </span>
            </div>
        </div>
    @if ($errors->any())
        <div class="error-message" style="color: red; margin-bottom: 10px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

        {{-- Links --}}
        <div class="login-links">
            <a href="{{ route('password.request') }}">Forgot Password?</a>
            <span> | </span>
            <a href="{{ route('register') }}">Sign Up</a>
        </div>

        {{-- Submit --}}
        <button type="submit">LOGIN</button>
    </form>
</div>

{{-- Toggle password JS (same style as register) --}}
<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const svg = field.nextElementSibling.querySelector('svg');

    if (field.type === 'password') {
        field.type = 'text';
        svg.innerHTML = `
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
    } else {
        field.type = 'password';
        svg.innerHTML = `
            <path d="M17.94 17.94L6.06 6.06M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        `;
    }
}
</script>
@endsection
