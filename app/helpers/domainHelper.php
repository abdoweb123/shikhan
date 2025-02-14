<?php

namespace App\helpers;

class domainHelper
{

  public static function calculateTestRate($degree)
  {
      return $degree >= 90 && $degree <= 100 ? 5 : ($degree >= 80 && $degree < 90 ? 4 : ($degree >= 70 && $degree < 80 ? 3 : ($degree >= 60 && $degree < 70 ? 2 : ($degree >= 50 && $degree < 60 ? 1 : 0))));
  }

  public static function formatDegreeNumber($degree)
  {
      return number_format($degree, 2);
  }

  public static function getTestRatesTitles()
  {
      return [
        0 => __('domain_rates.fail'),
        1 => __('domain_rates.pass'),
        2 => __('domain_rates.good'),
        3 => __('domain_rates.above_average'),
        4 => __('domain_rates.very_good'),
        5 => __('domain_rates.excellent')
      ];

  }





}
