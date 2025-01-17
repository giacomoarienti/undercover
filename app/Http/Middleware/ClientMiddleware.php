<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user && !$user->is_seller) {
            return $next($request);
        }

        return redirect()
            ->route($user ? 'index' : 'auth.signin')
            ->with('error', 'You cannot access this page.');
    }
}
