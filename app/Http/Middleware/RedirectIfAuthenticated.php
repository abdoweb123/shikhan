<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
//    public function handle($request, Closure $next, $guard = null)
//    {
//        if (Auth::guard($guard)->check())
//        {
//            return redirect(route($guard == 'admin' ? 'dashboard.index' : 'home'));
//        }
//
//        return $next($request);
//    }

    public function handle($request, Closure $next)
    {
        if (auth('admin')->check()) {
//            return redirect(RouteServiceProvider::HOME);
            return redirect()->route('dashboard.index');
        }

        if (auth('web')->check()) {
            return redirect()->route('home');
        }

        if (auth('teacher')->check()) {
            return redirect()->route('teacher.dashboard');
//            return redirect()->route('logout.teacher');
        }

        return $next($request);
    }
}
