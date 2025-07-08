<?php


namespace App\Http\Middleware;

use Illuminate\Support\Facades\Log;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;
use Closure;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        Log::info('Authenticate redirectTo called', [
            'expectsJson' => $request->expectsJson(),
            'path' => $request->path(),
            'headers' => $request->headers->all(),
        ]);
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        Log::info('🔥 Authenticate middleware hit!', [
            'expectsJson' => $request->expectsJson(),
            'headers' => $request->headers->all(),
            'path' => $request->path()
        ]);
        Log::info('Authenticate handle start', ['guards' => $guards]);
        try {
            return parent::handle($request, $next, ...$guards);
        } catch (AuthenticationException $e) {
            Log::info('Authenticate caught AuthenticationException');
            throw $e;
        }
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        Log::info('Authenticate unauthenticated triggered', [
            'expectsJson' => $request->expectsJson(),
            'path' => $request->path(),
            'headers' => $request->headers->all(),
        ]);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['error' => 'Unauthenticated. Token may be missing or expired.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
