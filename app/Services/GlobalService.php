<?php

namespace App\Services;
use DB;
use Session;
use Illuminate\Support\Facades\Cache;

class GlobalService
{
  private $minDegreeToSucess = 1;
  public $pointOfSuccess = 50;
  public $pointOfSuccessEjaza_289 = 85;


  public function userFinishedAtLeastOneSite($user, $site, $params = [])
  {
        $siteNotCompleted = $this->siteNotCompleted([
            'site_id' => $site->id,
            'exists' => true
        ]);
        if ($siteNotCompleted){ return false; }


        $sitCoursesCount = $this->getCourses([
          'siteId' => $site->id,
          'count' => true
        ]);

        $userSiteDegrees = $this->getUserDegreesOfEachSite(
            $user,
            ['siteId' => $site->id]
        );


        $userFinishedSite = true;
        if ( $sitCoursesCount > count($userSiteDegrees)) {
            $userFinishedSite = false;
        }
        if (! $userFinishedSite){ return false; }


        $userSucessInSite = false;
        if ($this->userSuccessInSite($userSiteDegrees)){
            $userSucessInSite = true;
        }
        if (! $userSucessInSite){ return false; }


        return true;

  }

  public function userSuccessInSite($courses)
  {
      foreach ($courses as $course) {
          if( $this->siteRateRanges($course->max_degree) < $this->minDegreeToSucess ){
            return false;
          }
      }
      return true;
  }





  public function userFailedCourses($data)
  {
      // الدورات التى رسب فيها الطالب
      return $data->where('max_degree','<', $this->pointOfSuccess);
  }


  public function getSitesMustFinishToSubscribeIn($site_id)
  {
      // هناك دورات تعتمد على اخرى مثلا يجب قبل الاشتراك فى دبلوم اجازة القران ان يكون انهى دبلوم القران و علومه
      return DB::Table('site_dependent')
        ->join('sites_translations','site_dependent.depend_on_site_id','sites_translations.site_id')
        ->where('site_dependent.site_id',$site_id)
        ->where('sites_translations.locale', app()->getLocale())
        ->select('site_dependent.depend_on_site_id','sites_translations.name')
        ->get();
  }
  public function userFinishDependents($user, $dependents)
  {

      foreach ($dependents as $siteMustFinish) {
          $siteMustFinish->id = $siteMustFinish->depend_on_site_id; // becuse userFinishedAtLeastOneSite take id of site as site->id
          if ( $this->userFinishedAtLeastOneSite( $user, $siteMustFinish  ) == false ){
            return false;
          }
      }

      return true;

  }


  public function getCoursesNotTestedForUser($user, $params = [])
  {
      // الدورات الباقية للطالب التى لم يختبرها بعد من الدورات الفعالة

      $siteId = isset($params['site_id']) ? $params['site_id'] : null;
      $allCourses = isset($params['all_courses']) ? $params['all_courses'] : null;
      $first = isset($params['first']) ? $params['first'] : null;
      $count = isset($params['count']) ? $params['count'] : null;
      $rand = isset($params['rand']) ? $params['rand'] : null;
      $locale = isset($params['locale']) ? $params['locale'] : app()->getlocale();

      $data = DB::table('courses')
          ->join('courses_translations','courses_translations.course_id','courses.id')
          ->Join('course_site', function($join) {
              $join->on('course_site.course_id', 'courses.id');//->where('course_site.main_site', 1);
          })
          ->join('sites','sites.id','course_site.site_id')
          ->join('sites_translations','sites_translations.site_id','sites.id')
          ->where('courses_translations.locale',$locale)
          ->where('sites_translations.locale',$locale)
          ->when($allCourses == null, function ($query) {
              return $query->whereNULL('courses.deleted_at')
                        ->where('courses.exam_at' ,'<=', date('Y-m-d') )
                        ->where('courses.exam_at','!=', Null)
                        ->where('courses.status',1);
          })
          ->whereNotIn('courses.id', $user->test_results()->pluck('course_id')) // it was: $user->test_results->pluck('course_id')
          ->when($siteId, function ($query, $siteId) {
              return $query->where('sites.id', $siteId);
          })
          ->select(
            'courses_translations.name as title','courses_translations.alias as course_alias',
            'sites_translations.name as site_name','sites_translations.alias as site_alias',
            'courses.id as course_id', 'sites.id as site_id',
            'courses_translations.date_at','courses.exam_at'
          );

          if ($count){ return $data->count(); }
          if ($first){ return $data->first(); }
          if ($rand){ return $data->inRandomOrder()->limit(1)->first(); }


          return $data->get();

  }
  public function renderCoursesNotTestedForUser($user)
  {
      $data = $this->getCoursesNotTestedForUser($user);
      return view('common.members.user-courses', ['detailsType' => 'USER_COURSES_ACTIVE_NOT_TESTED', 'data' => $data])->render();
  }



