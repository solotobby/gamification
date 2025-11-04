<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $apiKey = $request->header('X-API-KEY');

        // if (!$apiKey || !\App\Models\User::where('api_key', $apiKey)->exists()) {
        //     return response()->json(['message' => 'Unauthorized access'.$apiKey], 401);
        // }

        $header = $request->header('Authorization');

        $key = null;

        if ($header && str_starts_with($header, 'Bearer ')) {
            $key = substr($header, 7);
        } elseif ($request->query('api_key')) {
            $key = $request->query('api_key');
        }

        if (! $key || ! User::where('api_key', $key)->exists()) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 401);
        }

        $request->attributes->set('api_key', User::where('is_business', true)->where('api_key', $key)->first());


        return $next($request);


    }
}
