<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddSecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		$response = $next($request);
		$response->header('Strict-Transport-Security', "max-age=63072000");

		$response->header("Content-Security-Policy", "default-src 'self' *.sentry.io; font-src 'self'; img-src 'self'; object-src 'none'; script-src 'self' *.sentry-cdn.com; style-src 'self'");

		return $response;
    }
}
