<?php

namespace App\Services;
use Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthService
{

  public function storeUserIp()
  {
      if (Auth::guard('web')->user()) {
          $user = Auth::guard('web')->user();
          if (!$user->country_name){
              try {
                  $ip = request()->ip();
                  $country_name = Http::get('https://ipapi.co/'.$ip.'/country_name');
                  $user->ip = $ip;
                  $user->country_name_out = $country_name;
                  $user->save();
              } catch (\Exception $ex) {
                  Log::emergency($ex);
                  // dd($ex);
              }
          }
      }
  }




}
