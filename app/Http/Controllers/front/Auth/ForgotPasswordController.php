<?php

namespace App\Http\Controllers\front\Auth;



// use App\Http\Controllers\back_controller as Controller;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web');
    }

    public function showLinkRequestForm()
    {
        $data =
        [
            'page_name' => __('meta.title.forgot_password'),
            'page_key' => 'forgot_password',
            'body_id' => 'forgot_password',
        ];
        set_meta('forgot_password');
        return view('front.content.auth.passwords.email',$data);
    }

    public function broker()
    {
        return Password::broker('members');
    }
}
