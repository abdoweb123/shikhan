<?php

namespace App\Services;

use App\Models\Gender;
use App\Models\Country;
use App\Models\NativeLanguage;
use App\Models\StudyArabicAim;
use App\Models\LessonType;
use App\Models\TrainingType;
use App\Models\LectureType;
use App\Models\GrammerContent;
use App\Models\Teacher;
use App\Models\LectureWoner;

use App\helpers\UtilHelper;

class DataService
{

  public function getActiveLessonTypes()
  {
    return LessonType::Active()->get();
  }

  public function getActiveLectureTypes()
  {
    return LectureType::Active()->get();
  }

  public function getActiveTrainingTypes()
  {
    return TrainingType::Active()->get();
  }

  public function getActiveGrammerContentTreeRoot( $parent_id , $exceptId  = null)
  {
    $data = GrammerContent::active()->get();
    $temp = [];
    $data = UtilHelper::buildTreeRoot( $data, $exceptId, $temp, $parent_id, 0 ) ;
    return $data;
  }


  public function getGenders()
  {
    return Gender::all();
  }

  public function getActiveNativeLanguages()
  {
    return NativeLanguage::Active()->get();
  }

  public function getActiveStudyArabicAims()
  {
    return StudyArabicAim::Active()->get();
  }

  public function getActiveCountriesTreeRootOf( $parent_id , $exceptId  = null)
  {
    $data = Country::active()->get();
    $temp = [];
    $data = UtilHelper::buildTreeRoot( $data, $exceptId, $temp, $parent_id, 0 ) ;
    return $data;
  }

  public function getYesNos()
  {
    return [
      [ 'id' => 0 , 'title' => __('project.no') ] ,
      [ 'id' => 1 , 'title' => __('project.yes') ]
    ];
  }

  public function getActiveTeachers()
  {
    return Teacher::Active()->get();
  }

  public function getActiveLectureWoners()
  {
    return LectureWoner::Active()->get();
  }


}
