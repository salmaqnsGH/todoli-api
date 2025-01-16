<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerifiedMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->user() || ! $request->user()->hasVerifiedEmail()) {
            return jsonresUnauthorized($request, 'Your account is not verified');
        }

        return $next($request);
    }
}
