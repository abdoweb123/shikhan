<?php

namespace App\Services;
// use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Page;
use App\Models\PageInfo;
use App\helpers\UtilHelper;
use Illuminate\Support\Facades\Cache;

class PageService
{

    public function getHomePage( $language = null )
    {
      return Page::where('is_home',1)->firstorfail()->activeTranslation->first();
    }

    public function getPageOfAlias( $pageAlias )
    {
        $alias = $pageAlias;
        return Page::with(['activeTranslation'])->whereHas('activeTranslation' , function($q) use($alias) {
            return $q->where('alias',$alias);
        })->firstorfail();
    }

    public function titleAndLanguageExists($title, $language, $currentId = null)
    {
        return PageInfo::where('title', $title)->where('language', $language)->where('page_id', '!=', $currentId)->exists();
    }

    public function aliasAndLanguageExists($alias, $language, $currentId = null)
    {
        return PageInfo::where('alias', $alias)->where('language', $language)->where('page_id', '!=', $currentId)->exists();
    }


    public function languageExists( $id, $language )
    {
        return PageInfo::where([ 'page_id' => $id , 'language' => $language ])->exists();
    }

    public function clearCacheLanguages()
    {
        $languages = \App\language::get();

        foreach ($languages as $language) {
          Cache::forget('menu_header_'.$language->alies);
        }
    }


}
