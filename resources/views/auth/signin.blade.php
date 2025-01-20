@extends('layouts.app')

@section('content')
    <h1>Sign In</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('auth.signin') }}">
                @csrf
                <div class="form-group has-validation">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                           id="email"
                           placeholder="Email" value="{{ old('email') }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                           id="password" placeholder="Password" required>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>


                <div class="row justify-content-center  mt-4">
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary w-100">Sign In</button>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <p class="mb-0">Don't have an account? <a href="{{ route('auth.signup') }}" class="text-decoration-none">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>
@endsection
