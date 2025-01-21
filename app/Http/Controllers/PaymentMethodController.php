<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Models\ReceptionMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    public function store(PaymentMethodRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $paymentMethod = new PaymentMethod($request->validated());
        $paymentMethod->user_id = $user->id;
        $paymentMethod->save();

        return to_route('settings')->with('message', 'Payment method created');
    }

    public function edit(PaymentMethodRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'id' => 'required|integer',
        ]);
        $paymentMethod = $user->paymentMethods()->find($request->get('id'));

        if (!$paymentMethod) {
            return to_route('settings')->withErrors(['id' => 'Payment method not found']);
        }

        $paymentMethod->update($request->validated());

        return to_route('settings')->with('message', 'Payment method created');
    }


    public function delete(Request $request, PaymentMethod $paymentMethod)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'id' => 'required|integer',
        ]);

        $paymentMethod = $user->paymentMethods()->find($request->get('id'));
        if (!$paymentMethod) {
            return to_route('settings')->withErrors(['id' => 'Payment method not found']);
        }
        $paymentMethod->delete();

        return to_route('settings')->with('success', 'Payment method deleted successfully');
    }
}
