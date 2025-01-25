<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function signin(Request $request): RedirectResponse
    {
        Log::info('signin');
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to log the client in
        $user = User::where('email', $request->get('email'))->first();

        if ($user && Hash::check($request->get('password'), $user->password)) {
            // Log the client in
            Auth::login($user);
            return to_route('index')->with('message', 'Logged in successfully.');
        }

        // If authentication fails, redirect back with an error message
        return to_route('auth.signin')->withErrors([
            'email' => 'Invalid email or password',
            'password' => 'Invalid email or password'
        ]);
    }

    public function signup(Request $request): RedirectResponse
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

        if($request->has('is_seller') && $request->get('is_seller', false)) {
            $request->validate([
                'is_seller' => 'required|boolean',
                'company_name' => 'required|string|max:255',
                'vat' => 'required|string|max:255',
            ]);
        }

        if(User::where('email', $request->get('email'))->exists()) {
            return to_route('auth.signup')->withErrors(['email' => 'Email already exists']);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'surname' => $request->get('surname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'birthday' => $request->get('birthday'),
            'street_name' => $request->get('street_name'),
            'street_number' => $request->get('street_number'),
            'city' => $request->get('city'),
            'state' => $request->get('state'),
            'zip_code' => $request->get('zip_code'),
            'country' => $request->get('country'),
            'is_seller' => $request->get('is_seller', false),
            'company_name' => $request->get('company_name'),
            'vat' => $request->get('vat'),
        ]);

        Auth::login($user);
        return to_route('index')->with('message', 'Registered successfully.');
    }

    public function signout(): RedirectResponse
    {
        Auth::logout();
        return to_route('auth.signin')->with('message', 'Logged out.');
    }
}
