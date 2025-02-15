<?php

namespace App\Http\Controllers\front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = route('home');
        $this->middleware('auth:web');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
    * Show the email verification notice.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function show(Request $request)
    {
        $data =
        [
            'page_name' => __('meta.title.verification'),
            'page_key' => 'verification',
            'body_id' => 'verification',
        ];
        set_meta('verification');

        return $request->user()->hasVerifiedEmail()
        ? redirect($this->redirectPath())
        : view('front.content.auth.verify',$data);
    }
}
