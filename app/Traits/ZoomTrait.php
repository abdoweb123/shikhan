<?php

namespace App\Traits;
use DB;

trait ZoomTrait
{

    public function getCourseWillZoomToday()
    {
        return DB::Table('sites') // courses_translations
          ->join('sites_translations', 'sites.id', 'sites_translations.site_id')
          ->join('course_site', 'sites.id', 'course_site.site_id')
          ->join('courses', 'courses.id', 'course_site.course_id')
          ->join('courses_translations', 'courses.id', 'courses_translations.course_id')
          ->join('lessons', 'lessons.course_id', 'courses.id')
          ->join('lesson_translations', 'lesson_translations.lesson_id', 'lessons.id')

          ->where('courses_translations.date_at',  date('Y-m-d')) // '2022-08-06'
          ->where('courses_translations.locale', app()->getlocale())
          ->wherenull('courses_translations.deleted_at')

          ->where('sites.status', 1)
          ->whereNULL('sites.deleted_at')

          ->where('courses.status', 1)
          ->whereNULL('courses.deleted_at')

          ->where('sites_translations.locale', app()->getlocale())

          ->where('lessons.is_active', 1)
          ->where('lesson_translations.trans_status', 1)
          ->where('lesson_translations.locale', app()->getlocale())

          ->select('sites_translations.slug as site_alias','courses_translations.name', 'courses_translations.alias as course_alias',
              'lesson_translations.link_zoom', 'courses_translations.date_at')->first();

    }

    public function getLastCourseByDateAtOfSite($site)
    {
        // اخر دورة فى الدبلوم مرتبة بتاريخ البث
        return DB::Table('sites') // courses_translations
          // ->join('sites_translations', 'sites.id', 'sites_translations.site_id')
          ->join('course_site', 'sites.id', 'course_site.site_id')
          ->join('courses', 'courses.id', 'course_site.course_id')
          ->join('courses_translations', 'courses.id', 'courses_translations.course_id')
          ->join('lessons', 'lessons.course_id', 'courses.id')
          ->join('lesson_translations', 'lesson_translations.lesson_id', 'lessons.id')

          ->where('sites.id', $site->id)
          ->where('courses_translations.date_at', '>=', date('Y-m-d')) // '2022-08-06'
          ->where('courses_translations.locale', app()->getlocale())
          ->wherenull('courses_translations.deleted_at')
          ->wherenotnull('courses_translations.date_at')

          ->where('sites.status', 1)
          ->whereNULL('sites.deleted_at')

          ->where('courses.status', 1)
          ->whereNULL('courses.deleted_at')

          // ->where('sites_translations.locale', app()->getlocale())

          ->where('lessons.is_active', 1)
          ->where('lesson_translations.trans_status', 1)
          ->where('lesson_translations.locale', app()->getlocale())

          ->select('courses_translations.name', 'lesson_translations.link_zoom', 'courses_translations.date_at')
          ->orderBy('courses_translations.date_at', 'ASC')
          ->first();

    }

    public function courseZoomToday($course)
    {
        // هل بث الزووم اليوم
        return date('Y-m-d' ,strtotime($course->date_at)) == date('Y-m-d');
    }

    public function courseZoomAfterToday($course)
    {
        // هل بث الزووم مستقبلا
        return date('Y-m-d' ,strtotime($course->date_at))  > date('Y-m-d');
    }

    public function courseZoomBeforeToday($course)
    {
        // هل بث الزووم انتهى
        return date('Y-m-d' ,strtotime($course->date_at))  < date('Y-m-d');
    }

    public function courseZoomDayStatus($course)
    {
        if (! $course){
          return null;
        }

        if (! $course->date_at){
          return null;
        }

        if ( $this->courseZoomToday($course) ){
            return __('trans.onair_date_mecca');
        }

        if ( $this->courseZoomAfterToday($course) ){
            return  __('trans.onair_date') . $course->date_at;
        }      

        return '';
    }

    public function siteCourseZoomDayStatus($course)
    {
        if (! $course){
          return [
            'status' => null,
            'course_name' => null,
            'course_date_at' => null,
          ];
        }


        if ( $this->courseZoomToday($course) ){
          // return '<br>'.'<i class="fas fa-wifi" style="color: gray;"></i>' . 'بث مباشر اليوم ' . ' <span class="zoom_day_course"> '. $course->name . '</span>';
          return [
            'status' => 'course_zoom_today',
            'course_name' => $course->name,
            'course_date_at' => $course->date_at,
          ];
        }

        if ( $this->courseZoomAfterToday($course) ){
            // return '<br>'.'<i class="fas fa-wifi" style="color: gray;"></i>' . ' تبث ' . ' <span class="zoom_day_course"> ' . $course->name . '</span><br>' . ' بتاريخ ' . ' <span class="zoom_day_date_at"> ' . $course->date_at . '</span>';
            return [
              'status' => 'course_zoom_after_today',
              'course_name' => $course->name,
              'course_date_at' => $course->date_at,
            ];
        }

        return '';
    }

}
