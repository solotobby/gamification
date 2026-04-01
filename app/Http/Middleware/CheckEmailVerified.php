<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && is_null(auth()->user()->email_verified_at)) {
            // Allow access to verification routes
            if ($request->routeIs('verification.*')) {
                return $next($request);
            }

            return response()->view('auth.email_verification');
        }

        return $next($request);
    }
}
