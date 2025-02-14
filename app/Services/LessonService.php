<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use App\Lesson;
use App\Models\LessonInfo;

use App\helpers\UtilHelper;

class LessonService
{

  public function getFreeLessons($pages=0)
  {
    $data = Lesson::with(['activeTranslation'])->whereHas('activeTranslation')->Free();
    if ($pages){
      return $data->paginate($pages);
    } else {
      return $data->get();
    }

  }

  // get the whale tree lessons of content
  public function getLessonsTreeOfContent($id)
  {
    $data = Lesson::with(['activeTranslation'])->whereHas('activeTranslation')->where('content_id',$id)->active()->get();
    $temp = [];
    $data = buildTreeRoot( $data, 0, $temp , 0, 0 ) ;
    return $data;
  }

  // get the whale tree lessons of lesson
  public function getLessonsTreeOfLesson($id)
  {
    $data = Lesson::where('id',$id)->get();
    $temp = [];
    $data = buildTreeRoot( $data, 0, $temp , 0, 0 ) ;
    return $data;
  }

  public function getLessonOfAlias($alias)
  {
    return Lesson::with(['activeTranslation'])->whereHas('activeTranslation', function($q) use($alias) {
           return $q->where('alias',$alias);
        })->firstorfail();
  }

  public function getLessonOfId($id)
  {
    return Lesson::with(['activeTranslation'])->whereHas('activeTranslation')->where('id',$id)->firstorfail();
  }

  public function getNextLesson( $content_id , $sort )
  {
    return Lesson::with(['activeTranslation'])->whereHas('activeTranslation')->where('content_id',$content_id)->where('sort',$sort+1)->first();
  }

  public function getPrevLesson( $content_id , $sort )
  {
    return Lesson::with(['activeTranslation'])->whereHas('activeTranslation')->where('content_id',$content_id)->where('sort',$sort-1)->first();
  }

  // for language bar
  public function getLessonTranslations($id)
  {
    return LessonInfo::where('lesson_id',$id)->select('title','language','alias')->get();
  }



  public function setActive( $model , $status )
  {

      $model->update([ 'is_active' => $status ]);


      return true;
  }

  public function validateDoublicateTitle( $data , $language , $id = 0 )
  {
      $validate = LessonInfo::where([ 'title' => $data , 'language' => $language ]);
      $validate->when( $id , function($q) use($id) {
          return $q->where('id', '!=', $id);
      });

      $validate = $validate->exists();
      if ( $validate ) {
        throw ValidationException::withMessages(['title' => __('messages.already_exists' , [ 'var' => __('words.title') ] ) ]);
      }
  }

  public function validateDoublicateAlias($data ,$language ,$id = 0)
  {
      $validate = Lesson::whereTranslation('alias', $data)->whereTranslation('locale', $language);
      return $validate->when( $id , function($q) use($id) {
          return $q->where('id', '!=', $id);
      })->exists();
  }

  public function validateDoublicateLanguage( $dataId , $language )
  {
      $validate = LessonInfo::where([ 'lesson_id' => $dataId , 'language' => $language ])->exists();

      if ( $validate ) {
        throw ValidationException::withMessages(['lesson' => __('messages.already_exists' , [ 'var' => __('words.language') ] ) ]);
      }
  }


  public function getLessonsTreeOfContentApi($id)
  {
    $data = Lesson::with(['activeTranslation','translation'])->whereHas('activeTranslation')->where('content_id',$id)->active()->get();
    $temp = [];
    $data = buildTreeRoot( $data, 0, $temp , 0, 0 ) ;
    return $data;
  }

}
