<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Order::class);

        $user = Auth::user();

        $orders = $user->is_vendor ?
            $user->orders():
            Order::whereHas('specificProducts.product', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        return view('orders.index')
                ->with('user', $user)
                ->with('orders', $orders->paginate(10));
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

        if(!$request->user->checkCartAvailability()){
            //TODO->segnalare problema
            return redirect()->route("cart.index");
        }

        $paymentMethod = PaymentMethod::firstWhere('id', $validated['payment_method_id']);
        Gate::authorize('use', $paymentMethod);
        $payment = Payment::create([
            'payment_method_id' => $paymentMethod->id,
        ]);

        $coupon = $validated['coupon_id'] ? Coupon::firstWhere('id', $validated['coupon_id']) : null;

        Order::place($request->user, $payment, $coupon);
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        Gate::authorize('view', $order);
        return view('orders.show')
                ->with('order', $order);
    }
}
