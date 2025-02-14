<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Services\EmailService;

class UsersSubscribedButNotTestedEver extends Mailable
{
    use Queueable, SerializesModels;

    public $settings;

    public function __construct($settings = [])
    {
        $this->settings = (new EmailService())->prepareSettings($settings);
    }

    public function build()
    {
        return $this->from($this->settings['mail_from'], $this->settings['app_name'])
            ->subject($this->settings['subject'])
            ->view('emails.users_subscribed_but_not_tested_ever')
            // ->attach('https://www.baldatayiba.com/storage/courses/cwEluKxgobqhOlNdAKOqCrUMx8e0U5UXfVdPzc1F.jpeg')
            ;
    }
}
