<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Models\ReceptionMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentMethodController extends Controller
{
    public function store(PaymentMethodRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $paymentMethod = new PaymentMethod($request->validated());
        $paymentMethod->user_id = $user->id;
        $paymentMethod->save();

        // if it's the first method
        if ($user->paymentMethods()->count() == 0) {
            $user->payment_method_id = $paymentMethod->id;
            $user->save();
        }

        return to_route('settings')->with('message', 'Payment method created');
    }

    public function edit(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'id' => 'required|integer',
            'default' => 'boolean'
        ]);
        $paymentMethod = $user->paymentMethods()->find($request->get('id'));

        if (!$paymentMethod) {
            return to_route('settings')->withErrors(['id' => 'Payment method not found']);
        }

        if($request->has('default')) {
            $user->payment_method_id = $paymentMethod->id;
            $user->save();
        }

        return to_route('settings')->with('message', 'Payment set as default');
    }


    public function delete(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'id' => 'required|integer',
        ]);

        $paymentMethod = $user->paymentMethods()->find($request->get('id'));
        if($paymentMethod->default) {
            return to_route('settings')->withErrors(['id' => 'Cannot delete default payment method']);
        }

        $paymentMethod = $user->paymentMethods()->find($request->get('id'));
        if (!$paymentMethod) {
            return to_route('settings')->withErrors(['id' => 'Payment method not found']);
        }
        $paymentMethod->delete();

        return to_route('settings')->with('success', 'Payment method deleted successfully');
    }
}
