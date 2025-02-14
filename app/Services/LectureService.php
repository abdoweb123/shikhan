<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use App\Models\Lecture;
use App\Models\LectureInfo;

use App\helpers\UtilHelper;
use App\helpers\DateHelper;

class LectureService
{

  public function getFreeLectures($pages=0)
  {
    $data = Lecture::with(['activeTranslation'])->whereHas('activeTranslation')->Free();
    if ($pages){
      return $data->paginate($pages);
    } else {
      return $data->get();
    }

  }


  public function getLectureOfAlias($alias)
  {
    return Lecture::with(['activeTranslation'])->whereHas('activeTranslation', function($q) use($alias) {
           return $q->where('alias',$alias);
        })->firstorfail();
  }

  public function getLectureOfId($id)
  {
    return Lecture::with(['activeTranslation'])->whereHas('activeTranslation')->where('id',$id)->firstorfail();
  }

  // for language bar
  public function getLectureTranslations($id)
  {
    return LectureInfo::where('lecture_id',$id)->select('title','language','alias')->get();
  }

  public function setActive( $model , $status )
  {

      $model->update([ 'is_active' => $status ]);
      // $childs = LessonOld::where('parent_id', $model->id)->get();

      // foreach ($childs as $child) {
      //   $this->setActive( $child , $status );
      // }

      return true;
  }

  public function validateDoublicateTitle( $data , $language , $id = 0 )
  {
      $validate = LectureInfo::where([ 'title' => $data , 'language' => $language ]);
      $validate->when( $id , function($q) use($id) {
          return $q->where('id', '!=', $id);
      });

      $validate = $validate->exists();
      if ( $validate ) {
        throw ValidationException::withMessages(['title' => __('messages.already_exists' , [ 'var' => __('words.title') ] ) ]);
      }
  }

  public function validateDoublicateAlias( $data , $language , $id = 0 )
  {
      $validate = LectureInfo::where([ 'alias' => $data , 'language' => $language ]);
      $validate->when( $id , function($q) use($id) {
          return $q->where('id', '!=', $id);
      });

      $validate = $validate->exists();
      if ( $validate ) {
        throw ValidationException::withMessages(['alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] ) ]);
      }
  }

  public function validateDoublicateLanguage( $dataId , $language )
  {
      $validate = LectureInfo::where([ 'lecture_id' => $dataId , 'language' => $language ])->exists();

      if ( $validate ) {
        throw ValidationException::withMessages(['lecture' => __('messages.already_exists' , [ 'var' => __('words.language') ] ) ]);
      }
  }


  public function validateDateTimeAfterNow( $dataTime )
  {
      if ( $dataTime <= DateHelper::currentDateTime()){
        throw ValidationException::withMessages(['date' => __('messages.error_date_before_today') ]);
      }
  }


}
