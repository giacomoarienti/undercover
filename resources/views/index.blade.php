@php
    /** @var \App\Models\User $user */
    $user = Illuminate\Support\Facades\Auth::user();
@endphp

@extends('layouts.app')

@section('content')
    @if(!$user)
        <div class="container">
            <div class="row gap-3">
                <a class="col-sm bg-primary text-bg-primary p-5 text-center text-decoration-none h3"
                   href="{{ route('products.index') }}">
                    Shop
                </a>

                <a class="col-sm bg-primary text-bg-primary p-5 text-center text-decoration-none h3"
                   href="{{ route('auth.signin') }}">
                    Sign in
                </a>
            </div>
        </div>
    @else
        @if($user->is_seller)
            <div class="container">
                <div class="row gap-3">
                    <a class="col-sm bg-primary text-bg-primary p-5 text-center text-decoration-none h3"
                       href="{{ route('products.index') }}">
                        Products
                    </a>

                    <a class="col-sm bg-primary text-bg-primary p-5 text-center text-decoration-none h3"
                       href="{{ route('coupons.index') }}">
                        Coupons
                    </a>

                    <a class="col-sm bg-primary text-bg-primary p-5 text-center text-decoration-none h3"
                       href="{{ route('orders.index') }}">
                        Orders
                    </a>
                </div>
            </div>
        @else
            <div class="container">
                <div class="row gap-3">
                    <a class="col-sm bg-primary text-bg-primary p-5 text-center text-decoration-none h3"
                       href="{{ route('products.index') }}">
                        Shop
                    </a>

                    <a class="col-sm bg-primary text-bg-primary p-5 text-center text-decoration-none h3"
                       href="{{ route('orders.index') }}">
                        Orders
                    </a>
                </div>
            </div>
        @endif
    @endif
@endsection
