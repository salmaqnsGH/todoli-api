<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ExtendTokenExpirationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            // Get current token
            $token = $request->user()->currentAccessToken();

            // Extend expiration time
            $token->expires_at = now()->addMinutes(config('sanctum.expiration'));
            $token->save();
        }

        return $next($request);
    }
}
