<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class RedirectIfHasntId
{
    public function handle($request, Closure $next)
    {

        if (! auth()->check()) {
          return redirect()->route('login');
        }

        if ($request->route()->getName() == 'site-certificate-show'){ // only site cirt download route to detrmine new flag
            if (! auth()->user()->id_number) {
                $request->session()->flash('user_doesnt_have_id_to_get_cirt', __('trans.must_enter_id_to_download_certificate'));
                if ($request->ajax()){
                  return response()->json(['redirect' => route('profile')]);
                }
                return redirect()->route('profile');
            }
        }

        return $next($request);

    }

}
