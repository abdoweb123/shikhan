<?php

namespace App\Services;
use App\Currency;

class CurrencyService
{

  public function getAll()
  {
      return Currency::active()->orderBy('sort')->get();
  }

}
