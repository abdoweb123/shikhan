<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification
{
    public static $toMailCallback;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }
        return (new MailMessage)
        ->greeting(__('core.greeting',['name' => $notifiable->name]))
        ->subject(__('core.verification_email'))
        ->line(__('core.account_verification',['app_name' => __('core.app_name')]))
        ->action(__('core.verify_it'), $verificationUrl)
        ->line(__('message.thank_you_joining',['app_name' => __('core.app_name')]))
        ->line(__('message.enjoy_your_time'))
        ->line(__('core.app_name').'®');
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now('africa/cairo')->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
