@php
    function formatTotal(\App\Models\Order $order): string
    {
        /** @var \App\Models\User $user */
        $user = Illuminate\Support\Facades\Auth::user();

        $total = $user->is_seller ? $order->vendorTotal($user) : $order->total;
        return number_format($total, 2);
    }
@endphp

@extends('layouts.app')

@section('content')

    <h1>Orders</h1>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" aria-label="Orders List">
                    <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Order ID</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orders as $order)
                        <tr data-order-id="{{ $order->id }}" class="order-row" role="button" tabindex="0">
                            <td>{{ $order->created_at->format('Y-m-d') }}</td>
                            <td>{{ $order->id }}</td>
                            <td>{{ formatTotal($order) }}</td>
                            <td>{{ ucfirst($order->status?->value) }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-primary view-order"
                                   title="View order {{ $order->id }} details">
                                    <i class="fa fa-eye"><span class="sr-only">Order {{ $order->id }} details</span></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $orders->links() }}
            </div>

        </div>
    </div>
@endsection
