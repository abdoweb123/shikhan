<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use App,Auth;
use App\site;
use App\member;
use App\Models\Page;
use App\Teacher;
use DB;
use App\Services\GlobalService;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{

  public function __construct( private GlobalService $globalService )
  {
    //
  }

  public function home(Request $request)
  {
      // Cache::flush();
      $lang = App::getLocale() ;
      $data = $this->data($request,'home');

      $this->seoInfo('page_inf','home');

      $data['home'] = (new \App\Services\PageService())->getHomePage();

//      return $data;
      return view('front.index_seo',$data);


  }

  public function getHomeInvisiblePart(Request $request)
  {

      // without cache
      // Cache::forget('home_invisible_part_'.app()->getLocale());
      $html = view('front.index-invisible-part', [
        // 'result' => site::where('status',1)->whereTranslation('locale', app()->getlocale())->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get(),
        //  دبلومات الدبلوم الاول فقط مؤقتا
        'result' => site::where('status',1)->whereTranslation('locale', app()->getlocale())->where('parent_id','!=', 0)->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get(),
        'home' => (new \App\Services\PageService())->getHomePage(),
        'partners' => (new \App\Services\PartnerService())->getExactsortAndShuffle()
        // 'teachers' => IsTeacher::limit(3)->inRandomOrder()->get();,
      ])->render();
      return response()->json(['html' => $html]);


      // // Cache::forget('home_invisible_part_'.app()->getLocale());
      // $html = Cache::rememberForever('home_invisible_part_'.app()->getLocale(), function () {
      //     return view('front.index-invisible-part', [
      //       // 'result' => site::where('status',1)->whereTranslation('locale', app()->getlocale())->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get(),
      //       //  دبلومات الدبلوم الاول فقط مؤقتا
      //       'result' => site::where('status',1)->whereTranslation('locale', app()->getlocale())->where('parent_id','!=', 0)->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get(),
      //       'home' => (new \App\Services\PageService())->getHomePage(),
      //       'partners' => \App\Partner::where('status',1)->whereTranslation('locale', app()->getlocale())->get()
      //       // 'teachers' => IsTeacher::limit(3)->inRandomOrder()->get();,
      //     ])->render();
      // });

      return response()->json(['html' => $html]);

  }


  // public function getreportcousres($id, Request $request)
  // {
  //     $user = member::where('id', $id)->first();
  //     $notSubscriptions = $this->globalService->getCoursesNotTestedForUser($user, $request->params);
  //     return response()->json($notSubscriptions);
  // }

  public function landing1()
  {
      // Session::put('landing', 'landing');
      session()->put('landing', 'landing');
      return redirect(route('register'));
  }

  public function siteLike(Request $request)
  {
      site::where('id', $request->id)->first()->increment('likes_count');
  }


}
