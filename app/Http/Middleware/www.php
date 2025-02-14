<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use App;
use DB;
use Illuminate\Support\Facades\Cache;
use Auth;
use App\Traits\ZoomTrait;

class www
{
    use ZoomTrait;

    public function handle($request, Closure $next)
    {
        // dd(\LaravelLocalization::getSupportedLocales());

        $language = \LaravelLocalization::setLocale();

        // dd($language);
        if (! $language) {
            return redirect(Request::root().'/ar');
        }



        $social = Cache::rememberForever('socials_'.$language, function () use($language) {
            return DB::Table('social')->join('social_translation','social.id','social_translation.social_id')->where('locale', $language)->select('link','icon')->get();
        });
        view::share('social', $social);

        $menu_header = Cache::rememberForever('menu_header_'.$language, function () {
            return $this->getMenu( app()->getlocale() );
        });

//        dd($menu_header);

        View::share('menu_header', $menu_header );

        $notificationsUnseen = [];
        if (Auth::guard('web')->user()) {
            $notificationsUnseen = DB::Table('notifications_inner')
              ->leftjoin('notifications_inner_members','notification_id','notifications_inner.id')
              ->where('notifications_inner.is_active',1)
              ->where( function($query) {
                  return $query->where('notifications_inner_members.user_id',Auth::guard('web')->id())
                  ->orwhere('notifications_inner.for_all', 1);
              })
              ->where('notifications_inner_members.is_active',1)
              ->wherenull('notifications_inner_members.seen_at')

              ->select('notifications_inner.id','notifications_inner.title')
              ->orderBy('notifications_inner.created_at','desc')->limit(7)->get();
        }
        View::share('notificationsUnseen', $notificationsUnseen );


        $request->merge(['courseWillZoomToday' => collect($this->getCourseWillZoomToday())->toArray() ]);


        if (Auth::guard('web')->user()) {
          $globalService = new \App\Services\GlobalService();
          $suggestionCource = $globalService->getCoursesNotTestedForUser(auth()->user(), ['rand' => true]);
          $request->merge(['suggestionCource' => collect($suggestionCource)->toArray()]);
        }


        // if user loged in, store his ip and country
        $authService = new \App\Services\AuthService();
        $authService->storeUserIp();

        return $next($request);
    }

    private function getMenu($language)
	  {

    		$data = DB::Table('menus')->where('is_active',1)->orderBy('sort')->get();

    		foreach ($data as $item) {
    				if ($item->type == 'page'){
    					$link = DB::Table('page_info')->where('page_id',$item->type_id)->where('language',$language)->where('is_active',1)->select('page_info.title','page_info.alias','page_info.route','page_info.params','page_info.image')->first();
    					if ($link){
    						$item->title = $link->title;
    						$item->alias = $link->alias;
    						$item->image = $link->image;
    						$item->route = $link->route;
    						$item->params = str_replace("**", $link->alias, $link->params);
    					}
    				}
    		}

		    return buildTree($data, $parentId = 0, $depth=0);

	   }
}
