<?php

namespace App\Services;
use App\Models\Option;

class OptionService
{

  public function get()
  {
      return Option::get();
  }





}
