@extends('layouts.app')

@section('content')

<h1>Orders</h1>
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Customer</th>
                <th scope="col">Status</th>
                <th scope="col">Total</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ $order->customer->name }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$orders->links()}}
    </div>
</div>
@endsection
