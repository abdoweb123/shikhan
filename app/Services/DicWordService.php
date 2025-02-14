<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use App\Models\DicWord;
use App\Models\DicWordInfo;

use App\helpers\UtilHelper;

class DicWordService
{


  public function setActive( $model , $status )
  {
      $model->update([ 'is_active' => $status ]);
      return true;
  }

  public function validateDoublicateTitle( $data , $language , $id = 0 )
  {
      $validate = DicWordInfo::where([ 'title' => $data , 'language' => $language ]);
      $validate->when( $id , function($q) use($id) {
          return $q->where('id', '!=', $id);
      });

      $validate = $validate->exists();
      if ( $validate ) {
        throw ValidationException::withMessages(['title' => __('messages.already_exists' , [ 'var' => __('words.title') ] ) ]);
      }
  }

  public function validateDoublicateCharacter( $data , $id = 0 )
  {
      $validate = DicWord::where([ 'dic_character' => $data ]);
      $validate->when( $id , function($q) use($id) {
          return $q->where('id', '!=', $id);
      });

      $validate = $validate->exists();
      if ( $validate ) {
        throw ValidationException::withMessages(['dic_character' => __('messages.already_exists' , [ 'var' => __('project.character') ] ) ]);
      }
  }

  public function validateDoublicateLanguage( $dataId , $language )
  {
      $validate = DicWordInfo::where([ 'dic_word_id' => $dataId , 'language' => $language ])->exists();

      if ( $validate ) {
        throw ValidationException::withMessages(['lesson' => __('messages.already_exists' , [ 'var' => __('words.language') ] ) ]);
      }
  }

}
