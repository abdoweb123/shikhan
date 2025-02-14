<?php

namespace App\Services;
use Illuminate\Validation\ValidationException;
use App\Models\Role;
use App\helpers\UtilHelper;
use Auth;

class RoleService
{

  // public function getUserRoleById($roleId)
  // {
  //     $currentRole = Auth::user()->roles()->find($roleId);
  //     if (!$currentRole) {
  //       throw ValidationException::withMessages(['general' => __( 'messages.error_belongs_to_user'.Auth::user()->GenderAlias , [ 'var' => __('role.title') ] ) ]);
  //     }
  //
  //     return $currentRole;
  // }
  //
  // public function setCurrentRole($roleId)
  // {
  //   $currentRole = $this->getUserRoleById($roleId);
  //   session(['currentRole' => $currentRole]);
  //   $this->shareUserRole($currentRole);
  // }
  //
  // public function getCurrentRole()
  // {
  //
  //   if (session()->has('currentRole') ) {
  //       $sessionRole = session('currentRole');
  //       $currentRole = $this->getUserRoleById($sessionRole->id);
  //       $this->shareUserRole($currentRole);
  //   }
  //
  //   $defaultRole = Auth::user()->primaryRole()->first();
  //   if (! $defaultRole) {
  //     throw new ModelNotFoundException(__('role.not_found') );
  //   }
  //
  //   session(['currentRole' => $defaultRole]);
  //   $this->shareUserRole($defaultRole);
  //
  // }
  //
  // public function shareUserRole($role)
  // {
  //   View::share( 'currentRole', $role );
  //   Auth::user()->currentRole = $role;
  // }

  public function getPrivilegsTree( $privilegs ,$privileg_id = 0 , $dont = null)
  {
    $temp = [];
    return UtilHelper::buildTreeRoot( $privilegs, $dont, $temp, $privileg_id, 0 );
  }

  public function getClientRoles()
  {
    return Role::where('role_for',1)->get();
  }

}
