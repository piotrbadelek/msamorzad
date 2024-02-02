<?php

namespace App\Http\Middleware;

use App\Utilities\EventActivation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValentinesDayEventInProgress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if (!$request->user()->isAdministrator && !EventActivation::isValentinesDayEventActive()) {
			return redirect("/");
		}

        return $next($request);
    }
}
