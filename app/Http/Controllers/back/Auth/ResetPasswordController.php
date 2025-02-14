<?php

namespace App\Http\Controllers\back\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request as Request;
use Auth;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
    * Where to redirect users after resetting their password.
    *
    * @var string
    */
    protected $redirectTo = '/';

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->redirectTo = route('dashboard.index');
        $this->middleware('guest:admin');
    }

    /**
    * Display the password reset view for the given token.
    *
    * If no token is present, display the link request form.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  string|null  $token
    * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    */
    public function showResetForm(Request $request,$local = 'en',$token = null)
    {
        return view('back.content.auth.passwords.reset')->with(
        ['token' => $token, 'email' => $request->email]
        );
    }

    /**
    * Get the broker to be used during password reset.
    *
    * @return \Illuminate\Contracts\Auth\PasswordBroker
    */
    public function broker()
    {
        return Password::broker('admins');
    }

    /**
    * Get the guard to be used during password reset.
    *
    * @return \Illuminate\Contracts\Auth\StatefulGuard
    */
    protected function guard()
    {
        return Auth::guard('admin');
    }

}
