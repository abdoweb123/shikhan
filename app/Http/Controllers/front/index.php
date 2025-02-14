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

class index extends Controller
{

  private $globalService ;

  public function __construct( GlobalService $globalService )
  {
      $this->globalService = $globalService;
  }

  public function home(Request $request)
  {


      // Cache::flush();
      $lang = App::getLocale() ;
      $data = $this->data($request,'home');

      $this->seoInfo('page_inf','الرئيسية');

      // $data['home'] = Page::where('title_general','الرئيسية')->firstorfail()->activeTranslation->first();
      // $data['home'] = Page::where('is_home',1)->firstorfail()->activeTranslation->first();
      $data['home'] = (new \App\Services\PageService())->getHomePage();
      return view('front.index_seo',$data);

      // $data['home'] = Page::where('title_general','الرئيسية')->firstorfail()->activeTranslation->first();
      // $data['result'] = site::where('status',1)->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get();
      // $data['teachers'] = IsTeacher::limit(3)->inRandomOrder()->get();
      // return view('front.index',$data);

  }

  public function getHomeInvisiblePart(Request $request)
  {
      // Cache::forget('home_invisible_part_'.app()->getLocale());
      $html = Cache::rememberForever('home_invisible_part_'.app()->getLocale(), function () {
          return view('front.index-invisible-part', [
            'result' => site::where('status',1)->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get(),
            'home' => (new \App\Services\PageService())->getHomePage(),
            'partners' => \App\Partner::orederby('exact_sort')->limit(3)->get(),
            // 'teachers' => IsTeacher::limit(3)->inRandomOrder()->get();,
          ])->render();
      });

      return response()->json(['html' => $html]);

  }


  public function getreportcousres($id, Request $request)
  {
      $user = member::where('id', $id)->first();
      $notSubscriptions = $this->globalService->getCoursesNotTestedForUser($user, $request->params);
      return response()->json($notSubscriptions);
  }

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
