<?php

namespace App\Services;
use App\Models\ContactUs;

class ContactUsService
{

  public function getUnread()
  {
    return ContactUs::with(['type','user'])->Unread()->orderBy('id', 'desc')->take(10)->get();
  }





}
