<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected static function view(string $route): \Illuminate\View\View
    {
        if(Auth::user()->is_seller)
            return view('seller.' . $route);
        return view('client.' . $route);
    }
}
