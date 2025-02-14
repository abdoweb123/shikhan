@extends('front.layouts.the-index')
@section('head')
    <!-- Styles -->
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
    <link rel="stylesheet" href="{{ asset('assets/front/style_rtl.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('assets/front/style.css') }}">
    @endif

     <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
     {{--
       <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
     --}}
 <style>
    .header-area {
        position: absolute !important;
    }
    .section-padding-100-0 {
        padding-top: 145px;
        padding-bottom: 0;
    }
    .text-danger a {
        color: #dc3545!important;
    }
    .ul-taps{
      width: 100%;
      display: inline-flex;
    }
    .ul-taps li{

      text-align: center;
      width: 50%;
    }
    .ul-taps li h4{
      margin-bottom: 15px !important;
        margin-top: -18px !important;
        border: 2px solid #e8bb8f;
        box-shadow: 1px 2px 12px 0px #eabe92;
        padding: 10px;
        border-radius: 5px;
        color: #00c1e8;
    }
    h4.unactive {
        background-color: #d89a5f;
        color: #fff !important;
    }
    h4.unactive:hover {
        background-color: #fff;
        color: #d89a5f !important;
    }
    button.btn.clever-btn.w-100 {
      color: #fff;
      background: #2266ae;
    }
    ul.ul-taps {
      margin-bottom: 25px;
    }
    @media only screen and (max-width: 767px){
      .register-now .register-now-countdown {
          display: none !important;
      }
      .register-now .register-contact-form {
          margin-top: 45px !important;
          padding: 6px !important;
      }
      .ul-taps li h4 {
          font-size: 18px !important;
          padding: 4px;
          margin: 7px 2px !important;
          font-weight: 900;
      }
    }
 </style>
@endsection
@section('content')



