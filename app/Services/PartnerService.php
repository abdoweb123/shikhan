<?php

namespace App\Services;
use App\Translations\PartnerTranslation;
use App\helpers\UtilHelper;

class PartnerService
{

  public function getAll()
  {
      return \App\Partner::get()->sortBy('sort');
  }

  public function aliasAndLanguageExists($alias, $language, $currentId = null)
  {
      return PartnerTranslation::where('alias', $alias)->where('locale', $language)->where('partner_id', '!=', $currentId)->exists();
  }

  public function getExactsortAndShuffle()
  {
      $partners = \App\Partner::whereTranslation('locale', app()->getLocale())->get()->sortBy('sort');

      $exactSort = $partners->where('exact_sort', 1);
      $shuffle = $partners->where('exact_sort', 0)->shuffle();

      return $exactSort->merge($shuffle);
  }

  public function updateActiveStatus( $record , $status )
  {
      // because force update
      $record->is_active = $status;
      $record->save();

      return true;
  }


}
