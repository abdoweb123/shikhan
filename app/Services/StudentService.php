<?php

namespace App\Services;

use App\helpers\UtilHelper;

class StudentService
{


  public function updateActiveStatus( $record , $status )
  {
      // because force update
      $record->is_active = $status;
      $record->save();

      return true;
  }


}
