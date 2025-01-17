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

        $receptionMethod = new ReceptionMethod($request->validated());
        $receptionMethod->user_id = $user->id;
        $receptionMethod->save();

        return to_route('user.settings')->with('message', 'Reception method created');
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
            return to_route('user.settings')->withErrors(['id' => 'Payment method not found']);
        }

        $paymentMethod->update($request->validated());

        return to_route('user.settings')->with('message', 'Reception method created');
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
            return to_route('user.settings')->withErrors(['id' => 'Payment method not found']);
        }
        $paymentMethod->delete();

        return to_route('user.settings')->with('success', 'Payment method deleted successfully');
    }
}
