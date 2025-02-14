<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check header request and determine localization
        $local = ($request->hasHeader('X-Localization')) ? $request->header('X-Localization') : app()->getLocale();

        // set laravel localization
        app()->setLocale($local);


        // continue request
        return $next($request);
    }
}
