<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\site;
use App\course;
use App\LessonOld;
use App\language;
use App\menu_details;
use App\category_description;
use App\category;
use App\course_site;
use App\member;
use App\course_test_result;
use App\MemberSitesResult;

use Illuminate\Support\Facades\Input;
use App\libraries\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Validator;
use Carbon\Carbon;
use App\Http\Controllers\front\PaymobController;
use App\Services\GlobalService;
use App\Traits\CacheTrait;


use File;
use Storage;
use DateTime;

use Mail;

class TempControllor extends Controller
{

    use CacheTrait;

    public function __construct(private GlobalService $globalService)
    {
        // $this->redirectTo = route('diplomas.index'); // route('home');
        // $this->middleware('guest:web');
    }


    public function correctStaticResults(Request $request)
     {


         $rs = new \App\Services\ResultsService();

         // // تسجيل نتائج الكورسات لطالب
         //  $member = \App\member::where('id', 13995)->withTrashed()->first();
         //  $course = course::where('id', 381 )->first();
         //  $courseTestResults = $rs->setUser($member)->setCourse($course)->saveFinalUserCourseResult();
         // //  dd('Courses Done');

         // must be sw because this route(correct...) works befora setlocale routes
         app()->setlocale('sw');

         // تسجيل نتائج الدبلومات لطالب
         // $member = \App\member::where('id', 224 )->withTrashed()->first();
         // $data = $rs->setUser($member)->saveFinalUserSitesResults();

         // dd('Diplomes Done 224');



         // all members
         $members = \App\member::where('id','<', 3000)->where('id','>', 0)->orderby('id')
             ->chunkbyid(50, function($members) use($rs) {
                 foreach ($members as $member) {
                     $data = $rs->setUser($member)->saveFinalUserSitesResults();
                 }
             });



         dd('Diplomes All');



         // $all = DB::select('Select DISTINCT(user_id) FROM `member_sites_results` where user_id in ( SELECT user_id FROM `member_sites_results` GROUP by user_id, site_id HAVING count(*) > 1 ) ');
         // foreach ($all as $record) {
         //   $member = \App\member::where('id', $record->user_id)->withTrashed()->first();
         //   $data = $rs->setUser($member)->saveFinalUserSitesResults();
         // }



         // $data = MemberSitesResult::where('site_id', 28)->chunkById(10, function ($records) use($rs){
         //     foreach ($records as $record) {
         //       $member = \App\member::where('id', $record)->withTrashed()->first();
         //       $data = $rs->setUser($member)->saveFinalUserSitesResults();
         //     }
         // });

         dd('Diplomes Done ');

     }

    public function t1(Request $request)
    {


      $u = member::find(39);
      dd(
        $this->globalService->getUserCoursesMaxDegrees($u),
        (new \App\Services\ResultsService())->setUser($u)->getUserSuccesseCources($u->id),
      );

      dd($site->courses()->select('courses.id','courses.title','courses.status','link_ended','link')
          ->with(['terms' => function($q) use($site){
              $q->where('site_id', $site->id);
          }])->get());


      dd($site->courses);




      $start = microtime(true);
      // site::find(11)->courses()->where('status', 1)->select('courses.id','courses.title','logo','exam_at','exam_approved','courses.new_flag')->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get();

      // site::find(11)->courses()->where('status', 1)->orderBy( DB::raw('ISNULL(sort), sort'), 'ASC' )->get();

      $time = microtime(true) - $start;
      dd($time);







    }




}
