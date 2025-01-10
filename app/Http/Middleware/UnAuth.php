<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UnAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('UnAuth middleware');
        if (auth()->check()) {
            if(auth()->user()->is_seller) {
                return redirect()->route('seller.index');
            }
            return redirect()->route('client.index');
        }
        return $next($request);
    }
}