  public function getCoursesUserDoesntSubscripeIn($user, $params = [])
  {
      // الدورات التى لم يشترك فيها الطالب من الدورات الفعالة

      $siteId = isset($params['site_id']) ? $params['site_id'] : null;
      $locale = isset($params['locale']) ? $params['locale'] : app()->getlocale();

      return DB::table('courses')
          ->join('courses_translations','courses_translations.course_id','courses.id')
          ->Join('course_site', function($join) {
              $join->on('course_site.course_id', 'courses.id');//->where('course_site.main_site', 1);
          })
          ->join('sites','sites.id','course_site.site_id')
          ->join('sites_translations','sites_translations.site_id','sites.id')
          ->where('courses_translations.locale',$locale)
          ->where('sites_translations.locale',$locale)
          ->whereNULL('courses.deleted_at')
          ->where('courses.exam_at' ,'<=', date('Y-m-d') )->where('courses.exam_at','!=', Null)
          ->whereNull('courses.deleted_at')->where('courses.status',1)
          ->whereNotIn('courses.id',$user->courses->pluck('id'))
          ->when( $siteId , function($q) use($siteId) {
              return $q->where('sites.id', $siteId);
          })
          ->select(
            'courses_translations.name as course_name','courses_translations.alias as course_alias',
            'sites_translations.name as site_name','sites_translations.alias as site_alias','sites.id as site_id','courses.id as course_id'
          )->get();
  }
  public function renderCoursesUserDoesntSubscripeIn($user)
  {
      $data = $this->getCoursesUserDoesntSubscripeIn($user);
      return view('common.members.user-courses', ['detailsType' => 'USER_COURSES_DOESNT_SUBSCRIPE', 'data' => $data])->render();
  }


  // important working in admin and front
  public function getUserCoursesMaxDegrees($user, $params = [])
  {
      // review
      // اختبارات الطالب . اختبار واحد لكل دورة وهو الاعلى درجة
      $siteId = isset($params['siteId']) ? $params['siteId'] : null;

       $data = DB::Table('course_tests_results')
        ->join('courses','courses.id','course_tests_results.course_id')
        ->join('courses_translations','courses_translations.course_id','courses.id')
        ->Join('course_site', function($join) {
            $join->on('course_site.course_id', 'course_tests_results.course_id');//->where('course_site.main_site', 1);
        })
        ->join('sites','sites.id','course_site.site_id')
        ->join('sites_translations','sites_translations.site_id','sites.id')
        ->where('user_id',$user->id)
        ->select('course_tests_results.id','course_tests_results.course_id',
                  DB::raw('MAX(course_tests_results.degree) as max_degree'),
                  DB::raw('MAX(course_tests_results.rate) as rate'),
                  'courses_translations.alias as course_alias', 'sites_translations.alias as site_alias',
                  'course_tests_results.created_at', 'course_tests_results.no_test')
        ->groupBy('course_tests_results.course_id');

        if ($siteId) {
            $data = $data->where('course_site.site_id',$siteId);
        }

        return $data->get();

  }
  public function renderUserCoursesMaxDegrees($user, $params = [])
  {
      $data = $this->getUserCoursesMaxDegrees($user, $params);
      $overAllDegree = $this->getUserOverAllDegree($user)[0]->over_all_degree;
      return view('common.members.user-courses', ['detailsType' => 'USER_COURSES', 'data' => $data, 'overAllDegree' => $overAllDegree])->render();
  }


