<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Mail\RemainingCourses;
use Mail;

class SendEmails_Not_Used implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $settings;

    public function __construct($settings = [])
    {
       $this->settings = (new \App\Services\EmailService())->prepareSettings($settings);
    }

    public function handle()
    {
      $email = new RemainingCourses($this->settings);
      Mail::to($this->settings['email_to'])->send($email);
    }
}
