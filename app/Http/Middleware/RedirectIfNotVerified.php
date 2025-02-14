<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class RedirectIfNotVerified
{
    public function handle($request, Closure $next)
    {

        // if (! auth()->check()) {
        //   return redirect()->route('login');
        // }
        //
        //
        // // كل المراحل
        // if ($request->route()->getName() == 'site-certificate-show'){
        //     if (! auth()->user()->email_verified_at) {
        //         if ($request->ajax()){
        //           return response()->json(['redirect' => route('show_verification_email')]);
        //         }
        //         return redirect()->route('show_verification_email');
        //     }
        // }
        //
        //
        // // كل الدورات
        // if ($request->route()->getName() == 'download-certificate'){
        //   if (! auth()->user()->email_verified_at) {
        //       if ($request->ajax()){
        //         return response()->json(['redirect' => route('show_verification_email')]);
        //       }
        //       return redirect()->route('show_verification_email');
        //   }
        // }

        return $next($request);

    }

}
