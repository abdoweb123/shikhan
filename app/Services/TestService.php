<?php

namespace App\Services;
use App\Models\Test;
use App\course;

class TestService
{

  public function getById($id)
  {
      return Test::find($id);
  }

  public function paginate($params = [], $status = null, $paginate = null)
  {
      return Test::when(@$params['title'], function($q) use($params) {
          return $q->whereTranslationLike('title', '%'.$params['title'].'%');
      })->paginate($paginate ?? config('domain.paginate'));
  }

  public function paginateWithDetails($params = [], $status = null, $paginate = null)
  {
      return Test::withDetails()->when(@$params['title'], function($q) use($params) {
          return $q->whereTranslationLike('title', '%'.$params['title'].'%');
      })->paginate($paginate ?? config('domain.paginate'));
  }

  public function loadTestQuestionsAnswersOfLanguage($test, $language = null)
  {
      $language = $language ?? getDefaultLanguage()->alies;
      return $test->questions()->with(['translation','answers.translation'])->orderBy('sequence', 'ASC')->get();
  }

  public function aliasAndLanguageExistsOfMorph($model, $alias, $locale, $currentId = null)
  {
    return $model->tests()->whereTranslation('alias', $alias)->whereTranslation('locale', $locale)->where('id', '!=', $currentId)->exists();
  }



}
