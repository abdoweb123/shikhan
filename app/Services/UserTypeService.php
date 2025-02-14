<?php

namespace App\Services;
use App\Models\UserType;

class UserTypeService
{

  public function getAll()
  {
    return UserType::all();
  }

  public function getFrontUserTypes()
  {
    return UserType::wherein('alias',['teachers','student'])->get();
  }

  // public function checkInClientUserTypes($typeId)
  // {
  //     if (array_search( $typeId , ["1","3"]  ) === (bool) false) {
  //       return false;
  //     }
  //
  //     return true;
  // }



}