<!-- ##### Register Now Start ##### -->
<section class="register-now d-flex justify-content-between align-items-center" style="background-image: url(img/core-img/texture.png);padding-top: 10px;">
    <!-- Register Contact Form -->


    <div class="col-lg-6 col-12" style="text-align: center;">
        <h3>{{ __('core.header_login') }}</h3>

        <div  style="text-align: center; padding: 15px 0px;background-color: #f8e8e9;">
            {{-- @include('front.include.support_videos.how_to_study', ['direct' => true]) --}}
            @include('front.include.support_videos.login', ['direct' => true])
              {{--
              <iframe style="height: 320px;width: 480px auto;" class="embed-responsive-item" src="https://www.youtube.com/embed/NbNufM7pWGY" title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              --}}
        </div>

        <!-- <p>{{-- __('core.header_subscribe_title') --}}</p> -->
        <!-- Register Countdown -->
        <div class="register-countdown">
            <div class="events-cd d-flex flex-wrap" data-countdown="2019/03/01"></div>
        </div>
    </div>

    <div class="register-contact-form mb-50 col-6" style="flex: 0 0 40%;">
        <div class="container-fluid">
            @include('front.include.page_alert')
            <div class="row">
                <div class="col-12" style="text-align: center;">

                  @if( request()->session()->has('come_from_outside') )
                  <div style="padding: 0px 0px 40px 0px;color: #21ac6b;font-size: 20px;font-weight: bold;">
                      {{ __('core.login_for_prize_outside') }}
                  </div>
                  @endif

                    <div class="forms">
                      {{--
                      <ul class="ul-taps">
                        <li>
                          <a href="{{ route('register') }}">
                            <h4 class="unactive">{{ __('core.register') }}</h4>
                          </a>
                        </li>
                        <li>
                            <h4 >{{ __('core.login') }}</h4>
                        </li>
                      </ul>
                      --}}
                      <h4 >{{ __('words.login') }}</h4>

                        <form method="POST" class="row justify-content-center" action="{{ route('login') }}">
                            @csrf

                            <!-- if user clcik ( subscripe diploma from outside) get the diploma id from session and put
                            it in the form to subscripe after login -->
                            @if ( Session::has('siteIdTosubscripe'))
                              <input type="hidden" name="diplome_ids[]" value="{{ Session::get('siteIdTosubscripe') }}">
                              سيتم اكتمال اشتراكك فى
                              {{ optional(\App\site::where('id',Session::get('siteIdTosubscripe'))->select('title')->first())->title }}
                              بعد تسجل الدخول
                              <br><br>
                            @endif


                            <input type="hidden" name="url_p" value="{{session()->get('url_p')}}">

                            <div class="row">
                                <div class="col-12 col-lg-12">
                                    <div class="form-group">

                                        <input id="email" type="text" class="form-control @error('mail_or_phone') is-invalid @enderror" name="mail_or_phone" value="{{ old('mail_or_mobile') }}" required autocomplete="email"  style="color: black" autofocus placeholder="{{ __('trans.email') }} {{ __('field.mobile') }}">
                                        @error('mail_or_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-12 col-lg-12">
                                    <div class="form-group">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="color: black" placeholder="{{ __('trans.password') }}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>








                                <div class="col-12" style="text-align: center;">
                                    <button type="submit" class="default-btn but-login" style="padding: 8px 15px 8px 13px !important">
                                        {{ __('words.login') }}
{{--                                        <span style="font-size: 17px;"></span>--}}
                                    </button>
                                    <br>
                                </div>



                            <div class="col-12" style="text-align: center;">
                                <div class="col-12" style="text-align: center;padding: 10px 80px;">
                                  <!-- <span>خيارات تسجيل أخرى</span> -->
                                </div>

                                {{--
                                <div class="col-12" style="text-align: center;">
                                    <a  href="{{ route('socialite.index','facebook') }}" class="btn btn-social btn-facebook"
                                    style="color: #fff;background-color: #3b5998;border-radius: 10px;padding: 10px 30px;" rel="nofollow">
                                      <i class="fa fa-facebook fa-fw"></i>
                                    </a>
                                </div>
                                --}}


                                <div class="col-12" style="text-align: center;padding: 10px 0px;">
                                    <a  href="{{ route('socialite.index','google') }}" class="btn btn-social btn-google"
                                    style="color: #fff;background-color: #ea4234;border-radius: 10px;padding: 10px 30px;" rel="nofollow">
                                      <i class="fa fa-google fa-fw"></i>
                                    </a>
                                </div>
                            </div>




                                {{--
                                <div class="col-12" style="text-align: center;">

                                    <div class="col-12" style="text-align: center;padding: 10px 80px;">
                                      <span>خيارات تسجيل أخرى</span>
                                    </div>

                                    <div class="col-12" style="text-align: center;">
                                        <a  href="{{ route('socialite.index','facebook') }}" class="btn btn-social btn-facebook"
                                        style="color: #fff;background-color: #3b5998;border-radius: 10px;padding: 13px 7px;width: 207px;" rel="nofollow">
                                          <i class="fa fa-facebook fa-fw"></i>الدخول بحساب الفيسبوك
                                        </a>
                                    </div>

                                    <div class="col-12" style="text-align: center;padding: 10px 0px;">
                                        <a  href="{{ route('socialite.index','google') }}" class="btn btn-social btn-google"
                                        style="color: #fff;background-color: #ea4234;border-radius: 10px;padding: 13px 7px;width: 207px;" rel="nofollow">
                                          <i class="fa fa-google fa-fw"></i>الدخول بحساب جوجل
                                        </a>
                                    </div>

                                    <div class="col-12" style="text-align: center;padding: 10px 0px;">
                                        <a  href="{{ route('socialite.index','twitter') }}" class="btn btn-social btn-google"
                                        style="color: #fff;background-color: #55acee;border-radius: 10px;padding: 13px 7px;width: 207px;" rel="nofollow">
                                          <i class="fa fa-twitter fa-fw"></i>الدخول بحساب تويتر
                                        </a>
                                    </div>

                                </div>
                                --}}


                                <div class="col-12" style="visibility: hidden;">
                                    <p class="font-small grey-text d-flex" style="margin-right: 20px;">
                                    <input class="form-check-input" type="checkbox" checked style="margin: 7px -20px 0px -20px;" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    {{ __('core.remember_me') }}
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                    </p>
                                </div>


                                <div class="col-12" style="text-align: right;padding-bottom: 15px;padding-top: 45px;">
                                    <span class="col-2" style="">{{ __('trans.not_registerd') }}</span>
                                    <a class="col-5" style="color: #2266ae;padding: 10px;font-size: 23px;" href="{{ route('register') }}">
                                       {{ __('core.register') }}
                                    </a>
                                </div>






                                @if (Route::has('password.request'))
                                    <p class="font-small grey-text d-flex justify-content-end @error('password') text-danger @enderror"  style="margin-right: 20px;" >
                                      <a href="{{ route('password.request') }}" class="dark-grey-text ml-1 font-weight-bold ">{{ __('core.forgot_password') }}</a>
                                    </p>
                                @endif




                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- test facebook -->
        <!-- <div class="col-12" style="text-align: center;">
            <a  href="{{ route('socialite.index','facebook') }}"
            style="" rel="nofollow">.
            </a>
        </div> -->

    </div>





</section>
<!-- ##### Register Now End ##### -->


@endsection
