<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\View;
use DB;

class BackEnd
{

    public function handle($request, Closure $next)
    {

        View::share(
          'sitesTree',
          (new \App\Services\SiteService())->getSitesTreeRoot( \App\site::with('terms')->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get())
        );

        return $next($request);

    }

}
