@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h1 class="card-title mb-0">Order #{{ $order->id }} - {{ ucfirst($order->status->value) }}</h1>
            </div>
            <div class="card-body">
                <section class="border-bottom pb-3 mb-3">
                    <h2 class="h3 text-muted mb-2">Shipping Address</h2>
                    <p class="mb-0">{{ $order->user->full_address }}</p>
                </section>

                <section class="row border-bottom pb-3 mb-3">
                    @if($order->payment)
                        @php
                            $paymentMethod = $order->payment->paymentMethod;
                        @endphp
                        <div class="col-md-6 mb-3 mb-sm-0">
                            <h2 class="h2 text-muted mb-2">Payment Details</h2>
                            <p class="mb-1">
                                Method: {{ ucfirst($paymentMethod->type) }}
                                @switch($paymentMethod->type)
                                    @case('paypal')
                                        ({{ $paymentMethod->paypal_email }})
                                        @break
                                    @case('card')
                                        (Ending in {{ substr($paymentMethod->card_number, -4) }})
                                        @break
                                @endswitch
                            </p>
                            <p class="mb-1">
                                Transaction id:
                                {{ $order->payment->transaction_id }}
                            </p>
                            <p class="mb-1">
                                Status:
                                {{ $order->payment->paymentStatus->name }}
                            </p>
                        </div>
                    @endif

                    <div class="col-md-6">
                        <h2 class="h3 text-muted mb-2">Shipping Information</h2>
                        @if($order->shipping)
                            <p class="mb-1">Method: {{ $order->shipping->shipping_company }}</p>
                            <p class="mb-0">Status: {{ $order->shipping->status }}</p>
                        @else
                            <p class="mb-0">Status: Processing</p>
                        @endif
                    </div>
                </section>

                <section class="mb-3">
                    <h2 class="h3 text-muted mb-3">Order Summary</h2>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-light">
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $products = $user->is_seller ? $order->sellerSpecificProducts($user) : $order->specificProducts;
                            @endphp
                            @foreach ($products as $item)
                                <tr>
                                    <td>{{ $item->product->name }} - {{ $item->color->name }}</td>
                                    <td>{{ $item->pivot->quantity }}</td>
                                    <td>{{ $item->product->price }} &euro;</td>
                                    <td>{{ $item->pivot->quantity * $item->product->price }} &euro;</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"></td>
                                <td><strong>Subtotal:</strong></td>
                                <td>
                                    <strong>
                                        @if($user->is_seller)
                                            {{ $order->vendorTotalBeforeDiscount($user) }} &euro;
                                        @else
                                            {{ $order->total_before_discount }} &euro;
                                        @endif
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>Discount:</td>
                                <td>
                                    @if($user->is_seller)
                                        -{{ number_format($order->vendorDiscount($user), 2) }} &euro;
                                    @else
                                        -{{ number_format($order->discount, 2) }} &euro;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td><strong>Total</strong>:</td>
                                <td>
                                    <strong>
                                        @if($user->is_seller)
                                            {{ number_format($order->vendorTotal($user), 2) }} &euro;
                                        @else
                                            {{ number_format($order->total, 2) }} &euro;
                                        @endif
                                    </strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
