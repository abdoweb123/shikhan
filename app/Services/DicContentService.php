<?php

namespace App\Services;
// use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\DicContent;
use App\Models\DicContentInfo;
use App\Models\DicWord;
use App\Models\Letter;
use App\helpers\UtilHelper;
use App\Traits\Cachement;

use Auth;

class DicContentService
{
    use Cachement;

    public function getDicContentsTreeOf( $id , $exceptId, $language = null )
    {
        $data = DicContent::Details($language)->get();
        $temp = [];
        $data = UtilHelper::buildTreeRoot( $data, $exceptId, $temp , $id, 0 ) ;
        return $data;
    }

    // public function getWordsOfDicContent($id)
    // {
    //   return DicWord::where('dic_content_id',$id)->get();
    // }

    // in front
    public function getFreeDicContentsTreeOf( $id , $exceptId )
    {
        $data = DicContent::with(['activeTranslation'])->whereHas('activeTranslation')->Free()->get();
        $temp = [];
        $data = UtilHelper::buildTreeRoot( $data, $exceptId, $temp , $id, 0 ) ;
        return $data;
    }

    public function getDicContentWordsOfLetter( $dic_content_id , $letter_id ,$pages )
    {
        $data = DicWord::with(['activeTranslation'])->whereHas('activeTranslation');
        $data->when( $dic_content_id , function($q) use($dic_content_id) {
            return $q->where('dic_content_id',$dic_content_id);
        });
        $data->when( $letter_id , function($q) use($letter_id) {
            return $q->where('letter_id',$letter_id);
        });

        $data->orderBy('letter_id');

        if ($pages == 0) {
          return $data->get();
        } else {
          return $data->paginate($pages);
        }
    }

    // for language bar
    public function getDicContentTranslations( $dicContentId )
    {
      return DicContentInfo::where('dic_content_id',$dicContentId)->select('title','language','alias')->get();
    }



    public function setActive( $model , $status )
    {

        $model->update([ 'is_active' => $status ]);
        $childs = DicContent::where('parent_id', $model->id)->get();

        foreach ($childs as $child) {
          $this->setActive( $child , $status );
        }

        return true;
    }

    public function validateDoublicateTitle( $data , $language , $id = 0 )
    {
        $validate = DicContentInfo::where([ 'title' => $data , 'language' => $language ]);
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
        $validate = DicContentInfo::where([ 'alias' => $data , 'language' => $language ]);
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
        $validate = DicContentInfo::where([ 'dic_content_id' => $dataId , 'language' => $language ])->exists();

        if ( $validate ) {
          throw ValidationException::withMessages(['alias' => __('messages.already_exists' , [ 'var' => __('words.language') ] ) ]);
        }
    }




    public function destroyAll($id)
    {

        // $temp = [];
        // $categories = UtilHelper::buildTreeRoot( Category::all(), null, $temp, $id, 0 ) ;
        //
        // $ids = [$id] ;
        // foreach ($categories as $category) {
        //   $ids = array_merge( $ids , [$category['id']]);
        // }
        //
        //
        // DB::beginTransaction();
        // try {
        //     // subscription_packages  will ask
        //     DB::Table('item_category')->wherein('category_id' , $ids)->delete();
        //     DB::Table('user_category')->wherein('category_id' , $ids)->delete();
        //     DB::Table('delivery_charges')->wherein('category_id' , $ids)->delete();
        //     DB::Table('category_info')->wherein('category_id' , $ids)->delete();
        //     DB::Table('categories')->wherein('id', $ids )->delete();
        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     // return $e;
        //     return __('messages.deleted_faild');
        // }
        //
        // $this->cacheClearGroup('categories');
        // return true;

      }



}
