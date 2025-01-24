@extends('layouts.app')

@push('scripts')
    @vite('resources/js/views/order.create.js')
@endpush

@section('content')
    <h1>Checkout</h1>
    <div class="card">
        <div class="card-body border-bottom">
            <h1 class="h5">Payment method</h1>
            @if($paymentMethod)
                <div class="card mb-2">
                    <div class="card-body">
                        @if($paymentMethod->type === 'card')
                            <div>
                                <div class="card-title fw-bold">Credit Card</div>
                                <div class="card-text text-muted">{{ $paymentMethod->card_number }}</div>
                                <div class="card-text text-muted small">Expires: {{ $paymentMethod->card_expiration_date }}</div>
                            </div>
                        @else
                            <div>
                                <div class="card-title fw-bold">PayPal</div>
                                <div class="card-text text-muted">{{ $paymentMethod->paypal_email }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <a href="{{ route('settings') }}" type="button" class="btn btn-primary w-100 text-bg-primary text-decoration-none" >Change the default payment method</a>
            @else
                <a href="{{ route('settings') }}" type="button" class="btn btn-primary w-100 text-bg-primary text-decoration-none">Add a default payment method</a>
            @endif
        </div>
        <div class="card-body border-bottom">
            <h1 class="h5">Billing address</h1>
            <fieldset disabled>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="streetName" class="form-label">Street</label>
                        <input
                            type="text"
                            id="streetName"
                            class="form-control"
                            value="{{$user->street_name}}"
                            readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="streetNumber" class="form-label">Number</label>
                        <input
                            type="text"
                            id="streetNumber"
                            class="form-control"
                            value="{{$user->street_number}}"
                            readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input
                            type="text"
                            id="city"
                            class="form-control"
                            value="{{$user->city}}"
                            readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="zipCode" class="form-label">Postal Code</label>
                        <input
                            type="text"
                            id="zipCode"
                            class="form-control"
                            value="{{$user->zip_code}}"
                            readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="state" class="form-label">Region</label>
                        <input
                            type="text"
                            id="state"
                            class="form-control"
                            value="{{$user->state}}"
                            readonly>
                    </div>
                </div>
                <div>
                    <label for="country" class="form-label">Country</label>
                    <input
                        type="text"
                        id="country"
                        class="form-control"
                        value="Italy"
                        readonly>
                </div>
            </fieldset>
        </div>
        <div class="card-body border-bottom">
            <h1 class="h5">Shipping address</h1>
            <fieldset disabled>
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="address" class="form-label">Street</label>
                        <input
                            type="text"
                            id="address"
                            class="form-control"
                            value="Via dell'Università"
                            readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="number" class="form-label">Number</label>
                        <input
                            type="text"
                            id="number"
                            class="form-control"
                            value="50"
                            readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input
                            type="text"
                            id="city"
                            class="form-control"
                            value="Cesena"
                            readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="postalCode" class="form-label">Postal Code</label>
                        <input
                            type="text"
                            id="postalCode"
                            class="form-control"
                            value="47522"
                            readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="region" class="form-label">Region</label>
                        <input
                            type="text"
                            id="region"
                            class="form-control"
                            value="FC"
                            readonly>
                    </div>
                </div>
                <div>
                    <label for="country" class="form-label">Country</label>
                    <input
                        type="text"
                        id="country"
                        class="form-control"
                        value="Italy"
                        readonly>
                </div>
            </fieldset>
        </div>
        <div class="card-body border-bottom">
            <label for="coupon_code" class="h5">Coupon</label>
            <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    id="coupon_code"
                    placeholder="Enter your coupon code">
                <button
                    type="button"
                    class="btn btn-primary"
                    id="apply_coupon"
                    onclick="applyCoupon()">
                    Apply
                </button>
            </div>
            <small id="coupon_help" class="form-text text-muted ms-1">
                Enter a valid coupon code to get a discount.
            </small>
        </div>

        <form id="form" action="{{route('orders.store')}}" method="POST">
        @csrf
            <input type="hidden" name="payment_method_id" value="{{$paymentMethod?->id ?? ''}}"/>
            <input id="coupon_id" type="hidden" name="coupon_id" value=""/>
            <div class="card-body form-group d-flex flex-row">
                <button type="button" class="btn btn-danger w-100 me-2" onclick="location.href='{{ url()->previous() }}'">Abort</button>
                <button type="submit" class="btn btn-primary w-100 ms-2">Place order</button>
            </div>
        </form>
    </div>


@endsection
