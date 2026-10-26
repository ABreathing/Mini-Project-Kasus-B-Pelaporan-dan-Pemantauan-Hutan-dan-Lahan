@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="card card-narrow">
        <h1>Login</h1>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror

            <button type="submit" class="btn">Login</button>
        </form>
    </div>
@endsection