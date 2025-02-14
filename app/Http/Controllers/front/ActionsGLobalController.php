<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use DB;
use App\course_subscription;
use App\member;

class ActionsGLobalController extends Controller
{


  //كل من اشترك فى  كورس واحد مت اى دبلوم يتم اشراكه فى الدبلوم كاملا
  public function SubscribeUsersInCourses(Request $request)
  {


      set_time_limit(0);
      // $selectedUsersIds = DB::table("members")
    	//     ->select("members.id", DB::raw("COUNT(course_subscriptions.user_id) as subscriptions_count"))
    	//     ->join("course_subscriptions","course_subscriptions.user_id","=","members.id")
    	//     ->groupBy(["members.id","course_subscriptions."])
      //     ->having('subscriptions_count','>',60)
    	//     ->pluck('id');
      $members = DB::Table('members')->select('id')->skip(9000)->take(1000)->orderBy('id')->get();
      $sites = DB::Table('sites')->select('id')->get();

      foreach ($members as $member) {
        foreach ($sites as $site) {
            $isUserSubscribedInCourse = DB::Table('course_subscriptions')
               ->join('course_site','course_site.course_id','course_subscriptions.course_id')
               ->where('course_subscriptions.user_id',$member->id)
               ->where('course_site.site_id',$site->id)
               ->where('course_site.main_site',1)
               ->first();

            if ($isUserSubscribedInCourse) {
              $siteCourses = DB::Table('sites')
                ->join('course_site','sites.id','course_site.site_id')
                ->where('sites.id',$site->id)
                ->where('main_site',1)
                ->select('course_site.course_id')
                ->get();

                foreach ($siteCourses as $siteCourse) {
                    $subscription = course_subscription::firstOrCreate(
                        ['user_id' => $member->id, 'course_id' => $siteCourse->course_id]
                    );
                }
            }
        }
        // dd($member);
        // break;
      }

      // $selectedUsersIds = collect($usersWithCountSubscriptionsInEachCOurse)->where('all_subscribtions_count', '>', 67)->pluck('id');

      // $allCoursesIds = DB::Table('courses')->pluck('id');

      // foreach ($selectedUsersIds as $userId) {
      //     foreach ($allCoursesIds as $courseId) {
      //         $subscription = course_subscription::firstOrCreate(
      //             ['user_id' => $userId, 'course_id' => $courseId]
      //         );
      //     }
      //     dd($userId);
      //     break;
      // }

      dd('Inserted Successfully');

  }


}
