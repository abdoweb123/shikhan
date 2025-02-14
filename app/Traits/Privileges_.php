<?php

namespace App\Traits;
use Request;
use Illuminate\Support\Facades\View;
use App\Traits\Roles;

trait Privileges_
{
  use Roles;

  // public function _checkPrivilege($currentPrivilege)
  // {
  //
  //       // $userPrivileges = optional(Request::User()->roles()->first())->privileges;
  //       // Request::route()->getName()
  //       if (array_search( $currentPrivilege , $this->_getUserPrivileges() ) === (bool) false) {
  //         return false;
  //       }
  //
  //       return true;
  //
  //   }
  //
  //   public function _getUserPrivileges()
  //   {
  //     $privileges = optional(\App\Models\Role::where('id', $this->_getCurrentRole()->id)->first())->privileges;
  //     $privileges = explode(',', $privileges);
  //     View::share('privileges', $privileges);
  //     return $privileges;
  //   }
  //
  //   public function _getUserPrivilegesIds()
  //   {
  //     $privileges = optional(\App\Models\Role::where('id', $this->_getCurrentRole()->id)->first())->privileges_ids;
  //     $privileges = explode(',', $privileges);
  //     View::share('privilegesIds', $privileges);
  //     return $privileges;
  //   }

}
