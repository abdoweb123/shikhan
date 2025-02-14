<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use LaravelLocalization;
use App\language;
use App\libraries\Helpers;
use Illuminate\Support\Facades\Cache;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(\Request $request)
    {
        Schema::defaultStringLength(200);
        $this->set_lang();


        \Illuminate\Pagination\Paginator::useBootstrap();

        // \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
        //   'course' => 'App\Models\Course',
        // ]);

        Relation::morphMap([
            'lessons' => 'App\Models\Lesson',
            'tests' => 'App\Models\Test',
            'course' => 'App\course',
            'student' => 'App\Models\Student',
            'teacher' => 'App\Models\Teacher',
            'enrolled' => 'App\Models\Enrolled',
            'enrolled_term' => 'App\Models\EnrolledTerm',
            'enrolled_term_course' => 'App\Models\EnrolledTermCourse',
        ]);
    }

    public function set_lang()
    {
        $site_alias = in_array(request()->segment(1),array_keys(config('database.connections'))) ? request()->segment(1) : (in_array(request()->segment(2),array_keys(config('database.connections'))) ? request()->segment(2) : 'index' );
        if (!Cache::has('languages-'.$site_alias) && $site_alias != 'index')
        {
            Cache::forever('languages-'.$site_alias , language::on($site_alias)->where('status',1)->pluck('name','alies')->toArray());
        }

        if (!Cache::has('supported-locales-'.$site_alias))
        {
            $supported_locales = config('localization');
            $languages = $site_alias == 'index' ? \LaravelLocalization::getSupportedLocales() : Cache::get('languages-'.$site_alias);
            $supported_locales = \Arr::where($supported_locales, function($value,$key) use($languages)
            {
                return in_array($key,array_keys($languages));
            });
            Cache::forever('supported-locales-'.$site_alias , $supported_locales);
        }

        if (in_array(request()->segment(1),array_keys(config('database.connections'))))
        {
            $lang = json_decode(Helpers::defult_language($site_alias))->alies ;
        }
        elseif (in_array(request()->segment(2),array_keys(config('database.connections'))))
        {
            $lang = in_array(request()->segment(1),array_keys(Cache::get('supported-locales-'.$site_alias))) ? request()->segment(1) : json_decode(Helpers::defult_language($site_alias))->alies ;
        }
        else
        {
            $lang = config('app.fallback_locale') ;
        }

        config([
            'laravellocalization.supportedLocales' => Cache::get('supported-locales-'.$site_alias),
            'laravellocalization.fallback_locale' => $lang,
            'app.fallback_locale' => $lang,
            'app.locale' => $lang,
        ]);
        \LaravelLocalization::setLocale($lang);
    }




}
