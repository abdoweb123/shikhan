<?php

namespace App\Services;
use DB;

class StaService_not
{


  // public function getCourseSubsCountSql($course_id)
  // {
  //     return "select COUNT(*) as total from course_subscriptions where course_id = $course_id";
  // }
  // public function incremantCourseSubsCount($course_id)
  // {
  //     // عدد الاشتراكات فى الدبلوم
  //     $count = DB::select( $this->getCourseSubsCountSql($course_id) )[0]->total;
  //     DB::Table('courses_sta')->where('course_id', $course_id)->update(['subs_count' => $count]);
  // }
  //
  // public function incremantCourseSubsUsersCount($course_id)
  // {
  //     // عدد الطلاب المشتركين فى الدبلوم
  //     DB::Table('sites_sta')->where('site_id',$site_id)->increment('subs_users_count');
  // }




  public function getSiteSubsCountSql($site_id)
  {
      return "select COUNT(*) as total from course_subscriptions
       join course_site on course_subscriptions.course_id = course_site.course_id
       where course_site.site_id = $site_id
       GROUP by course_site.site_id";
  }
  public function incremantSiteSubsCount($site_id)
  {
      // عدد الاشتراكات فى الدبلوم
      $count = DB::select( $this->getSiteSubsCountSql($site_id) );
      if (count($count)){
        DB::Table('sites_sta')->where('site_id', $site_id)->update(['subs_count' => $count[0]->total]);
      }
  }





  public function incremantSiteSubsUsersCount($site_id)
  {
      // عدد الطلاب المشتركين فى الدبلوم
      DB::Table('sites_sta')->where('site_id',$site_id)->increment('subs_users_count');
  }





  public function getSiteTestsCountSql($site_id)
  {
      return "Select COUNT(*) as total from course_tests_results
       join course_site on course_tests_results.course_id = course_site.course_id
       where course_site.site_id = $site_id
       GROUP by course_site.site_id";
  }
  public function incremantSiteTestsCount($site_id)
  {
      // عدد الاختبارات فى الدبلوم
      $count = DB::select( $this->getSiteTestsCountSql($site_id) );
      if (count($count)){
        DB::Table('sites_sta')->where('site_id', $site_id)->update(['tests_count' => $count[0]->total]);
      }
  }

  // public function incremantSiteTestsUsersCount($site_id)
  // {
  //     // عدد الطلاب الذين اختبرو فى الدبلوم
  //     $siteCourses = DB::Table('course_site')->where('site_id',$site_id)->pluck('course_id');
  //
  //
  //     DB::Table('sites_sta')->where('site_id',$site_id)->increment('tested_users_count');
  // }







  public function getSiteCirtsCountSql($site_id)
  {
      return "select count(id) as total from
          (
          select course_tests_results.id from course_tests_results
           JOIN course_site on course_tests_results.course_id = course_site.course_id
           where course_site.site_id = $site_id
           and degree > 50.00 GROUP by course_tests_results.user_id, course_tests_results.course_id
          ) a";

  }
  public function incremantSiteCirtsCount($site_id)
  {
      // عدد الشهادات فى الدبلوم
      $count = DB::select( $this->getSiteCirtsCountSql($site_id) );
      if (count($count)){
        DB::Table('sites_sta')->where('site_id', $site_id)->update(['cirts_count' => $count[0]->total]);
      }
  }






  public function getSiteSuccessedUsersCountSql($site_id)
  {
      return "select count(user_id) as total from (
        select user_id, count(user_id) as total from
            (
              SELECT user_id FROM `course_tests_results`
              JOIN course_site on course_tests_results.course_id = course_site.course_id and course_site.site_id = $site_id
              WHERE course_tests_results.degree > '50:00' GROUP by course_tests_results.course_id , course_tests_results.user_id
            ) a GROUP by user_id HAVING total = (
                                         SELECT COUNT(course_id)	FROM `course_site`
                                 JOIN courses on courses.id = course_site.course_id
                                 where course_site.site_id = $site_id
                                 GROUP by course_site.site_id
                                      )
         ) b";

  }
  public function incremantSiteSuccessedUsersCount($site_id)
  {
      //عدد الناجحين فى الدبلوم
      $count = DB::select( $this->getSiteSuccessedUsersCountSql($site_id) );
      if (count($count)){
        DB::Table('sites_sta')->where('site_id', $site_id)->update(['successed_users_count' => $count[0]->total]);
      }
  }



}
