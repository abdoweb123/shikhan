<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;



class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            // SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen('routes.translation', function($locale, $attributes)
        {
            if (isset($attributes['site']) && in_array($attributes['site'],array_keys(config('database.connections'))))
            {
                foreach( $attributes as $k => $v )
                {
                    if($k == 'course')
                    {
                        $page = \App\course::whereTranslation('alias',($v))->first();
                        if (!empty($page))
                        {
                            $attributes[$k] = @$page->translateOrDefault($locale)->alias;
                        }
                    }

                    if($k == 'post')
                    {
                        $cache_1 = 'routes-translation-'.$attributes['site'].'-'.app()->getLocale().'-'.$v;
                        if (!Cache::has($cache_1))
                        {
                            $page = \App\post_description::on($attributes['site'])->select('post_id')->where(['status'=> 1,'alies' => $v])
                            ->whereHas('language', function ($query) {return $query->where('alies',app()->getLocale());})->first();

                            Cache::forever($cache_1,$page);
                        }
                        $page = Cache::get($cache_1);
                        if (!empty($page))
                        {
                            $cache_2 = 'routes-translation-'.$attributes['site'].'-'.app()->getLocale().'-'.$v.'-to-'.$locale;
                            if (!Cache::has($cache_2))
                            {
                                $page = \App\post_description::on($attributes['site'])->select('alies')->where(['post_id'=> $page->post_id])
                                ->whereHas('language', function ($query) use($locale) {return $query->where('alies',$locale);})->first();

                                Cache::forever($cache_2,$page);
                            }
                            $page = Cache::get($cache_2);
                            if (!empty($page))
                            {
                                $attributes[$k] = @$page->alies;
                            }
                        }
                    }
                }
            }
            return $attributes;
        });
    }
}
