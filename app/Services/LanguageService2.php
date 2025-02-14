<?php

namespace App\Services;
use App\Models\Language;
use App\Traits\CacheTrait;

class LanguageService2
{
  use CacheTrait;

  public function getAll()
  {
    return $this->cacheForever('languages', Language::all());
  }

  public function getActiveLanguages()
  {
    return $this->cacheForever('active_languages', Language::active()->get());
  }

  public function languageStatus($field, $value)
  {
    $languageExists = Language::where($field, $value)->active()->exists();
    return $languageExists ?? false;
  }

  public function getDefaultLanguage()
  {
    return $this->cacheForever('default_language', Language::default()->first());
  }

}
