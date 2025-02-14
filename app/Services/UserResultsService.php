<?php

namespace App\Services;
use DB;
use App;
use App\site;
use App\course_test_result;
use App\MemberSiteCertificate;

class UserResultsService
{

  private $user;
  private $site;
  public $range_degree=1;

  public function setUser($user)
  {
      $this->user = $user;
      return $this;
  }

  public function setSite($site)
  {
      $this->site = $site;
      return $this;
  }

  private function globalService()
  {
    return new \App\Services\GlobalService();
  }




  public function getUserCoursesTestsResults()
  {

      $siteId = $this->site->id ?? null;

      $results = DB::table('sites')
          ->join('sites_translations','sites_translations.site_id','sites.id')
          ->join('course_site','course_site.site_id','sites.id')
          ->leftJoin('course_tests_results', function($join){
              $join->on('course_tests_results.course_id','course_site.course_id')->where('course_tests_results.user_id', $this->user->id); // Auth::id()
          })
          ->when($siteId, function($q) use($siteId){
            return $q->where('course_site.site_id', $siteId);
          })
          ->join('courses','courses.id','course_site.course_id')
          ->join('courses_translations','courses_translations.course_id','courses.id')
          ->where('courses_translations.locale', App::getLocale())
          ->where('sites_translations.locale', App::getLocale())
          ->select('course_tests_results.id','course_tests_results.user_id','course_tests_results.degree','course_tests_results.rate',
                   'sites.title','sites_translations.slug as site_alias','courses_translations.name as course_title','courses_translations.alias as course_alias',
                   'sites.id as site_id','courses.id as course_id','courses_translations.date_at','courses.exam_at','course_site.extra_certificate_templates','course_tests_results.visual_rate')
          ->get()
          ->groupBy('course_id')
          ->transform(function ($item, $key) {
              return $item->sortByDesc('degree'); // put max degree at the first
          })
          ->each(function ($item, $key) {
              $item->each(function ($course) use($item) {
                // $course->site_alias = str_ireplace('_','-',$course->site_alias);
                $course->course_rate = __('trans.rate.'.$course->rate); // get translated rate word ممتاز  - جيد ....
                $course->testsCount = $item->count(); // count tests
              });
          })
          ->transform(function ($item, $key) {
              return $item->first(); // return only first one whiche is has max_degree
          })
          ->sortBy('date_at') // sort last result by date_at
          ;


          $site = null;
          if( $results->isNotEmpty() ){
                    $site = site::valid()->where('id',$results->first()->site_id)->first();

                    $site->courses_count = $this->globalService()->getCourses([
                      'siteId' => $site->id,
                      'count' => true
                    ]);
                    $site->full_site_degree = $site->courses_count * 100;

                    $userSiteDegrees = $this->globalService()->getUserDegreesOfEachSite(
                        $this->user,
                        ['siteId' => $site->id]
                    );

                    $site->finished_courses_count = count($userSiteDegrees);
                    $site->user_site_degree = $this->globalService()->getUserSiteDegree($userSiteDegrees) / $site->full_site_degree;
                    $site->user_site_degree = round($site->user_site_degree * 100, 2);
                    $site->user_site_rate = $this->globalService()->siteRateRanges($site->user_site_degree);


                    $examsStillClosed = $this->globalService()->siteNotCompleted([
                        'site_id' => $siteId,
                        'exists' => true
                    ]);
                    $site->site_completed = $examsStillClosed ? false : true;




                    $site->user_sucess = false;
                    if ($this->userSuccessInSite($userSiteDegrees)){
                        $site->user_sucess = true;
                    }

                    $site->user_finished_site = true;
                    if ( $site->courses_count > $site->finished_courses_count){
                        $site->user_finished_site = false;
                    }

                    //$userSubsCountInSite = $this->globalService->getUserSubsInSite(Auth::guard('web')->user(), $site, [ 'count' => true ]);
                    //$site->isUserSubscribedInSite = $userSubsCountInSite > 1 ? false : true; // لو اشترك ولو كورس واحد فى الدبلوم يظهر // ($userSubsCountInSite != $site->courses_count);

                    $site->less_than_70 = true;
                    if ($site->user_site_rate < $this->range_degree){
                        $site->less_than_70 = false;
                    }


                    foreach ($results as $test) {

                        // $test->previousTestsSameCourse = course_test_result::where('user_id', $this->user->id)->where('course_id',$test->course_id)->select('id','course_id','degree','created_at')->with('course:id','course.sites')->get();
                        // $userResultsService = new \App\Services\UserResultsService();
                        $data['previousTestsSameCourse'] = $this->getPreviousTestsSameCourse($this->user->id, $test->course_id, app()->getlocale());

                        // trays -----------------------
                        $test->trays = maxTests();
                        $test->userGetXtraTray = false;
                        // for all
                        $extraTraysService = new App\Services\ExtraTrays([
                          'user' => $this->user,
                          'site_id' =>  $site->id,
                          'course_id' => $test->course_id,
                          'locale' => app()->getlocale()
                        ]);
                        $test->trays = $extraTraysService->getUserXtraTrays()['extraTrays'];
                        $test->trays = $test->trays < maxTests() ? maxTests() : $test->trays;

                        $test->less_than_70 = true;
                        if ($test->rate < $this->range_degree){
                            $test->less_than_70 = false;
                        }


                        // show hide ejaza cirt
                        $test->userHasEjazaCertificate = false;
                        $test->userSucessInVisualTestToGetEjaza = $this->globalService()->userSucessInVisualTestToGetEjaza($test);
                        if ( $this->globalService()->userSucessInTestToGetEjaza($test) &&  $test->userSucessInVisualTestToGetEjaza === true){
                          $test->userHasEjazaCertificate = true;
                        }
                        // ---------------------------------
                    }

          }


          return ['results' => $results, 'site' => $site];


  }

