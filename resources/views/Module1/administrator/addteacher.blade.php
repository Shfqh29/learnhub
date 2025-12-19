@extends('Module1.administrator.layout')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h2>Add Teacher</h2>

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    <form action="{{ route('administrator.addteacher.post') }}" method="POST">
        @csrf

        {{-- Name --}}
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            @error('email')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
            @error('password_confirmation')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit">Add Teacher</button>
    </form>
</div>
@endsection
