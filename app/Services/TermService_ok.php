<?php

namespace App\Services;
use App\Term;
use App\member;
use DB;

class TermService
{

    public function getAll()
    {
      return Term::get()->sortBy('sort');
    }

    public function getTermIdBySiteByCourse($site_id,$course_id)
    {
        return DB::table('course_site')->where('site_id',$site_id)->where('course_id',$course_id)->value('term_id');
    }

    public function getTermQuestionsAnswersByLanguage(Term $term, $language = null)
    {
        $language = $language ?? getDefaultLanguage()->alies;
        return $term->questions()->with(['translation','answers.translation'])->orderBy('sequence', 'ASC')->get();
    }

    public function userFinishedAllTermCourses(member $user, Term $term)
    {
        return $term->courses()->whereDoesntHave('final_results', function($q) use($user){
          $q->where('user_id', $user->id);
        })->exists() ? false : true;
    }

    public function userSuccessedInAllTermCourses(member $user, Term $term)
    {
        return $term->courses()->whereHas('final_results', function($q) use($user){
            return $q->where('user_id', $user->id)->notSuccessed();
        })->exists() ? false : true;
    }


    public function showTermTestToUser(Term $term, member $user = null, $userFinishedAllTermCourses = null, $userSuccessedInAllTermCourses = null)
    {

      $userFinishedAllTermCourses = isset($userFinishedAllTermCourses) ? $userFinishedAllTermCourses : $this->userFinishedAllTermCourses($user, $term);
      if (! $userFinishedAllTermCourses){
          return false;
      }

      $userSuccessedInAllTermCourses = isset($userSuccessedInAllTermCourses) ? $userSuccessedInAllTermCourses : $this->userSuccessedInAllTermCourses($user, $term);
      if (! $userSuccessedInAllTermCourses){
        return false;
      }

      if (! $term->examApproved()){
        return false;
      }

      return true;

    }




    public function openTermTestToUser(Term $term, $extraTrays, $userTestsCountOfTerm, member $user = null, $userFinishedAllTermCourses = null, $userSuccessedInAllTermCourses = null)
    {

      $userFinishedAllTermCourses = isset($userFinishedAllTermCourses) ? $userFinishedAllTermCourses : $this->userFinishedAllTermCourses($user, $term);
      if (! $userFinishedAllTermCourses){
          return false;
      }

      $userSuccessedInAllTermCourses = isset($userSuccessedInAllTermCourses) ? $userSuccessedInAllTermCourses : $this->userSuccessedInAllTermCourses($user, $term);
      if (! $userSuccessedInAllTermCourses){
        return false;
      }

      if (! $term->examApproved()){
        return false;
      }

      if (! $this->userHasTrays($extraTrays, $userTestsCountOfTerm)){
        return false;
      }

      return true;

    }

    public function userHasTrays($extraTrays, $userTestsCountOfTerm)
    {
        return ($extraTrays <= $userTestsCountOfTerm) ? false : true;
    }

}
