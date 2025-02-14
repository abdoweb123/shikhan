<?php

namespace App\Http\Middleware;

// use LaravelLocalization;
use Illuminate\Support\Facades\Cache;
use Closure;

class DefaultLocalization
{
    public function handle($request, Closure $next)
    {
        dd('ssssssss');
        // $site_alias = \Route::input('site_alias','index');
        // if (!Cache::has('supported-locales-'.$site_alias))
        // {
        //     $supported_locales = config('localization');
        //     // $supported_locales = LaravelLocalization::getSupportedLocales();
        //     $languages = $site_alias == 'index' ? [config('app.fallback_locale')] : language::on($site_alias)->where('status',1)->pluck('alies')->toArray();
        //
        //     $supported_locales = array_where($supported_locales, function($value,$key) use($languages)
        //     {
        //         return in_array($key,[config('app.fallback_locale')]);
        //     });
        //     Cache::forever('supported-locales-'.$site_alias , $supported_locales);
        // }
        // // dd(Cache::get('supported-locales-'.$site_alias));
        // config([
        //     'laravellocalization.supportedLocales' => Cache::get('supported-locales-'.$site_alias),
        //     // 'laravellocalization.hideDefaultLocaleInURL' => true
        // ]);
        // // dd(config('laravellocalization.supportedLocales'));
        // dd(\LaravelLocalization::getSupportedLocales());

        // continue request
        return $next($request);
    }
}
