<?php

namespace App\Services;
// use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Content;
use App\Models\ContentInfo;
use App\Models\ContentType;
use App\Models\Lesson;
use App\helpers\UtilHelper;
use App\Traits\Cachement;

use Auth;

class ContentService
{
    use Cachement;

    public function getContentsTreeOfCourse( $id , $exceptId, $language = 'ar' )
    {
        $data = Content::Details($language)->ofCourse($id)->orderBy('sort')->get();
        $temp = [];
        $data = UtilHelper::buildTreeRoot( $data, $exceptId, $temp , 0, 0 ) ;
        return $data;
    }

    public function getContentOfAlias( $alias )
    {
       $data = Content::with(['activeTranslation'])->whereHas('activeTranslation', function($q) use($alias) {
          return $q->where('alias',$alias);
       })->firstorfail();

       return $data;
    }

    // get only one level after current content .if we pass 0 get root
    public function getContentChilds( $id )
    {
      return Content::with(['activeTranslation'])->whereHas('activeTranslation')->where('parent_id',$id)->orderBy('sort')->get();
    }

    // for language bar
    public function getContentTranslations($id)
    {
      return ContentInfo::where('content_id',$id)->select('title','language','alias')->get();
    }

    public function getContentTypes()
    {
        return ContentType::all();
    }

    // get first level lessons of content
    public function getLessonsOfContent($id)
    {
      return Lesson::where('content_id',$id)->get();
    }



    public function setActive( $model , $status )
    {

        $model->update([ 'is_active' => $status ]);
        $childs = Content::where('parent_id', $model->id)->get();

        foreach ($childs as $child) {
          $this->setActive( $child , $status );
        }

        return true;
    }

    public function validateDoublicateTitle( $data , $language , $id = 0 )
    {
        $validate = ContentInfo::where([ 'title' => $data , 'language' => $language ]);
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
        $validate = ContentInfo::where([ 'alias' => $data , 'language' => $language ]);
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
        $validate = ContentInfo::where([ 'content_id' => $dataId , 'language' => $language ])->exists();

        if ( $validate ) {
          throw ValidationException::withMessages(['alias' => __('messages.already_exists' , [ 'var' => __('words.language') ] ) ]);
        }
    }








        // $data = $this->getAll($language)->filter(function ($category) {
        //     return $category->type == 'service' ;
        // });






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

       public function getContentOfAliasApi( $alias )
    {
       $data = Content::with(['activeTranslation','translation'])->whereHas('activeTranslation', function($q) use($alias) {
          return $q->where('alias',$alias);
       })->firstorfail();

       return $data;
    }

     public function getContentChildsApi( $id )
    {
      return Content::with(['activeTranslation','translation'])->whereHas('translation')->where('parent_id',$id)->orderBy('sort')->get();
    }



}
