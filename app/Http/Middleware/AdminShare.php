<?php

namespace App\Http\Middleware;
use Closure;

class AdminShare
{

    public function handle($request, Closure $next)
    {


        // input locale
        $inputLocale = $request->query('input_locale');
        if (! $inputLocale){
          app()->singleton('inputLocale' , function() {
            return getDefaultLanguage()->alias;
          });
        } else {
          app()->singleton('inputLocale' , function() use($inputLocale){
            return strip_tags($inputLocale);
          });
          // return redirect( route(request()->route()->getName() , ['input_language' => $defaultLanguage->locale]));
        }

        return $next($request);

    }
}
