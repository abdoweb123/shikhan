<?php

namespace App\Traits;
use Illuminate\Support\Facades\View;
use Auth;

trait GenderLocale
{

  public function _getGenderLocale()
  {
    $genderLocale = auth()->user()->gender;
    View::share('genderLocale', $userGender);
    return $genderLocale;
  }
}
