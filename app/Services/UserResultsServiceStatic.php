<?php

namespace App\Services;
use DB;
use App;
use App\site;
use App\Term;
use App\course_test_result;
use App\MemberSiteCertificate;
use Auth;

class UserResultsServiceStatic
{

  private $user;
  private $site;
  private $term;
  private $course;
  private $locale;
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

  public function setTerm($term)
  {
      $this->term = $term;
      return $this;
  }

  public function setCourse($course)
  {
      $this->course = $course;
      return $this;
  }

  public function setLocale($locale)
  {
      $this->locale = $locale;
      return $this;
  }

  private function globalService()
  {
    return new \App\Services\GlobalService();
  }


  // not_used
  public function getUserCoursesTestsResults_elequent()
  {
      $siteId = $this->site->id ?? null;

      return site::with([
            'terms'=> function($q){
                 $q->where('status', 1);
             },
            'terms.term_results' => function($q){
                 $q->where('user_id', 39)->where('locale', 'ar');
             },
             'terms.courses' => function($q){
                  $q->where('status', 1);
             },
             'terms.courses.final_results' => function($q) use($siteId){
                 $q->where('user_id', 39)->where('locale', 'ar')
                    ->when($siteId, function($q) use($siteId){ // extra than dynamic
                         return $q->where('member_courses_results.site_id', $siteId);
                    });
             }
         ])->get();

  }


