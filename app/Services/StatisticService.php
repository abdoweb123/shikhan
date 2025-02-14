<?php

namespace App\Services;
use DB;

class StatisticService
{

  private $stage;
  private $siteId;
  private $from;
  private $to;

  public function whereStage($stage)
  {
      $this->stage = $stage;
      return $this;
  }

  public function whereSiteId($siteId)
  {
      $this->siteId = $siteId;
      return $this;
  }

  public function from($from)
  {
      $this->from = $from;
      return $this;
  }

  public function to($to)
  {
      $this->to = $to;
      return $this;
  }


  /////////////////////////////////////// Site //////////////////////////////////////

  // عدد الاختبارات فى دبلوم بالتكرار
  public function getTestsCountOfSite_WithRepeated()
  {
      $siteId = $this->siteId;

      return  DB::select("select COUNT(*) as total from course_tests_results
                join course_site on course_tests_results.course_id = course_site.course_id
                where course_site.site_id = $siteId
              ");
  }

  // عدد الاخنبارات فى دبلوم فى توقيت بالتكرار
  public function getTestsCountOfSiteOfPeriod_WithRepeated()
  {
      $siteId = $this->siteId;
      $from = $this->from;
      $to = $this->to;

      return  DB::select("Select COUNT(*) as total from course_tests_results
                join course_site on course_tests_results.course_id = course_site.course_id
                where course_site.site_id = $siteId
                and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
              ");

  }




  // عدد الاختبارات الناجحة فى دبلوم
  public function getSuccessedTestsCountOfSite()
  {
      $siteId = $this->siteId;

      /* select count(id) as total from
        ( select course_tests_results.id from course_tests_results
            JOIN course_site on course_tests_results.course_id = course_site.course_id
            where course_site.site_id = $siteId
            and degree >= 50.00 GROUP by course_tests_results.user_id, course_tests_results.course_id
        ) count_cirts
      */

      return DB::select("
        SELECT count(*) as total FROM `member_courses_results` WHERE test_degree >= 50 and site_id = $siteId
      ");

  }


  // عدد الاختبارات الناجحة فى دبلوم فى فترة
  public function getSuccessedTestsCountOfSiteOfPeriod()
  {
      $siteId = $this->siteId;
      $from = $this->from;
      $to = $this->to;
      /*
      select count(id) as total from
        ( select course_tests_results.id from course_tests_results
            JOIN course_site on course_tests_results.course_id = course_site.course_id
            where course_site.site_id = $site->id
            and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
            and degree >= 50.00 GROUP by course_tests_results.user_id, course_tests_results.course_id
        ) count_cirts
      */

      return DB::select("
        SELECT count(*) as total FROM `member_courses_results`
        WHERE site_id = $siteId
        and test_degree >= 50
        and test_created_at >= '$from' and test_created_at  <= '$to'
      ");

  }






  // عدد الناجحين فى دبلوم
  public function getSuccessedUsersCountOfSite()
  {
      $siteId = $this->siteId;

      /*
        Select count(*) FROM all_results_max WHERE site_id = sites.id
      */

      return  DB::select("Select count(*) as total FROM
                `member_sites_results`
                WHERE user_successed = 1
                and site_id = $siteId
              ");

  }

  // عدد الناجحين فى دبلوم فى توقيت
  public function getSuccessedUsersCountOfSiteOfPeriod()
  {
      $siteId = $this->siteId;
      $from = $this->from;
      $to = $this->to;

      /*
        Select count(*) FROM all_results_max WHERE site_id = sites.id and max_created_at >= '$from' and max_created_at <= '$to'
      */

      return  DB::select("Select count(*) as total FROM `member_sites_results`
                  WHERE user_successed = 1 and
                  site_id = $siteId
                  and user_max_test_datetime >= '$from' and user_max_test_datetime <= '$to'
              ");

  }





  // اجمالى المختبرين فى دبلوم
  public function getTestedUsersCountOfSite()
  {
    $siteId = $this->siteId;

    /*
      SELECT COUNT(*) from course_tests_results
      join course_site on course_tests_results.course_id = course_site.course_id
      where course_site.site_id = sites.id
      GROUP by course_site.site_id
    */

    return  DB::select("Select count(DISTINCT(user_id)) as total
                FROM `member_courses_results`
                WHERE site_id = $siteId
            ");

  }

  // اجمالى المختبرين فى دبلوم فى توفيت
  public function getTestedUsersCountOfSiteOfPeriod()
  {
    $siteId = $this->siteId;
    $from = $this->from;
    $to = $this->to;

    /*
      SELECT COUNT(DISTINCT(user_id)) from course_tests_results
      join course_site on course_tests_results.course_id = course_site.course_id
      where course_site.site_id = sites.id
      and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
      GROUP by course_site.site_id
    */

    return  DB::select("Select
                  count(DISTINCT(user_id)) as total FROM `member_courses_results`
                  WHERE site_id = $siteId
                  and test_created_at >= '$from' and test_created_at <= '$to'
            ");
  }









  /////////////////////////////////////// Stage //////////////////////////////////////

  // عدد الاختبارات فى مرحلة بدون تكرار
  public function getTestsCountOfStage()
  {
      $stage = $this->stage;

      // return DB::select("
      //   select count(*) as total from
      //     (
      //       select course_tests_results.id
      //       FROM course_tests_results
      //       join sites on sites.id = course_tests_results.site_id
      //       where sites.new_flag = $stage
      //       GROUP by user_id, course_id
      //     ) a");

      // return DB::select("
      //   select count(*) as total from
      //     (
      //       select course_tests_results.id
      //       FROM course_tests_results
      //       join course_site on course_tests_results.course_id = course_site.course_id
      //       join sites on sites.id = course_site.site_id
      //       where sites.new_flag = $stage
      //       GROUP by course_tests_results.user_id, course_tests_results.course_id
      //     ) a");


      // return DB::select("
      //   select count(*) as total from
      //    (
      //       select member_courses_results.id
      //       FROM member_courses_results
      //       where member_courses_results.site_new_flag = $oldOrNew
      //       GROUP by user_id, course_id
      //     ) a
      //   ");

      return DB::select("
            select count(DISTINCT(test_id)) as total from member_courses_results
            WHERE site_new_flag = $stage
          ");

  }

  // عدد الاختبارات فى مرحلة فى توقيت بدون تكرار
  public function getTestsCountOfStageOfPeriod()
  {
      $stage = $this->stage;
      $from = $this->from;
      $to = $this->to;

      // return DB::select("
      //   select count(*) as total from
      //     (
      //       select course_tests_results.id
      //       FROM course_tests_results
      //       join course_site on course_tests_results.course_id = course_site.course_id
      //       join sites on sites.id = course_site.site_id
      //       where sites.new_flag = $stage
      //       and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
      //       GROUP by course_tests_results.user_id, course_tests_results.course_id
      //     ) a");

      return DB::select("
            select count(DISTINCT(test_id)) as total from member_courses_results
            WHERE site_new_flag = $stage
            and test_created_at >= '$from' and test_created_at <= '$to'
          ");

  }


  // عدد الاختبارات الناجحة فى مرحلة
  // عدد الشهادات
  public function getSuccessedTestsCountOfStage()
  {

       $stage = $this->stage;

       // return DB::select("
       //   select count(id) as total from
       //     ( select course_tests_results.id from course_tests_results
       //       join sites on sites.id = course_tests_results.site_id
       //       where sites.new_flag = $oldOrNew
       //       and degree >= 50.00
       //       GROUP by course_tests_results.user_id, course_tests_results.course_id ) count_cirts
       //   ");

       // bad performance
       // return DB::select("
       //   select count(id) as total from
       //     ( select member_courses_results.id from member_courses_results
       //       where test_degree >= 50.00 and member_courses_results.site_new_flag  = $oldOrNew
       //       GROUP by member_courses_results.user_id, member_courses_results.course_id
       //      ) count_cirts
       //   ");

       // return DB::select("select count(DISTINCT user_id,course_id ) as total
       //    from member_courses_results where test_degree >= 50.00 and member_courses_results.site_new_flag = $oldOrNew");

       //
       // return DB::select("
       //    select count(DISTINCT(test_id)) ");

       return DB::select("select count(DISTINCT(test_id)) as total
          from member_courses_results where test_degree >= 50.00 and site_new_flag = $stage");


  }

  // عدد الاختبارات الناجحة فى مرحلة فى فترة
  // عدد الشهادات
  public function getSuccessedTestsCountOfStageOfPeriod()
  {

       $stage = $this->stage;
       $from = $this->from;
       $to = $this->to;

       // return DB::select("
       //   select count(id) as total from
       //     ( select course_tests_results.id from course_tests_results
       //       join sites on sites.id = course_tests_results.site_id
       //       where sites.new_flag = $oldOrNew
       //       and degree >= 50.00
       //       GROUP by course_tests_results.user_id, course_tests_results.course_id ) count_cirts
       //   ");

       // bad performance
       // return DB::select("
       //   select count(id) as total from
       //     ( select member_courses_results.id from member_courses_results
       //       where test_degree >= 50.00 and member_courses_results.site_new_flag  = $oldOrNew
       //       GROUP by member_courses_results.user_id, member_courses_results.course_id
       //      ) count_cirts
       //   ");

       // return DB::select("select count(DISTINCT user_id,course_id ) as total
       //    from member_courses_results where test_degree >= 50.00 and member_courses_results.site_new_flag = $oldOrNew");

       //
       // return DB::select("
       //    select count(DISTINCT(test_id)) ");

       return DB::select("select count(DISTINCT(test_id)) as total
            from member_courses_results where test_degree >= 50.00 and site_new_flag = $stage
            and test_created_at >= '$from' and test_created_at <= '$to'
          ");


  }





  // اجمالى عدد المختبرين فى المرحلة بدون تكرار
  public function getTestedUsersCountOfStage()
  {

       $stage = $this->stage;

       // return  DB::select("
       //     select count(DISTINCT(user_id)) as total
       //     FROM course_tests_results
       //     join sites on sites.id = course_tests_results.site_id
       //     where sites.new_flag = $stage
       //   ");

       return DB::select("
            select count(DISTINCT(user_id)) as total from member_courses_results
            where site_new_flag = $stage
          ");

  }

  // اجمالى عدد المختبرين فى المرحلة فى فترة بدون تكرار
  public function getTestedUsersCountOfStageOfPeriod()
  {
      $stage = $this->stage;
      $from = $this->from;
      $to = $this->to;

      // return DB::select("
      //   select count(DISTINCT(course_tests_results.user_id)) as total FROM course_tests_results
      //   join sites on sites.id = course_tests_results.site_id
      //   where sites.new_flag = $stage
      //   and course_tests_results.created_at >= '$from' and course_tests_results.created_at <= '$to'
      // ");

      return DB::select("
           select count(DISTINCT(user_id)) as total from member_courses_results
           where site_new_flag = $stage
           and test_created_at >= '$from' and test_created_at <= '$to'
         ");
  }




  // اجمالى عدد الناجحين فى المرحلة بدون تكرار
  public function getSuccessdUsersCountOfStage()
  {
      $stage = $this->stage;

      // return DB::select("
      //       select count(*) as total from
      //       (
      //         Select DISTINCT(user_id) FROM all_results_max
      //         join sites on sites.id = all_results_max.site_id
      //         where sites.new_flag = $oldOrNew
      //       ) all_successs
      //     ");
      //
      return DB::select("
            Select count(DISTINCT(user_id)) as total FROM member_sites_results
            where user_successed = 1 and site_new_flag = $stage
          ");

  }

  public function getSuccessdUsersCountOfStageOfPeriod()
  {
      $stage = $this->stage;
      $from = $this->from;
      $to = $this->to;

      // return DB::select("
      //       select count(*) as total from
      //       (
      //         Select DISTINCT(user_id) FROM all_results_max
      //         join sites on sites.id = all_results_max.site_id
      //         where sites.new_flag = $oldOrNew
      //       ) all_successs
      //     ");

      return DB::select("
            Select count(DISTINCT(user_id)) as total FROM member_sites_results
            where user_successed = 1 and site_new_flag = $stage
            and user_max_test_datetime >= '$from' and user_max_test_datetime <= '$to'
          ");
  }




  // عدد الدورات فى المرحلة
  public function getCoursesCountOfStage()
  {
    $stage = $this->stage;

    // return DB::select("
    //     Select count(*) as total from
    //          ( select course_site.course_id as total FROM courses
    //            join course_site on course_site.course_id = courses.id
    //            join sites on sites.id = course_site.site_id
    //            where sites.new_flag = $stage
    //            GROUP by course_site.course_id
    //          ) courses
    //     ");

    return DB::select("
          select count(DISTINCT(course_site.course_id)) as total FROM courses
            join course_site on course_site.course_id = courses.id
            join sites on sites.id = course_site.site_id
            where sites.new_flag = $stage
        ");

  }

  // عدد الدورات فى المرحلة فى فترة
  public function getCoursesCountActiveOfStage()
  {
    $stage = $this->stage;

    // return DB::select("
    //       select courses.id as total FROM courses
    //       join course_site on course_site.course_id = courses.id
    //       join sites on sites.id = course_site.site_id
    //       where courses.status = 1 and
    //       courses.deleted_at is null and
    //       courses.exam_at < now() and
    //       sites.new_flag = $oldOrNew
    //       GROUP by course_site.course_id
    //     ");

    return DB::select("
          select count(DISTINCT(course_site.course_id)) as total FROM courses
            join course_site on course_site.course_id = courses.id
            join sites on sites.id = course_site.site_id
            where sites.new_flag = $stage
            and courses.status = 1
            and courses.deleted_at is null
            and courses.exam_at < now()
        ");

  }




  // عدد الاختبارات الناجحة اى بتكرار الشخص
  //  عدد الدبلومات التى تم اجتيازاها بتكرار الدبلومات فى مرحلة
  public function getSuccessdSitesTestsCountOfStage()
  {
      $stage = $this->stage;

      return DB::select("
          SELECT count(*) as total FROM `member_sites_results`
          WHERE user_successed = 1 and site_new_flag = $stage
      ");

  }

  // عدد الاختبارات الناجحة اى بتكرار الشخص
  // عدد الدبلومات التى تم اجتيازاها بتكرار الدبلومات فى مرحلة فى فترة
  public function getSuccessdSitesTestsCountOfStageOfPeriod()
  {

      $stage = $this->stage;
      $from = $this->from;
      $to = $this->to;

      return DB::select("
          SELECT count(*) as total FROM `member_sites_results`
          WHERE user_successed = 1 and site_new_flag = $stage
          and user_max_test_datetime >= '$from' and user_max_test_datetime <= '$to'
      ");

  }




  // عدد الاشخاص الذين اجتازو دبلوم واحد فقط فى مرحلة
  public function getSuccessdUsersInOneDiplomeCountOfStage()
  {
      $stage = $this->stage;

      // return DB::select("
      //     select count(DISTINCT(user_id)) as total from (
      //         Select user_id, count(user_id) as users_tests_count FROM member_sites_results
      //         where member_sites_results.user_successed = 1 and member_sites_results.site_new_flag = $stage
      //         group By(user_id)
      //         having users_tests_count = 1 ) a
      // ");

      return DB::select("
          select count(user_id) as total from (
              Select user_id, count(user_id) as users_tests_count FROM member_sites_results
              where member_sites_results.user_successed = 1 and member_sites_results.site_new_flag = $stage
              group By(user_id)
              having users_tests_count = 1 ) a
      ");

  }

  // عدد الاشخاص الذين اجتازو دبلوم واحد فقط فى مرحلة فى فترة
  public function getSuccessdUsersInOneDiplomeCountOfStageOfPeriod()
  {
      $stage = $this->stage;
      $from = $this->from;
      $to = $this->to;

      // return DB::select("
      //     select count(DISTINCT(user_id)) as total from (
      //         Select user_id, count(user_id) as users_tests_count FROM member_sites_results
      //         where user_successed = 1 and site_new_flag = $stage
      //         and user_max_test_datetime >= '$from' and user_max_test_datetime <= '$to'
      //         group By(user_id)
      //         having users_tests_count = 1 ) a
      // ");

      return DB::select("
          select count(user_id) as total from (
              Select user_id, count(user_id) as users_tests_count FROM member_sites_results
              where user_successed = 1 and site_new_flag = $stage
              and user_max_test_datetime >= '$from' and user_max_test_datetime <= '$to'
              group By(user_id)
              having users_tests_count = 1 ) a
      ");

  }




  // عدد الطلاب الذين اجتازو اكتر من دبلوم فى مرحلة
  public function getSuccessdUsersMoreThanOneDiplomeCountOfStage()
  {
      $stage = $this->stage;

      // return DB::select("
      //     select count(DISTINCT(user_id)) as total from (
      //         Select user_id, count(user_id) as users_tests_count FROM member_sites_results
      //         where member_sites_results.user_successed = 1 and member_sites_results.site_new_flag = $stage
      //         group By(user_id)
      //         having users_tests_count > 1 ) a
      // ");

      return DB::select("
          select count(user_id) as total from (
              Select user_id, count(user_id) as users_tests_count FROM member_sites_results
              where member_sites_results.user_successed = 1 and member_sites_results.site_new_flag = $stage
              group By(user_id)
              having users_tests_count > 1 ) a
      ");

  }

  // عدد الطلاب الذين اجتازو اكتر من دبلوم فى مرحلة فى فترة
  public function getSuccessdUsersMoreThanOneDiplomeCountOfStageOfPeriod()
  {

      $stage = $this->stage;
      $from = $this->from;
      $to = $this->to;

      // return DB::select("
      //     select count(DISTINCT(user_id)) as total from (
      //         Select user_id, count(user_id) as users_tests_count FROM member_sites_results
      //         where user_successed = 1 and site_new_flag = $stage
      //         and user_max_test_datetime >= '$from' and user_max_test_datetime <= '$to'
      //         group By(user_id)
      //         having users_tests_count > 1 ) a
      // ");

      return DB::select("
          select count(user_id) as total from (
              Select user_id, count(user_id) as users_tests_count FROM member_sites_results
              where user_successed = 1 and site_new_flag = $stage
              and user_max_test_datetime >= '$from' and user_max_test_datetime <= '$to'
              group By(user_id)
              having users_tests_count > 1 ) a
      ");

  }




  // عدد الطلاب
  public function getMembersCount()
  {

      return DB::select("
          select count(*) as total FROM members
      ");

  }

  // عدد الطلاب الذين اشتركو فى فترة معين
  public function getMembersCountOfPeriod()
  {
      $from = $this->from;
      $to = $this->to;

      return DB::select("
          select count(*) as total FROM members where created_at >= '$from' and created_at <= '$to'
      ");

  }






}
