<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run in debug mode or local environment
        if (config('app.debug') || app()->environment('local')) {
            $user = auth()->user();
            
            Log::info('Authentication Debug', [
                'url' => $request->url(),
                'method' => $request->method(),
                'authenticated' => auth()->check(),
                'user_id' => $user?->id,
                'user_email' => $user?->email,
                'user_role' => $user?->role?->name,
                'user_permissions' => $user?->role?->permissions,
                'session_id' => session()->getId(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $next($request);
    }
}
