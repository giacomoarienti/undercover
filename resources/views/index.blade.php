@php
    /** @var \App\Models\User $user */
    $user = Illuminate\Support\Facades\Auth::user();
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="d-none col p-3 pe-0 d-md-flex justify-content-center align-items-center">
                    <img src="{{ Storage::url('public/logo.svg') }}" alt="logo" class="img-fluid" style="max-height: 35vh;">
                </div>
                <div class="col-12 col-md-9">
                    <div class="card-body d-flex flex-column h-100 justify-content-center align-items-start">
                        <h1 class="card-title">
                            The perfect cover
                        </h1>
                        <h1 class="card-subtitle h3 mb-3">
                            for your every secret.
                        </h1>
                        <div class="card-text">
                            Undercover is your go-to destination for unique, high-quality covers. We combine exclusive designs and reliable protection to bring you accessories that reflect your style while safeguarding your devices. Express your personality, without compromise: <em>we've got you covered.</em>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @if(!$user)
                <div class="col-12-sm col-md-6">
                    <a class="d-flex justify-content-center p-5 bg-primary text-white text-decoration-none h3 text-center rounded"
                       href="{{ route('products.index') }}">
                        Browse products
                    </a>
                </div>

                <div class="col-12-sm col-md-6">
                    <a class="d-flex justify-content-center p-5 bg-primary text-white text-decoration-none h3 text-center rounded"
                       href="{{ route('auth.signin') }}">
                        Sign in
                    </a>
                </div>
            @else
                @if($user->is_seller)
                    <div class="col-12 col-md-6">
                        <a class="d-flex justify-content-center p-5 bg-primary text-white text-decoration-none h3 text-center rounded"
                           href="{{ route('products.index') }}">
                            Products
                        </a>
                    </div>

                    <div class="col-12 col-md-6">
                        <a class="d-flex justify-content-center p-5 bg-primary text-white text-decoration-none h3 text-center rounded"
                           href="{{ route('products.create') }}">
                            New Product
                        </a>
                    </div>

                    <div class="col-12 col-md-6">
                        <a class="d-flex justify-content-center p-5 bg-primary text-white text-decoration-none h3 text-center rounded"
                           href="{{ route('coupons.index') }}">
                            Coupons
                        </a>
                    </div>

                    <div class="col-12 col-md-6">
                        <a class="d-flex justify-content-center p-5 bg-primary text-white text-decoration-none h3 text-center rounded"
                           href="{{ route('orders.index') }}">
                            Orders
                        </a>
                    </div>
                @else
                    <div class="col-12 col-md-6">
                        <a class="d-flex justify-content-center p-5 bg-primary text-white text-decoration-none h3 text-center rounded"
                           href="{{ route('products.index') }}">
                            Shop
                        </a>
                    </div>

                    <div class="col-12 col-md-6">
                        <a class="d-flex justify-content-center p-5 bg-primary text-white text-decoration-none h3 text-center rounded"
                           href="{{ route('orders.index') }}">
                            Orders
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>

@endsection