  public function getUserCoursesTestsResults()
  {

          $siteId = $this->site->id ?? null;

          // correct site with courses only with out term
          // $results = DB::table('sites')
          //     ->join('sites_translations','sites_translations.site_id','sites.id')
          //     ->join('course_site','course_site.site_id','sites.id')
          //     ->leftJoin('member_courses_results', function($join) use($siteId){
          //         $join->on('member_courses_results.course_id','course_site.course_id')
          //           ->where('member_courses_results.user_id', $this->user->id)
          //           ->where('member_courses_results.locale', app()->getLocale())
          //           ->when($siteId, function($q) use($siteId){ // extra than dynamic
          //               return $q->where('member_courses_results.site_id', $siteId);
          //           });
          //     })
          //     ->when($siteId, function($q) use($siteId){
          //         return $q->where('course_site.site_id', $siteId);
          //     })
          //     ->join('courses','courses.id','course_site.course_id')
          //     ->join('courses_translations','courses_translations.course_id','courses.id')
          //     ->where('sites_translations.locale', App::getLocale())
          //     ->where('courses_translations.locale', App::getLocale())
          //     ->where('courses.status', 1)
          //     ->select('member_courses_results.test_id as id','member_courses_results.user_id','member_courses_results.test_degree as degree',
          //              'member_courses_results.test_rate as rate','member_courses_results.tests_count as testsCount','member_courses_results.locale',
          //              'sites.title','sites_translations.slug as site_alias','courses_translations.name as course_title','courses_translations.alias as course_alias',
          //              'sites.id as site_id','courses.id as course_id','courses_translations.date_at','courses.exam_at'
          //              )
          //     ->orderBy('date_at')
          //     ->get()
          //     ->each(function ($course) {
          //         $course->course_rate = __('trans.rate.'.$course->rate); // get translated rate word ممتاز  - جيد ....
          //     });


          $results = DB::table('sites')
              ->join('sites_translations','sites_translations.site_id','sites.id')
              ->join('course_site','course_site.site_id','sites.id')
              ->leftJoin('member_terms_results', function($join) use($siteId){
                  $join->on('member_terms_results.term_id','course_site.term_id')
                    ->where('member_terms_results.user_id', $this->user->id)
                    ->where('member_terms_results.test_locale', app()->getLocale())
                    ->when($siteId, function($q) use($siteId){ // extra than dynamic
                        return $q->where('member_terms_results.site_id', $siteId);
                    });
              })
              ->leftJoin('member_courses_results', function($join) use($siteId){
                  $join->on('member_courses_results.course_id','course_site.course_id')
                    ->where('member_courses_results.user_id', $this->user->id)
                    ->where('member_courses_results.locale', app()->getLocale())
                    ->when($siteId, function($q) use($siteId){ // extra than dynamic
                        return $q->where('member_courses_results.site_id', $siteId);
                    });
              })
              ->when($siteId, function($q) use($siteId){
                  return $q->where('course_site.site_id', $siteId);
              })
              ->join('terms','terms.id','course_site.term_id')
              ->join('terms_translations','terms_translations.term_id','terms.id')
              ->join('courses','courses.id','course_site.course_id')
              ->join('courses_translations','courses_translations.course_id','courses.id')
              ->where('sites_translations.locale', App::getLocale())
              ->where('terms_translations.locale', App::getLocale())
              ->where('courses_translations.locale', App::getLocale())
              ->where('courses.status', 1)
              ->select('member_courses_results.test_id as id','member_courses_results.user_id','member_courses_results.test_degree as degree',
                       'member_courses_results.test_rate as rate','member_courses_results.tests_count as testsCount','member_courses_results.locale',

                       'course_site.term_id as term_id','terms.sort as term_sort',
                       'member_terms_results.test_id as term_test_id','member_terms_results.test_degree as term_degree',
                       'member_terms_results.test_rate as term_rate','member_courses_results.tests_count as term_testsCount','member_courses_results.test_locale as term_test_locale',

                       'sites.title','sites_translations.slug as site_alias',
                       'terms_translations.name as term_title','terms_translations.alias as term_alias',
                       'courses_translations.name as course_title','courses_translations.alias as course_alias',
                       'sites.id as site_id','courses.id as course_id','courses_translations.date_at','courses.exam_at'
                       )
              ->orderBy('date_at')
              ->orderBy('term_sort')
              ->get()
              ->each(function ($course) {
                  $course->course_rate = __('trans.rate.'.$course->rate); // get translated rate word ممتاز  - جيد ....
              });



          $user = Auth::user();
          $site = null;

          if( $results->isNotEmpty() ){
                    // site data
                    $site = site::valid()->where('id',$results->first()->site_id)->first();
                    $this->setSite($site);
                    $site = $this->getUserSiteTestsResults();


                    $results = $results->groupBy('term_id');

                    foreach ($results as $term) {
                      // term data
                        $termData = $term->first();
                        $currentTerm = Term::valid()->where('id',$termData->term_id)->first();
                        $term->id = $termData->term_id;
                        $term->title = $termData->term_title;
                        $term->term_test_id = $termData->term_test_id;
                        $term->degree = $termData->term_degree;
                        $term->rate = $termData->term_rate;
                        $termTestResultService = new \App\Services\TermTestResultService();
                        $term->userFinalTestOfTerm = $termTestResultService->getUserFinalTestOfTerm($currentTerm, $this->user, app()->getlocale());

                        $termService = new \App\Services\TermService();

                        $extraTraysService = new \App\Services\ExtraTrays([
                          'user' => $user,
                          'term_id' => $term->id,
                          'locale' => app()->getlocale(),
                        ]);
                        $extraTrays = $extraTraysService->getUserEmailXtraTrays();
                        $extraTrays = $extraTrays < maxTests() ? maxTests() : $extraTrays;

                        $userTestsCountOfTerm = $termTestResultService->getUserTestsCountOfTerm($currentTerm, $user, app()->getlocale());
                        $term->showTermTestToUser = $termService->showTermTestToUser($currentTerm, $user);
                        $term->openTermTestToUser = $termService->openTermTestToUser($currentTerm, user: $user,  extraTrays : $extraTrays, userTestsCountOfTerm: $userTestsCountOfTerm);
                        $term->userHasTrays = $termService->userHasTrays($extraTrays, $userTestsCountOfTerm);
                        $term->userResultsOfTerm = collect([]);

                        if ($term->showTermTestToUser){
                          $term->userResultsOfTerm = $termTestResultService->getUserResultsOfTerm($currentTerm, $user, $locale = app()->getlocale());
                        }
                      // end term

                        foreach ($term as $test) {

                            $userResultsService = new \App\Services\UserResultsService();
                            $test->previousTestsSameCourse = $userResultsService->getPreviousTestsSameCourse($this->user->id, $test->course_id, app()->getlocale());


                            // trays -----------------------
                            $test->trays = maxTests();
                            $test->userGetXtraTray = false;
                            // for all
                            $extraTraysService = new App\Services\ExtraTrays([
                              'user' => $this->user,
                              'site_id' =>  $site->id,
                              'course_id' => $test->course_id,
                              'locale' => app()->getlocale(),
                            ]);
                            $test->trays = $extraTraysService->getUserXtraTrays()['extraTrays'];
                            $test->trays = $test->trays < maxTests() ? maxTests() : $test->trays;

                            $test->less_than_70 = true;
                            if ($test->rate < $this->range_degree){
                                $test->less_than_70 = false;
                            }


                            $ejazaService = new \App\Services\EjazaService();
                            $ejazaService->setUser($this->user)->setSiteId($test->site_id)->setCourseId($test->course_id)->setTestResult($test);
                            $test->faildInEjazaVisualTest = $ejazaService->faildInEjazaVisualTest();
                            $test->userHasEjazaCertificate = $ejazaService->userSucessInEjaza();
                            $test->visualTestResult = null;
                            if($ejazaService->hasEjazaVisualTest()){
                              $test->visualTestResult = $ejazaService->visualTestResult;
                            }
                            // ---------------------------------
                        }
                    }

          }




          return ['results' => $results, 'site' => $site];


  }

