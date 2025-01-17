<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserController extends Controller
{
    public function settings()
    {
        /** @var User $user */
        $user = Auth::user();

        $data = ["user" => $user];
        if($user->is_seller) {
            $data["reception_methods"] = $user->receptionMethods;
        } else {
            $data["payment_methods"] = $user->paymentMethods;
        }

        return view('user.settings', $data);
    }

    public function edit(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'surname' => 'string|max:255',
            'password' => 'string|min:6|confirmed',
            'birthday' => 'date',
            'street_name' => 'string|max:255',
            'street_number' => 'string|max:255',
            'city' => 'string|max:255',
            'state' => 'string|max:255',
            'zip_code' => 'string|max:255',
            'country' => 'string|max:255',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if ($user->is_seller) {
            $request->validate([
                'is_seller' => 'boolean',
                'company_name' => 'string|max:255',
                'vat' => 'string|max:255',
            ]);
        }

        $user = $user->fill($request->data());
        if($request->has('password')) {
            $user->password = Hash::make($request->get('password'));
        }
        $user->save();

        return to_route('settings');
    }
}
