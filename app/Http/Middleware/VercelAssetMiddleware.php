<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VercelAssetMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS and proper URL for Vercel
        if (app()->environment('production')) {
            $request->server->set('HTTPS', 'on');
            $request->server->set('HTTP_X_FORWARDED_PROTO', 'https');
            $request->server->set('HTTP_X_FORWARDED_SSL', 'on');
        }
        
        return $next($request);
    }
}
