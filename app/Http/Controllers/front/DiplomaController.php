<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use App;
use App\site;
use App\Services\SiteService;
use App\Services\GlobalService;
use App\Traits\ZoomTrait;
use DB;
use Auth;


class DiplomaController extends Controller
{

    use ZoomTrait;

    public function __construct(private SiteService $siteService, private GlobalService $globalService)
    {

    }

    public function index(Request $request)
    {
          $lang = App::getLocale();
          $data = $this->data($request,'Diplomas');
          $data['title_page'] = __('meta.title.Diplomas');
          $data['userFinishedAtLeastOneSite'] = false;

          $siteConditionService = new App\Services\SiteConditionService();

          $data['parent_site'] = null;
          if ($request->site){
              $data['parent_site'] = site::whereTranslation('locale', app()->getlocale())->whereTranslation('slug', $request->site)->select('id','parent_id')->firstorfail();
              $this->seoInfo('site','',$data['parent_site']);
              $sites = site::where('parent_id', $data['parent_site']->id)->get();
          } else {
              // كل الدبلومات
              $this->seoInfo('page_inf', 'diplomas');
              $sites = site::whereTranslation('locale', app()->getlocale())->with('terms')->get();

              // مؤقتا عرض الدبومات تحت الدبلوم الاول فقط
              // $data['parent_site'] = site::whereTranslation('locale', app()->getlocale())->where('parent_id', 0)->where('status',1)->select('id','parent_id')->firstorfail();
              // $this->seoInfo('site','',$data['parent_site']);
              // $sites = site::where('parent_id', $data['parent_site']->id)->whereTranslation('locale', app()->getlocale())->where('status',1)->get();
          }


          foreach ($sites as $site) {
              $lastCourseByDateAtOfSite = $this->getLastCourseByDateAtOfSite($site);
              $site->siteCourseZoomDayStatus = $this->siteCourseZoomDayStatus($lastCourseByDateAtOfSite);
              $site->siteNotCompleted = $site->isAllExamsOpened();

              if (Auth::guard('web')->user()){
                  $site->isUserSubscribedInSite = Auth::user()->isUserSubscribedInSite($site->id);
                  $site->userSiteConditionsDetails = $siteConditionService->setSite($site)->setUser(auth()->user())->getUserSiteConditionsDetails();

                  // user site degree
                  $userSiteDegree = Auth::guard('web')->user()->siteDegree($site->id);
                  if ($userSiteDegree){
                      $userSiteDegree = $userSiteDegree / ($site->validCourses('count') * 100);
                      $site->userSiteDegree = round($userSiteDegree * 100, 2);
                      $site->userSiteRate = $this->globalService->siteRateRanges($site->userSiteDegree);
                  }
              }
          }


          if ($request->site){
            $data['sitesTree'] = $this->siteService->getSitesTree($sites, $data['parent_site']->id, $data['parent_site']->id);
          } else {
            // كل الدبلومات
            $data['sitesTree'] = $this->siteService->getSitesTree($sites);

            // مؤقتا عرض الدبومات تحت الدبلوم الاول فقط
            // $data['sitesTree'] = $this->siteService->getSitesTree($sites, $data['parent_site']->id, $data['parent_site']->id);
          }

//          return view('front.content.diplomas.index', $data );
//        return $data;

//          foreach ($data['sitesTree'] as $site){
////              foreach ($site->terms as $term){
//                        return $site->terms[1]->with('courses')->get();
////              }
//          }

        return view('front.content.diplomas.terms', $data);
    }

}
