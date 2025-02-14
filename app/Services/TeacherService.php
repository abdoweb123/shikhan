<?php

namespace App\Services;
use App\Teacher;
use App\Translations\TeacherTranslation;
use App\helpers\UtilHelper;

class TeacherService
{

  public function aliasAndLanguageExists($alias, $language, $currentId = null)
  {
      return TeacherTranslation::where('alias', $alias)->where('locale', $language)->where('teacher_id', '!=', $currentId)->exists();
  }

  public function updateActiveStatus( $record , $status )
  {
      // because force update
      $record->is_active = $status;
      $record->save();

      return true;
  }


    public function get($locale = null)
    {
        return Teacher::get();
    }

    public function getSummary($locale = null)
    {
        return Teacher::select('id','name')->get();
    }

    public function getTeacherInstance(): Teacher
    {
        return Teacher::query()->getModel();
    }


}
