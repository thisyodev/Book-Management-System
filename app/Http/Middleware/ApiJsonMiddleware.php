<?php

namespace App\Http\Middleware;

use Closure;

class ApiJsonMiddleware
{
    public function handle($request, Closure $next)
    {
        Log::info('🔥 ApiJsonMiddleware middleware hit!', [
            'expectsJson' => $request->expectsJson(),
            'headers' => $request->headers->all(),
            'path' => $request->path()
        ]);

        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
