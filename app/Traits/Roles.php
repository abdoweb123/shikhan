<?php

namespace App\Traits;
// use Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\View;
use Auth;

trait Roles
{
  public function _getRoleById($roleId)
  {
      $currentRole = Auth::user()->roles()->find($roleId);
      if (!$currentRole) {
        throw ValidationException::withMessages(['general' => __( 'messages.error_belongs_to_user'.Auth::user()->GenderAlias , [ 'var' => __('role.title') ] ) ]);
      }

      return $currentRole;
  }

  public function _setCurrentRole($roleId)
  {
      $currentRole = $this->_getRoleById($roleId);
      $this->_shareRole($currentRole);
  }

  public function _getCurrentRole()
  {

      if (session()->has('currentRole') ) {
          $sessionRole = session('currentRole');
          $currentRole = $this->_getRoleById($sessionRole->id);
          $this->_shareRole($currentRole);
          return;
      }

      $defaultRole = Auth::user()->primaryRole()->first();
      if (! $defaultRole) {
        Auth::logout();
        throw new ModelNotFoundException(__('role.not_found') );
      }

      $this->_shareRole($defaultRole);

  }

  public function _shareRole($role)
  {
      View::share('currentRole', $role);
      Auth::user()->currentRole = $role;
      session()->put(['currentRole' => $role]);
  }

}