  public function userSuccessInSite($courses)
  {
      $globalService = new \App\Services\GlobalService();

      foreach ($courses as $course) {
          if( $globalService->siteRateRanges($course->max_degree) < $this->range_degree ){
            return false;
          }
      }
      return true;
  }




  public function getUserSitesTestsResults()
  {
      // only sites that user subscribed in
      $sites = $this->user->sites()->valid()->select('sites.id','title','status','new_flag')->orderBy('id')->get();

      foreach ($sites as $site) {
          $site = $this->setSite($site)->getUserSiteTestsResults();
      }

      return $sites;
  }

  public function getUserSiteTestsResults()
  {

      $siteId = $this->site->id;

      $this->site->courses_count = $this->globalService()->getCourses([
        'siteId' => $siteId,
        'count' => true
      ]);
      $this->site->full_site_degree = $this->site->courses_count * 100;

      $userSiteDegrees = $this->globalService()->getUserDegreesOfEachSite(
          $this->user,
          ['siteId' => $siteId]
      );

      $this->site->finished_courses_count = count($userSiteDegrees);
      $this->site->user_site_degree = $this->globalService()->getUserSiteDegree($userSiteDegrees) / $this->site->full_site_degree;
      $this->site->user_site_degree = round($this->site->user_site_degree * 100, 2);
      $this->site->user_site_rate = $this->globalService()->siteRateRanges($this->site->user_site_degree);


      $examsStillClosed = $this->globalService()->siteNotCompleted([
          'site_id' => $siteId,
          'exists' => true
      ]);
      $this->site->site_completed = $examsStillClosed ? false : true;


      $this->site->user_sucess = false;
      if ($this->userSuccessInSite($userSiteDegrees)){
          $this->site->user_sucess = true;
      }

      $this->site->user_finished_site = true;
      if ( $this->site->courses_count > $this->site->finished_courses_count){
          $this->site->user_finished_site = false;
      }

      // $userSubsCountInSite = $this->globalService->getUserSubsInSite($user, $site, [ 'count' => true ]);
      // $site->isUserSubscribedInSite =   $userSubsCountInSite > 1 ? false : true; // لو اشترك ولو كورس واحد فى الدبلوم يظهر // ($userSubsCountInSite != $site->courses_count);
      // $site->isUserSubscribedInSite = Auth::user()->sites()->where('sites.id',$site->id)->exists();
      $this->site->isUserSubscribedInSite = $this->user->sites()->where('sites.id',$this->site->id)->exists();

      $this->site->less_than_70 = true;
      if ($this->site->user_site_rate < $this->range_degree){
          $this->site->less_than_70 = false;
      }

      $siteCertificateCode = MemberSiteCertificate::where('user_id',$this->user->id)->where('site_id',$this->site->id)->first();
      $this->site->certificateCode = $siteCertificateCode ? $siteCertificateCode->code : '';

      return $this->site;

  }


  public function getPreviousTestsSameCourse($user_id, $course_id, $locale = null)
  {
      return course_test_result::where('user_id', $user_id)
        ->where('course_id',$course_id)
        ->when($locale, function($q) use($locale){
          return $q->where('locale', $locale);
        })
        ->select('id','course_id','degree','created_at')
        ->with('course:id','course.sites')->get();
  }


}
