<?php

namespace App\Traits;
use Illuminate\Support\Facades\View;
use Auth;

trait MenusAdmin
{

  public function _getMenu($menu)
  {


    $genderLocale = ''; //Auth::user()->gender_id == 2 ? 'female' : '' ;
    View::share( 'genderLocale', $genderLocale );
    return $genderLocale;

  }
}
