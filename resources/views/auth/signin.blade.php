@extends('layouts.app')

@section('content')
    <h1>Sign In</h1>

    <form method="POST" action="{{ route('auth.signin') }}">
        @csrf
        <div class="form-group has-validation">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Sign In</button>
        </div>
    </form>
@endsection
