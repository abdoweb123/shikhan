<?php

namespace App\Services;
use App\course;
use App\Translations\CourseTranslation;

class CourseService
{

  public function getById($id)
  {
    return course::find($id);
  }

  public function getSummary($locale = null)
  {
    return course::select('id','title')->get();
  }

  public function getTests(course $course, $paginate = null, $locale = null)
  {
    return $course->tests()->get();
  }

  public function getLessons(course $course, $paginate = null, $locale = null)
  {
    return $course->lessons()->get();
  }

  // الطالب شاهد كل الدروس و الاختبارات
  // public function isStudentSeeAllCourseLessons($student, $course)
  // {
  //     // lessons: lessons or tests
  //     return $course->course_track()->whereHas('courseable', function($q) use($student){
  //       $q->whereDoesnthave('seen', function($q) use($student){
  //           $q->where('student_id', $student->id);
  //       });
  //     })->exists() ? false : true;
  // }

  public function paginateTestsWithDetails($course, $status = null, $paginate = null)
  {
      return $course->tests()->with('lessons:id', 'teacher:id', 'testable:id')->paginate($paginate ?? config('domain.paginate'));
  }

  public function getTotalCoursesFee($courses)
  {
      $total = 0;

      // courses already loaded
      if ($courses instanceof \Illuminate\Database\Eloquent\Collection){
          foreach ($courses as $course) {
            $total = $total + ($course->study_hours * $course->study_hour_fee);
          }
      }

      // array means courses ids to get courses from db and calculate total
      if (is_array($courses) && ! empty($courses)){
          $courses = course::whereIn('id', $courses)->select('study_hours','study_hour_fee')->get();
          foreach ($courses as $course) {
            $total = $total + ($course->study_hours * $course->study_hour_fee);
          }
      }

      return $total;

  }

  public function getCourseInstance(): course
  {
    return course::query()->getModel();
  }

  public function getCoursesList()
  {
    return course::select('id','name')->get();
  }


    public function getCourseQuestionsAnswersByLanguage($course, $language = null)
    {

        $language = $language ?? getDefaultLanguage()->alies;

        // return $course->questions_old()->translatedIn($language)->with(['answers' => function($q) use($language) {
        //     $q->translatedIn($language);
        // }])->orderBy('sequence', 'ASC')->get();

        return $course->questions()->with(['translation','answers.translation'])->orderBy('sequence', 'ASC')->get();

    }

    public function getTopUsersOfCourse($course_id, $params=[])
    {
        // الطلاب الاعلى درجة فى الدورة
        $limit = isset($params['limit']) ? $params['limit'] : 5;

        $resault = "Select user_id, course_id, MAX(degree) as max_degree, members.name
        FROM `course_tests_results`
        join members on members.id = course_tests_results.user_id
        WHERE course_id = $course_id group by user_id ORDER by max_degree desc limit 5";

        return DB::select( $resault );
    }

    public function getCourseTestResultsMoreThan($course_id, $params=[])
    {
        // النتائج الاكبر من درجة معينة فى كورس معين
        $moreThan = isset($params['more_than']) ? $params['more_than'] : 100;

        $resault = "Select user_id, course_id, MAX(degree) as max_degree, members.name
        FROM `course_tests_results`
        join members on members.id = course_tests_results.user_id
        WHERE course_id = $course_id and degree >= $moreThan group by user_id ORDER by max_degree desc";

        return DB::select( $resault );
    }

    public function aliasAndLanguageExists($alias, $language, $currentId = null)
    {
        return CourseTranslation::where('alias', $alias)->where('locale', $language)->where('course_id', '!=', $currentId)->exists();
    }

}
