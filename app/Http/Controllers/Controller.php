<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected static function resolveView(string $basename): \Illuminate\View\View
    {
        return view((request()->user() && request()->user()->is_seller ? 'seller.' : 'client.') . $basename);
    }
}
