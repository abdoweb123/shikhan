<?php

namespace App\Services;
use App\language;
use App\Traits\CacheTrait;

class LanguageService
{
  use CacheTrait;

  public function getAll()
  {
    return $this->cacheForever('languages', language::all());
  }

  public function getActiveLanguages()
  {
    return $this->cacheForever('active_languages', language::active()->get());
  }

  public function languageStatus($field, $value)
  {
    return language::where($field, $value)->active()->exists();
  }

  public function getDefaultLanguage()
  {
    return $this->cacheForever('default_language', language::default()->first());
  }

}
