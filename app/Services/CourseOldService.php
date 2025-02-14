<?php

namespace App\Services;
use App\Translations\CourseTranslation;
use DB;

class CourseOldService
{

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