  public function getUserOverAllDegree($user)
  {
      return DB::select(
        "Select sum(max_degree) / count(course_id) as over_all_degree
         From (
          Select course_tests_results.id, course_tests_results.course_id, MAX(degree) as max_degree
          FROM `course_tests_results` where user_id = $user->id group by course_id
         ) user_courses"
      );
  }

  // used  in profile and quize
  public function getUserDegreesOfEachSite($user, $params=[])
  {
        // اختبارات الطالب بالدرجة الاعلى فى كل كورس
        $siteId = isset($params['siteId']) ? $params['siteId'] : null;

        $selStatment = "Select course_tests_results.id, course_site.site_id,course_tests_results.course_id, MAX(degree) as max_degree
                FROM `course_tests_results`
                JOIN course_site on course_tests_results.course_id = course_site.course_id
                where user_id = $user->id";
        if ($siteId){
          $selStatment = $selStatment . " and course_site.site_id = " . $siteId;
        }
        $selStatment = $selStatment . " group by course_tests_results.course_id,course_site.site_id";

        return DB::select( $selStatment );
  }






  public function getUserSubscriptions($user)
  {
      //الدورات المشترك بها
      return DB::table('courses')
          ->join('courses_translations','courses_translations.course_id','courses.id')
          ->Join('course_site', function($join) {
              $join->on('course_site.course_id', 'courses.id');//->where('course_site.main_site', 1);
          })
          ->join('sites','sites.id','course_site.site_id')
          ->join('sites_translations','sites_translations.site_id','sites.id')
          ->join('course_subscriptions','course_subscriptions.course_id','courses.id')
          ->where('course_subscriptions.user_id',$user->id)
          ->select(
            'courses_translations.name as course_name','courses_translations.alias as course_alias',
            'sites_translations.name as site_name','sites_translations.alias as site_alias','sites.id as site_id','courses.id as course_id'
          )->get();
  }
  public function renderUserSubscriptions($user)
  {
      $data = $this->getUserSubscriptions($user);
      return view('common.members.user-courses', ['detailsType' => 'USER_SUBSCRIPTIONS', 'data' => $data])->render();
  }





  public function getUserSiteDegree($testResaults, $params=[])
  {
      // مجموع الدرجات الاعلى فى كل كورس . اجمالى درجة الطالب على مستوى الدبلوم
      $userSiteDegree = 0;
      foreach ($testResaults as $testResault) {
        $userSiteDegree += $testResault->max_degree;
      }
      return $userSiteDegree;
  }

  public function siteRateRanges($degree)
  {
      // old
      // if ($degree < 70) { return 0;}
      // if ($degree < 80) { return 1;}
      // if ($degree < 90) { return 2;}
      // if ($degree < 95) { return 4;}
      // if ($degree <= 100) { return 5;}

      if ($degree < 50) { return 0;}
      if ($degree < 60) { return 1;}
      if ($degree < 70) { return 2;}
      if ($degree < 80) { return 3;}
      if ($degree < 90) { return 4;}
      if ($degree <= 100) { return 5;}

  }

  public function siteDegreeRanges($rate)
  {
      if ($rate == 0 ) { return 50;}
      if ($rate == 1 ) { return 60;}
      if ($rate == 2 ) { return 70;}
      if ($rate == 3 ) { return 80;}
      if ($rate == 4 ) { return 90;}
      if ($rate == 5 ) { return 100;}
  }

  public function rateRanges()
  {

      return [
        '0' => __('trans.rate')[0],
        '1' => __('trans.rate')[1],
        '2' => __('trans.rate')[2],
        '3' => __('trans.rate')[3],
        '4' => __('trans.rate')[4],
        '5' => __('trans.rate')[5],
      ];

  }

  public function getCoursesTestedForUser($user)
  {
      // اختبارات الطالب
      return $user->test_results;
  }



  // اشراك المستخدم فى دبلومات معينة
  public function subscripeUserInManySites($user,$sitesIds=[])
  {
      if (! empty($sitesIds) ){
            $diplomaCourses = DB::Table('courses')
              ->join('course_site','courses.id','course_site.course_id')
              ->wherein('course_site.site_id', $sitesIds)
              ->where('courses.status',1)
              ->whereNull('courses.deleted_at')
              ->pluck('courses.id');
            $user->courses()->syncWithoutDetaching($diplomaCourses);
      }

      Session::forget('siteIdTosubscripe');

      return true;
  }


