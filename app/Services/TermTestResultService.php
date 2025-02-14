<?php

namespace App\Services;
use App\Term;
use App\member;
use App\TermTestResult;
// use DB;

class TermTestResultService
{

    public function isUserTestedTerm(Term $term, member $user, $locale = null)
    {
        // هل الطالب اختبر هذا التيرم بهذه اللغة
        // return $term->term_results()->where('user_id', $user->id)
        return $user->whereRelation('term_results','user_id', $user->id)
          ->when($locale, function($q) use($locale){
              return $q->where('locale', $locale);
          })->exists();
    }

    public function getUserTestsCountOfTerm(Term $term, member $user, $locale = null)
    {
        // عدد اختبارات الطالب فى هذا التيرم بهذه اللغة
        return $term->term_results()->where('user_id', $user->id)
            ->when($locale, function($q) use($locale){
              return $q->where('locale', $locale);
            })->count();
    }

    public function isUserSuccessedInTerm(Term $term, member $user, $locale = null)
    {
        //  هل الطالب نجح فى التيرم بهذه اللغة
        return $term->term_results()->where('user_id', $user->id)
          ->where('degree', '>=' , pointOfSuccess())
          ->when($locale, function($q) use($locale){
              return $q->where('locale', $locale);
          })->exists();
    }

    public function getUserResultsOfTerm(Term $term, member $user, $locale = null)
    {
      // نتائج الطالب فى هذا التيرم بهذه اللغة
      return $term->term_results()->where('user_id', $user->id)
        ->when($locale, function($q) use($locale){
          return $q->where('locale', $locale);
        })
        ->select('id','term_id','degree','created_at')
        ->with('term:id','term.site')
        ->get();
    }

    public function getUserLargestDegreeOfTerm(Term $term, member $user, $locale = null)
    {
      // النتيجة الاكبر للطالب فى هذا التيرم
      return $term->term_results()->where('user_id', $user->id)
        ->when($locale, function($q) use($locale){
          return $q->where('locale', $locale);
        })
        ->orderBy('degree','DESC')
        ->orderBy('created_at','DESC')
        ->first();
    }

    public function getUserFinalTestOfTerm(Term $term, member $user, $locale = null)
    {
      // النتيجة النهائية للطالب فى هذا التيرم
      return $term->final_results()->where('user_id', $user->id)
        ->when($locale, function($q) use($locale){
          return $q->where('test_locale', $locale);
        })
        ->first();
    }






}
