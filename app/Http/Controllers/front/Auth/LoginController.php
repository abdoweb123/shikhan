<?php

namespace App\Http\Controllers\front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App,Auth,Socialite,Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo;

    public function __construct()
    {
        $this->redirectTo = route('home');
        $this->middleware('guest:web')->except('logout');
    }

    public function redirectToProvider(Request $request)
    {
        $driver = \Route::input('driver');
        if (in_array($driver,['facebook','twitter','google'])) {
            return Socialite::driver($driver)->redirect();
        } else {
            abort(404);
        }
    }

    public function handleProviderCallback(Request $request)
    {

        $driver = \Route::input('driver');

        if($driver == 'twitter' ){
            $user = Socialite::driver($driver)->user();
        } else {
          $user = Socialite::driver($driver)->stateless()->user();
        }


        $user->driver = $driver; // to save to database (lgoin from driver)

        $email = $user->getEmail();
        if (! $email){ // my be user use mobile number to regster in face so no email here
          // return redirect()->route('register');
          $user->email = $user->getId() . '@baldatayiba.com';
        }

        $user = $this->handle_social_data($user);
        if ($user){
          Auth::guard()->login($user);
        } else {
          return redirect()->route('register');
        }
        return redirect()->route('diplomas.index');
        // return redirect( url(app()->getLocale()));

        // $token = $request->input('code');
        // $driver = \Route::input('driver');
        // if (in_array($driver,['facebook','twitter','google'])) {
        //     $user = Socialite::driver($driver)->scopes(['public_profile', 'email'])->userFromToken($token);
        //     $user = $this->handle_social_data($user,$driver);
        //     return $user;
        //     return $this->helperService->successResponse(['user' => $user]);
        // } else {
        //     throw_404();
        // }


        // $validator = Validator::make($request->all(), [
        //     'token' => 'required|string',
        //     'driver' => 'ends_with:facebook,twitter,google',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()->withInput()->withErrors($validator);
        // }

        // $token = $request->input('token');
        // $driver = \Route::input('driver');
        // if (in_array($driver,['facebook','twitter','google'])) {
        //     $user = Socialite::driver($driver)->scopes(['public_profile', 'email'])->userFromToken($token);
        //     $user = $this->handle_social_data($user,$driver);
        //     return $user;
        //     return $this->helperService->successResponse(['user' => $user]);
        // } else {
        //     throw_404();
        // }

    }

    public function defaultSiteSubscription()
    {
        return \App\site::select('id')->where('status',1)->whereNull('deleted_at')->first();
    }

    private function handle_social_data($data, $driver = null)
    {


        $email = $data->getEmail();
        if (!App\member::where('email', $email)->exists()) {
            $user = new App\member();
            $user->email = $data->email;
            $user->provider = $data->driver; // 'facebook'; // ['facebook' => 1,'twitter' => 2,'google' => 3][$driver];
            $user->password = Str::random(16);
            $user->created_by = 0;
            $user->status = 1;
            $user->gender = 0;
            $user->birthday = null;
            $user->country_id = null; // will set in model
            $user->name = $data->getName();
            $user->name_search = \App\helpers\UtilHelper::formatNormal($data->getName()) . '_9' . rand(1, 1000000);
            $user->avatar = $data->getAvatar();
            $user->save();
            // return false;
        } else {
            $user = App\member::where('email', $email)->first();
        }

        $user->sites()->syncWithoutDetaching( [$this->defaultSiteSubscription()->id] );

        // $token = $this->helperService->generateUserToken($user);
        // $user = fractal($user, new UserTransformer($this->helperService))->toArray();
        // $user['token'] = $token->accessToken;
        // $user['tokenExpireAt'] = strtotime($token->token->expires_at);
        return $user;
    }

    public function showLoginForm()
    {
        $data = [
            'page_name' => __('meta.title.login'),
            'page_key' => 'login',
            'body_id' => 'login',
        ];
        set_meta('login');

        // take prev_url then in authintact medillware
        session()->put('url_p', url()->previous());

        return view('front.content.auth.login',$data);
    }

    public function guard()
    {
        return Auth::guard('web');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect(route('login'));
    }







    public function login(Request $request)
  {
      $this->validateLogin($request);

      // If the class is using the ThrottlesLogins trait, we can automatically throttle
      // the login attempts for this application. We'll key this by the username and
      // the IP address of the client making these requests into this application.
      if (method_exists($this, 'hasTooManyLoginAttempts') &&
          $this->hasTooManyLoginAttempts($request)) {
          $this->fireLockoutEvent($request);

          return $this->sendLockoutResponse($request);
      }

      if ($this->attemptLogin($request)) {
          if ($request->hasSession()) {
              $request->session()->put('auth.password_confirmed_at', time());
          }

          return $this->sendLoginResponse($request);
      }

      // If the login attempt was unsuccessful we will increment the number of attempts
      // to login and redirect the user back to the login form. Of course, when this
      // user surpasses their maximum number of attempts they will get locked out.
      $this->incrementLoginAttempts($request);

      return $this->sendFailedLoginResponse($request);
  }

  protected function validateLogin(Request $request)
  {
      $request->validate([
          $this->username() => 'required|string',
          'password' => 'required|string',
      ]);
  }

  protected function attemptLogin(Request $request)
  {
        // return Auth::guard('admin')->attempt(
        //     $this->credentials($request), $request->boolean('remember')
        // );

       if ($this->guard()->attempt($this->credentials($request), $request->boolean('remember')) ){
         return true;
       }

      return false;

  }

  protected function credentials(Request $request)
  {
      $username = filter_var($request->mail_or_phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

      return [
        $username => $request->mail_or_phone,
        'password' => $request->password
      ];

      // return $request->only($this->username(), 'password');
  }

  protected function sendLoginResponse(Request $request)
  {
      $request->session()->regenerate();

      $this->clearLoginAttempts($request);

      if ($response = $this->authenticated($request, $this->guard()->user())) {
          return $response;
      }

      return $request->wantsJson()
                  ? new JsonResponse([], 204)
                  : redirect()->intended($this->redirectPath());
  }

  protected function authenticated(Request $request, $user)
  {
      //
  }

  protected function sendFailedLoginResponse(Request $request)
  {
      throw \Illuminate\Validation\ValidationException::withMessages([
          $this->username() => [trans('auth.failed')],
      ]);
  }

  public function username()
  {
      return 'mail_or_phone';
  }



  protected function loggedOut(Request $request)
  {
      //
  }

}