  public function getUserTestResultAnswers($params = [])
  {

      // اجابات الطالب
      $courseTestTesultId = isset($params['course_test_result_id']) ? $params['course_test_result_id'] : null;
      $locale = isset($params['locale']) ? $params['locale'] : app()->getlocale();

      // from another db
      return DB::connection(config('project.db_util_connection'))->table('course_tests')
        ->join('aiaacademy_db.course_tests_results','aiaacademy_util_db.course_tests.course_test_result_id','aiaacademy_db.course_tests_results.id')
        ->join('aiaacademy_db.courses','aiaacademy_db.course_tests_results.course_id','aiaacademy_db.courses.id')
        ->join('aiaacademy_db.courses_translations','aiaacademy_db.courses.id','aiaacademy_db.courses_translations.course_id')
        ->join('aiaacademy_db.course_questions','aiaacademy_util_db.course_tests.question_id','aiaacademy_db.course_questions.id')
        ->join('aiaacademy_db.course_questions_translations','aiaacademy_db.course_questions.id','aiaacademy_db.course_questions_translations.question_id')
        ->join('aiaacademy_db.course_answers','aiaacademy_db.course_questions.id','aiaacademy_db.course_answers.question_id')
        ->join('aiaacademy_db.course_answers_translations','aiaacademy_db.course_answers.id','aiaacademy_db.course_answers_translations.answer_id')
        ->where('aiaacademy_util_db.course_tests.course_test_result_id',$courseTestTesultId)
        ->where('aiaacademy_db.courses_translations.locale', $locale)
        ->where('aiaacademy_db.course_answers_translations.locale', $locale)
        ->where('aiaacademy_db.course_questions_translations.locale', $locale)
        ->orderBy('aiaacademy_db.course_questions.sequence')
        ->orderBy('aiaacademy_db.course_answers.sequence')
        ->select('aiaacademy_util_db.course_tests.answer_id as user_answer_id','aiaacademy_db.courses_translations.name as course_name',
          'aiaacademy_db.course_questions.correct_answer',
          'aiaacademy_db.course_questions.id as course_question_id','aiaacademy_db.course_answers.id as course_answer_id',
          'aiaacademy_db.course_questions_translations.name as question_title',
          'aiaacademy_db.course_answers_translations.name as answers_title')->get()->groupBy('course_question_id');


      // from the same db
      // return DB::table('course_tests')
      //   ->join('course_tests_results','course_tests.course_test_result_id','course_tests_results.id')
      //   ->join('courses','course_tests_results.course_id','courses.id')
      //   ->join('courses_translations','courses.id','courses_translations.course_id')
      //   ->join('course_questions','course_tests.question_id','course_questions.id')
      //   ->join('course_questions_translations','course_questions.id','course_questions_translations.question_id')
      //   ->join('course_answers','course_questions.id','course_answers.question_id')
      //   ->join('course_answers_translations','course_answers.id','course_answers_translations.answer_id')
      //   ->where('course_tests.course_test_result_id',$courseTestTesultId)
      //   ->where('courses_translations.locale', $locale)
      //   ->where('course_answers_translations.locale', $locale)
      //   ->where('course_questions_translations.locale', $locale)
      //   ->orderBy('course_questions.sequence')
      //   ->orderBy('course_answers.sequence')
      //   ->select('course_tests.answer_id as user_answer_id','courses_translations.name as course_name',
      //     'course_questions.correct_answer',
      //     'course_questions.id as course_question_id','course_answers.id as course_answer_id',
      //     'course_questions_translations.name as question_title',
      //     'course_answers_translations.name as answers_title')->get()->groupBy('course_question_id');

  }
  public function renderUserTestResultAnswers($params = [])
  {
      $data = $this->getUserTestResultAnswers($params);
      return view('common.members.user-courses', ['detailsType' => 'USER_TEST_RESULT_ANSWERS', 'data' => $data])->render();
  }



