@extends('Module1.auth.layout')

@section('title', 'Forgot Password - LearnHub')

@section('content')
<div class="form-box">
    <h2>Forgot Password</h2>

    @if(session('success'))
        <p style="color: green; text-align: center; margin-bottom: 12px;">
            {{ session('success') }}
        </p>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label>EMAIL</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                placeholder="Enter your registered email"
            >
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit">SEND RESET LINK</button>

        {{-- Back to Login --}}
        <div class="login-link">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </form>
</div>
@endsection
