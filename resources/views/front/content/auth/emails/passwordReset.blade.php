@component('mail::message')
# Change password Request

Click on the button below to change password

@component('mail::button', ['url' => env('FRONT_END_URL'). '/response-password-reset?token='.$token])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
