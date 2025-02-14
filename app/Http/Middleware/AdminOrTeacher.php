<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOrTeacher
{
    /*** Handle an incoming request. ***/
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check() || Auth::guard('teacher')->check())
        {
            return $next($request);
        }

        return redirect()->route('login.teacher');
    }
}
