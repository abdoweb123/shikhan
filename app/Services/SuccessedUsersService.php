<?php

namespace App\Services;
use Illuminate\Support\Arr;
use DB;

class SuccessedUsersService
{

  private $siteId;
  private $siteCoursesCount;
  private $userId;
  private $oldOrNew;

  public function __construct( $params = [] )
  {
      $this->siteId = isset($params['site_id']) ? $params['site_id'] : null;
      $this->siteCoursesCount = isset($params['site_courses_count']) ? $params['site_courses_count'] : null;
      $this->userId = isset($params['user_id']) ? $params['user_id'] : null;
      $this->oldOrNew = isset($params['old_or_new']) ? $params['old_or_new'] : null;
  }

  public function getSucceedUsersInEachSite()
  {

        $sql = "
            Select sites.id, sites.title, sites.new_flag,
              (
                SELECT COUNT(course_id)	FROM `course_site`
                JOIN courses on courses.id = course_site.course_id
                where course_site.site_id = sites.id
                GROUP by course_site.site_id
              ) courses_count ,
              (
                SELECT COUNT(course_id)
                FROM `course_site`
                JOIN courses on courses.id = course_site.course_id
                where course_site.site_id = sites.id

                and courses.status = 1 and
                    courses.exam_approved = 1 and
                    courses.deleted_at is null and
                    courses.exam_at < now()

                GROUP by course_site.site_id
              ) active_courses_count
            FROM sites
         ";

       if($this->oldOrNew !== null){
         $sql = $sql . " where new_flag = $this->oldOrNew ";
       }

       $sites = DB::select($sql);

       foreach ($sites as $site) {
         $this->siteId = $site->id;
         $this->siteCoursesCount = $site->courses_count;
         $site->successedUsers = $this->getSuccessdCoursesOfEachSite();
       }

       return $sites;

  }

  public function getSuccessdCoursesOfEachSite($params = [])
  {

        $count = isset($params['count']) ? $params['count'] : null;

        $data = [];

        // الطلاب الناجحين فى دبلوم معين
        // بالنسبة للدورات المشتركة بين اكثر من دبلوم
        // لو الطالب اشترك فى الدبلوم يتم احتسابه واذا لم يشترك لن يتم احتسابه
        if ( $this->siteId ){ // اعداد الناجحين فقط
          if ($count){
            $data = DB::select("
                Select count(*) as total from (
                  Select
                  site_subscriptions.user_id, site_subscriptions.site_id, count(*) as total
                  FROM `users_results`
                  JOIN site_subscriptions on site_subscriptions.site_id = users_results.site_id
                    and site_subscriptions.user_id = users_results.user_id
                  where max_degree >= '50:00' and users_results.site_id = $this->siteId
                  GROUP by users_results.user_id, users_results.site_id
                  HAVING total = $this->siteCoursesCount
                ) a
            ")[0]->total;
          } else {  // السجلات نفسها
            $data = DB::select("
                Select
                site_subscriptions.user_id, site_subscriptions.site_id, count(*) as total
                FROM `users_results`
                JOIN site_subscriptions on site_subscriptions.site_id = users_results.site_id
                  and site_subscriptions.user_id = users_results.user_id
                where max_degree >= '50:00' and users_results.site_id = $this->siteId
                GROUP by users_results.user_id, users_results.site_id
                HAVING total = $this->siteCoursesCount
            ");
          }
        }

        return $data;

  }

  public function getCountSuccessdCoursesOfAllSites()
  {
      // عدد النجاحات فى الدبلومات كلها
      $count = 0;
      foreach ($this->getSucceedUsersInEachSite() as $site) {
          $count = $count + $site->successedUsers;
      }
      return $count;
  }

  public function getSuccessdUsersOfAllSites()
  {
      // اذا الطالب ناجح فى اكتر من الدبلوم يحسب طالب واحد فقط
      // عدد الاشخاص الذين اجتازوا دبلوم أو أكثر
      $count = 0;
      $allUsers = collect();

      foreach ($this->getSucceedUsersInEachSite() as $site) {
          $allUsers = $allUsers->merge(collect($site->successedUsers));
      }

      return $allUsers->groupBy('user_id');

  }

  public function getCountSuccessdUsersOfAllSites()
  {
      return $this->getSuccessdUsersOfAllSites()->count();

  }

}
