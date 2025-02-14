<?php

namespace App\Services;

class EmailService
{
  public function prepareSettings( $settings = [] )
  {
      $settings['mail_from'] = isset($settings['mail_from']) ? $settings['mail_from'] : 'info@baldatayiba.com'; // get email from .env
      $settings['app_name'] = isset($settings['app_name']) ? $settings['app_name'] : config('app.name'); // get app name from .env
      $settings['title'] = isset($settings['title']) ? $settings['title'] : config('app.name'); // get app name from .env
      $settings['subject'] = isset($settings['subject']) ? $settings['subject'] : __('core.app_name');
      $settings['locale'] = isset($settings['locale']) ? $settings['locale'] : 'ar';

      return $settings;
  }

  public function ourAccounts()
  {
      return ['5651','5972','2','5668','5672','5671'];  // '5663','5842' dr abdullah
  }

  public function ourAccountsEmails()
  {
      return \App\member::wherein('id',$this->ourAccounts())->select('email')->get();
  }

}