  public function getActiveCourses($params = [])
  {
      // الدورات الفعالة وتم فتح الاختبار لها
      $siteId = isset($params['siteId']) ? $params['siteId'] : null;
      $count = isset($params['count']) ? $params['count'] : null;
      $locale = isset($params['locale']) ? $params['locale'] : app()->getlocale();

      $data = DB::table('courses')
          ->join('courses_translations','courses_translations.course_id','courses.id')
          ->Join('course_site', function($join) {
              $join->on('course_site.course_id', 'courses.id');//->where('course_site.main_site', 1);
          })
          ->join('sites','sites.id','course_site.site_id')
          ->join('sites_translations','sites_translations.site_id','sites.id')
          ->where('courses_translations.locale',$locale)
          ->where('sites_translations.locale',$locale)
          ->whereNULL('courses.deleted_at')
          ->where('courses.exam_at' ,'<=', date('Y-m-d') )
          // ->where('courses.exam_at','!=', Null)
          ->where('courses.status',1)
          ->when( $siteId , function($q) use($siteId) {
              return $q->where('sites.id', $siteId);
          })
          ->select(
            'courses_translations.name as course_name','courses_translations.alias as course_alias',
            'sites_translations.name as site_name','sites_translations.alias as site_alias','sites.id as site_id','courses.id as course_id'
          );

          if ($count){
            return $data->count();
          }

          return $data->get();

  }
  public function getCourses($params = [])
  {
      // كل الدورات
      $siteId = isset($params['siteId']) ? $params['siteId'] : null;
      $count = isset($params['count']) ? $params['count'] : null;
      $locale = isset($params['locale']) ? $params['locale'] : app()->getlocale();

      $data = DB::table('courses')
          ->join('courses_translations','courses_translations.course_id','courses.id')
          ->Join('course_site', function($join) {
              $join->on('course_site.course_id', 'courses.id');//->where('course_site.main_site', 1);
          })
          ->join('sites','sites.id','course_site.site_id')
          ->join('sites_translations','sites_translations.site_id','sites.id')
          ->where('courses_translations.locale',$locale)
          ->where('sites_translations.locale',$locale)
          ->whereNULL('courses.deleted_at')
          ->when( $siteId , function($q) use($siteId) {
              return $q->where('sites.id', $siteId);
          })
          ->select(
            'courses_translations.name as course_name','courses_translations.alias as course_alias',
            'sites_translations.name as site_name','sites_translations.alias as site_alias','sites.id as site_id','courses.id as course_id'
          );

          if ($count){
            return $data->count();
          }

          return $data->get();

  }
  public function getValidCourses($params = [])
  {
      // الدورات الفعالة فقط ولا يشترط ان تكون  تاريخ اختبارها قبل تاريخ اليوم
      // هيا فقط ليست ملغاه وحالتها 1
      $siteId = isset($params['siteId']) ? $params['siteId'] : null;
      $count = isset($params['count']) ? $params['count'] : null;
      $locale = isset($params['locale']) ? $params['locale'] : app()->getlocale();

      $data = DB::table('courses')
          ->join('courses_translations','courses_translations.course_id','courses.id')
          ->Join('course_site', function($join) {
              $join->on('course_site.course_id', 'courses.id');//->where('course_site.main_site', 1);
          })
          ->join('sites','sites.id','course_site.site_id')
          ->join('sites_translations','sites_translations.site_id','sites.id')
          ->where('courses_translations.locale',$locale)
          ->where('sites_translations.locale',$locale)
          ->whereNULL('courses.deleted_at')
          ->where('courses.status',1)
          ->when( $siteId , function($q) use($siteId) {
              return $q->where('sites.id', $siteId);
          })
          ->select(
            'courses_translations.name as course_name','courses_translations.alias as course_alias',
            'sites_translations.name as site_name','sites_translations.alias as site_alias','sites.id as site_id','courses.id as course_id'
          );

          if ($count){
            return $data->count();
          }

          return $data->get();

  }

