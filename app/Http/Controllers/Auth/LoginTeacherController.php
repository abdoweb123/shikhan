<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginTeacherController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /*** Where to redirect users after login.*/
    protected $redirectTo = RouteServiceProvider::HOME;

    /*** Create a new controller instance. */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /** show Login Form **/
    public function showLoginForm()
    {
        return view('auth.teachers.login');
    }


    public function loginForm($type){

        return view('auth.login',compact('type'));
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('teacher')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/teachers/dashboard');
        }
        else{
            return redirect()->back()->withInput(['email'])->with('fail', 'Error in email or password');
        }

    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login.teacher');
    }


}
