<?php

namespace App\Http\Middleware;

use Closure;

class Headers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if ($request->ajax()) {
            $response->header("Cache-Control","no-cache, no-store, max-age=0, must-revalidate, proxy-revalidate, no-transform");
            $response->header("Pragma", "no-cache");
        }
        return $response;
    }
}
