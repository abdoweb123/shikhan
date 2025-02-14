<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected $guards = [];
    public function handle($request, Closure $next, ...$guards)
    {

        $this->guards = $guards;

        if (in_array('api',$this->guards))
        {
            // Force Json accept type
            if (!Str::contains($request->header('accept'), ['/json', '+json'])) {
                $request->headers->set('accept', 'application/json,' . $request->header('accept'));
            }
        }

        // return $next($request);
        return parent::handle($request, $next, ...$guards);
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if (in_array('admin',$this->guards))
            {
                if (Auth::guard('admin')->check())
                {
                    return route('dashboard.index');
                }
                else
                {
                    return route('dashboard.login');
                }
            }
            if (in_array('web',$this->guards))
            {
                if (Auth::guard('web')->check())
                {
                    return route('home');
                }
                else
                {
                    return route('login');
                }
            }
        }
    }
}
