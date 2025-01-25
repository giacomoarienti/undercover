@extends('layouts.app')

@section('content')
    <h1>Settings</h1>

    <div class="d-flex flex-column gap-4">
        <div class="card">
            <div class="card-header">
                <h2 class="h4 mb-0">Profile</h2>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('settings') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    @method('PATCH')

                    <!-- Personal Information -->
                    <fieldset>
                        <legend class="h5 mb-3">Personal Information</legend>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">First Name</label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       maxlength="255" />
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="surname" class="form-label">Last Name</label>
                                <input type="text"
                                       class="form-control @error('surname') is-invalid @enderror"
                                       id="surname"
                                       name="surname"
                                       value="{{ old('surname', $user->surname) }}"
                                       required
                                       maxlength="255" />
                                @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="birthday" class="form-label">Birthday</label>
                            <input type="date"
                                   class="form-control @error('birthday') is-invalid @enderror"
                                   id="birthday"
                                   name="birthday"
                                   value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}" />
                            @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </fieldset>

                    <!-- Address -->
                    <fieldset class="mt-4">
                        <legend class="h5 mb-3">Address</legend>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="street_name" class="form-label">Street Name</label>
                                <input type="text"
                                       class="form-control @error('street_name') is-invalid @enderror"
                                       id="street_name"
                                       name="street_name"
                                       value="{{ old('street_name', $user->street_name) }}"
                                       maxlength="255" />
                                @error('street_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="street_number" class="form-label">Street Number</label>
                                <input type="text"
                                       class="form-control @error('street_number') is-invalid @enderror"
                                       id="street_number"
                                       name="street_number"
                                       value="{{ old('street_number', $user->street_number) }}"
                                       maxlength="255" />
                                @error('street_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text"
                                       class="form-control @error('city') is-invalid @enderror"
                                       id="city"
                                       name="city"
                                       value="{{ old('city', $user->city) }}"
                                       maxlength="255" />
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State/Province</label>
                                <input type="text"
                                       class="form-control @error('state') is-invalid @enderror"
                                       id="state"
                                       name="state"
                                       value="{{ old('state', $user->state) }}"
                                       maxlength="255" />
                                @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="zip_code" class="form-label">ZIP Code</label>
                                <input type="text"
                                       class="form-control @error('zip_code') is-invalid @enderror"
                                       id="zip_code"
                                       name="zip_code"
                                       value="{{ old('zip_code', $user->zip_code) }}"
                                       maxlength="255" />
                                @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text"
                                       class="form-control @error('country') is-invalid @enderror"
                                       id="country"
                                       name="country"
                                       value="{{ old('country', $user->country) }}"
                                       maxlength="255" />
                                @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>


                    <div class="d-flex justify-content-end mt-4">
                        <button type="reset" class="btn btn-outline-secondary me-2">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="h4 mb-0">Security</h2>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('settings') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    @method('PATCH')

                    <!-- Personal Information -->
                    <fieldset>
                        <legend class="h5 mb-3">Password</legend>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                required
                                minlength="8"
                                maxlength="255" />
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                minlength="8"
                                maxlength="255" />
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </fieldset>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="reset" class="btn btn-outline-secondary me-2">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>

        @if($user->is_seller)
            @include('user.partials.reception_methods')
        @else
            <div id="payment_methods">
                @include('user.partials.payment_methods')
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h2 class="h4 mb-0">Session</h2>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Exit from the current session.</p>
                <a href="{{ route('auth.signout') }}" class="btn btn-danger">Sign Out</a>
            </div>
        </div>
    </div>
@endsection