  public function siteNotCompleted($params = [])
  {

      // هل تم فتح جميع الاختبارات فى الدبلوم
      $siteId = isset($params['site_id']) ? $params['site_id'] : null;
      $count = isset($params['count']) ? $params['count'] : null;
      $exists = isset($params['exists']) ? $params['exists'] : null;

      $data = DB::table('courses')
        ->join('course_site','course_site.course_id','courses.id')
        ->where('course_site.site_id',$siteId)
        ->whereNULL('courses.deleted_at')
        ->where(function($q){
            $q->whereNull('exam_at')
            ->orwhere('courses.exam_at', '>', date('Y-m-d H:i:s'))
            ->orwhere('exam_approved', 0);
        })
        ->select('courses.id');

        if ($count){ return $data->count(); }
        if ($exists){ return $data->first(); }

        return $data->get();

  }

  public function isUserSubscribedInSite($user, $site, $params=[])
  {
      // هل الطالب مشترك فى دبلوم معين
      return $user->sites()->where('id',$site->id)->exixts();
  }

  public function getUserSubsInSite($user, $site, $params=[])
  {
      if (!$user) {
        return 0;
      }
      // اشتراكات الطالب فى دبلوم معين  (course_subscriptions)
      $count = isset($params['count']) ? $params['count'] : null;

      $data = $user->courses()->join('course_site', function($join) use($site){
              $join->on('course_site.course_id', '=', 'course_subscriptions.course_id')
                  ->where('course_site.site_id', $site->id);
          });

      if ($count) { return $data->count(); }
      return $data->get();

  }

  public function userGetXtraTray($user, $course_id)
  {
      return DB::table('members_extra_trays')->where('user_email',$user->email)->where('course_id', $course_id)->exists();
  }

  // old but used
  // same function : Auth::guard('web')->user()->testsCount($site_id)
  // public function getUserTestsCountInSite($user, $site_id)
  // {
  //     // عدد اختبارات الطالب فى دبلوم معين
  //     return DB::Table('course_tests_results')
  //     ->join('course_site','course_tests_results.course_id','course_site.course_id')
  //     ->where('user_id',$user->id)
  //     ->where('course_site.site_id',$site_id)
  //     ->select('course_tests_results.id')
  //     ->groupBy('course_site.site_id')
  //     ->groupBy('course_site.course_id')
  //     ->get()->count();
  // }

  public function getCountSuccessedUsersInEachCountryOfSite($params=[])
  {

      // Cache::forget('countSuccessedUsersInEachCountryOfSite_'.$params['site_id']);

      $seconds = 60*60*6; // 12 houres
      return Cache::remember('countSuccessedUsersInEachCountryOfSite_'.$params['site_id'], $seconds, function() use($params) {
        return DB::Table('member_sites_results')
            ->join('members','members.id','member_sites_results.user_id')
            ->join('countries','members.country_id','countries.id')
            ->where('member_sites_results.user_successed', 1)
            ->where('member_sites_results.site_id', $params['site_id'])
            ->groupBy('countries.id')
            ->select('countries.id','countries.nicename','countries.flag',DB::raw('count(*) as count_success'))
            ->orderBy('countries.sort')
            ->get();

      });

  }

  public function getUserTestsCountInCourse($user, $course)
  {
      // عدد اختبارات الطالب فى دورة معينة
      return $user->test_results()->where('course_id', $course->id)->count();
  }

  public static function generateRandomString($length, $params = [])
  {
      $upper = isset($params['upper']) ? true : false;

      if ($upper){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      } else {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      }

      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

  public function userSucessInTestToGetEjaza($courseTestResult)
  {
      if ($courseTestResult->degree < $this->pointOfSuccessEjaza_289){
          return false;
      }
      return true;
  }

  public function userSucessInVisualTestToGetEjaza($courseTestResult)
  {
      $courseVisual = \App\CourseTestVisual::where('site_id', $courseTestResult->site_id)
        ->where('course_id', $courseTestResult->course_id)
        ->where('user_id', $courseTestResult->user_id)
        ->first();

      if (! $courseVisual ){
        return 'no_file'; // false
      }

      // 1 success       2 faild      0 not corrected yet
      if ( $courseVisual->rate === '2' ){
        return 'faild';
      }

      if ( $courseVisual->rate !== '1' ){
        return false;
      }

      return true;
  }


  public function getRealCountriesOnly()
  {
      return DB::table('countries')->where('is_default', 0)->get();
  }



}
