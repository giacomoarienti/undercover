@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h1>Sign Up</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('auth.signup') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                    <!-- Account Type Selection -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <fieldset class="d-flex gap-3 align-items-center">
                                <legend class="h5">Account Type</legend>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_seller" id="client_type"
                                           value="0"
                                           checked />
                                    <label class="form-check-label" for="client_type">Client</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_seller" id="seller_type"
                                           value="1" />
                                    <label class="form-check-label" for="seller_type">Seller</label>
                                </div>
                            </fieldset>
                        </div>
                    </div>

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
                                       value="{{ old('name') }}"
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
                                       value="{{ old('surname') }}"
                                       required
                                       maxlength="255" />
                                @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required />
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="birthday" class="form-label">Birthday</label>
                            <input type="date"
                                   class="form-control @error('birthday') is-invalid @enderror"
                                   id="birthday"
                                   name="birthday"
                                   value="{{ old('birthday') }}"
                                   required />
                            @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </fieldset>

                    <!-- Seller Information -->
                    <fieldset id="seller-fields" class="mt-4 d-none">
                        <legend class="h5 mb-3">Seller's Information</legend>

                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   id="company_name"
                                   name="company_name"
                                   value="{{ old('company_name') }}"
                                   maxlength="255" />
                            @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="vat" class="form-label">VAT Number</label>
                            <input type="text"
                                   class="form-control @error('vat') is-invalid @enderror"
                                   id="vat"
                                   name="vat"
                                   value="{{ old('vat') }}"
                                   maxlength="255" />
                            @error('vat')
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
                                       value="{{ old('street_name') }}"
                                       required
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
                                       value="{{ old('street_number') }}"
                                       required
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
                                       value="{{ old('city') }}"
                                       required
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
                                       value="{{ old('state') }}"
                                       required
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
                                       value="{{ old('zip_code') }}"
                                       required
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
                                       value="{{ old('country') }}"
                                       required
                                       maxlength="255" />
                                @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>

                    <!-- Password -->
                    <fieldset class="mt-4">
                        <legend class="h5 mb-3">Security</legend>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required
                                   minlength="6" />
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   minlength="6" />
                        </div>
                    </fieldset>

                    <div class="row justify-content-center  mt-4">
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <p class="mb-0">Already have an account? <a href="{{ route('auth.signin') }}"
                                                                    class="text-decoration-none">Sign in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sellerType = document.getElementById('seller_type');
                const clientType = document.getElementById('client_type');

                function toggleSellerFields(isSeller = false) {
                    const sellerSection = document.getElementById('seller-fields');

                    // Toggle visibility of seller section
                    sellerSection.classList.toggle('d-none', !isSeller);

                    // Toggle required attribute on seller fields
                    sellerSection.querySelectorAll('input').forEach(input => input.required = isSeller);
                }

                // Add change event listener to all account type radio inputs
                sellerType.addEventListener('change', () => toggleSellerFields(true));
                clientType.addEventListener('change', () => toggleSellerFields(false));

                // Initial state setup
                toggleSellerFields();
            });
        </script>
    @endpush
@endsection