  public function userSuccessInSite($courses)
  {

      foreach ($courses as $course) {
          if( $this->globalService()->siteRateRanges($course->max_degree) < $this->range_degree ){
            return false;
          }
      }
      return true;
  }




  public function getUserSitesTestsResults()
  {
      // only sites that user subscribed in
      $sites = $this->user->sites()->valid()->orderBy('id')->get();

      foreach ($sites as $site) {
          $site = $this->setSite($site)->getUserSiteTestsResults();
      }

      return $sites;
  }

  public function getUserSiteTestsResults()
  {

        $siteId = $this->site->id;

        $this->site->courses_count = $this->site->validCourses('count', app()->getlocale());

        $this->site->full_site_degree = $this->site->courses_count * 100;

        $userSiteResults = $this->user->sites_results()->where('site_id', $this->site->id)->where('locale', app()->getlocale())->first();

        if($userSiteResults){
            $this->site->finished_courses_count = $userSiteResults->user_tests_count;

            $this->site->user_site_degree = $userSiteResults->user_site_degree / $this->site->full_site_degree;
            $this->site->user_site_degree = round($this->site->user_site_degree * 100, 2);

            $this->site->user_site_rate = $this->globalService()->siteRateRanges($this->site->user_site_degree);

            $this->site->site_completed = $this->site->isAllExamsOpened(app()->getlocale());

            $this->site->user_sucess = $userSiteResults->user_successed; // ???

            $this->site->user_finished_site = $userSiteResults->user_finished_site;

            $this->site->isUserSubscribedInSite = $this->user->isUserSubscribedInSite($this->site->id); // $this->user->sites()->where('sites.id',$this->site->id)->exists();

            $this->site->less_than_70 = true;
            if ($this->site->user_site_rate < $this->range_degree){
                $this->site->less_than_70 = false;
            }

            $siteCertificateCode = $this->site->member_site_certificate()->select('site_id','user_id','locale')
              ->where('user_id', $this->user->id)
              ->where('locale', app()->getlocale())
              ->first();
            $this->site->certificateCode = $siteCertificateCode ? $siteCertificateCode->pivot->code : '';

        } else {
          $this->site->finished_courses_count = 0;
          $this->site->user_site_degree = 0;
          $this->site->user_site_rate = 0;
          $this->site->site_completed = $this->site->isAllExamsOpened();
          $this->site->user_sucess = false;
          $this->site->user_finished_site = false;
          $this->site->isUserSubscribedInSite = $this->user->isUserSubscribedInSite($this->site->id);
          $this->site->less_than_70 = true;
          $this->site->certificateCode = null;
        }


        return $this->site;

  }





}
