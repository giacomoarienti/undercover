<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Order::class);
        return view('orders.index')
                ->with('user', $request->user)
                ->with('orders', $request->user->orders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Order::class);

        return view('orders.create')
                ->with('user', $request->user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Order::class);
        $validated = $request->validate([
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'coupon_id' => 'nullable|integer|exists:coupons,id',
        ]);

        $paymentMethod = PaymentMethod::firstWhere('id', $validated['payment_method_id']);
        Gate::authorize('use', $paymentMethod);
        Payment::create([

        ]);

        $coupon = $validated['coupon_id'] ? Coupon::firstWhere('id', $validated['coupon_id']) : null;

        Order::forUser($request->user, $coupon);
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        Gate::authorize('view', $order);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        Gate::authorize('update', $order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        Gate::authorize('update', $order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        Gate::authorize('delete', $order);
    }
}
