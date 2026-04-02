<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Blacklisted user
            if ($user->is_blacklisted) {
                Auth::logout();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been blocked.']);
            }

            // Email not verified
            if (is_null($user->email_verified_at)) {

                // Allow verification routes
                if ($request->routeIs('verification.*')) {
                    return $next($request);
                }

                return response()->view('auth.email_verification');
            }
        }

        return $next($request);
    }
}
