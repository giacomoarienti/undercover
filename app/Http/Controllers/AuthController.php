<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function signin(Request $request)
    {
        Log::info('signin');
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to log the user in
        $user = User::where('email', $request->get('email'))->first();

        if ($user && Hash::check($request->get('password'), $user->password)) {
            // Log the user in
            Auth::login($user);

            return redirect()->intended('user.home')->with('success', 'Logged in.');
        }

        // If authentication fails, redirect back with an error message
        return back()->withErrors([
            'email' => 'Invalid email or password',
            'password' => 'Invalid email or password'
        ]);
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'birthday' => 'required|date',
            'street_name' => 'required|string|max:255',
            'street_number' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        if($request->has('is_vendor')) {
            $request->validate([
                'is_vendor' => 'required|boolean',
                'company_name' => 'required|string|max:255',
                'vat' => 'required|string|max:255',
            ]);
        }

        if(User::where('email', $request->email)->exists()) {
            return back()->withErrors(['email' => 'Email already exists']);
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthday' => $request->birthday,
            'street_name' => $request->street_name,
            'street_number' => $request->street_number,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'is_vendor' => $request->is_vendor ?? false,
            'company_name' => $request->company_name ?? null,
            'vat' => $request->vat ?? null,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registered successfully.');
    }


}
