@extends('front.layouts.landing')
@section('head')
    <!-- Styles -->
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
    <link rel="stylesheet" href="{{ asset('assets/front/style_rtl.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('assets/front/style.css') }}">
    @endif

     <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
 <style>
         .header-area {
            position: absolute !important;
         }
        .section-padding-100-0 {
            padding-top: 145px;
            padding-bottom: 0;
        }
        .register-now .register-contact-form .forms .form-control {
             color: rgb(0 0 0) !important;
        }
        select#country {
            display: block !important;
        }
        .nice-select.form-control {
    display: none !important;
}
input#code_country {
    direction: ltr;
    padding: 5px;
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
    /* .register-now .register-now-countdown {
        display: none !important;
      } */
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
  .section-padding-100-0 {
  padding-top: 105px;
  }
  body {
  background-color: #d0dafb;
}
.color-title {
    color: rgb(107 75 41);
  }
  .color-content {
    color: #d89a5f;
}
 </style>
@endsection
@section('content')

<!-- ##### Hero Area Start ##### -->
{{--
<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <!-- Hero Content -->
                <div class="hero-content text-center">
                    <h1 style="color: white;">{{ __('core.landing1_header_title') }}</h1>
                    <h4 style="color: white;">{{ __('core.landing1_header_content1') }}</h5>
                      <h1 style="color: white;">{{ __('core.landing1_header_title1') }}</h1>
                    <h5 style="color: white;">{{ __('core.landing1_header_content2') }}</h5>
                    <br>
                    <a href="{{ route('register') }}" class="btn clever-btn">{{ __('trans.start_now') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
--}}
<!-- ##### Hero Area End ##### -->

<!-- ##### Register Now Start ##### -->
<section class="register-now section-padding-100-0 d-flex justify-content-between align-items-center" style="background-image: url(img/core-img/texture.png);">
    <!-- Register Contact Form -->

    <div class="register-now-countdown mb-100" style="text-align: center;">
      <h2 class="color-title" >{{ __('core.landing1_header_title') }}</h2>
      <h6 class="color-content">{{ __('core.landing1_header_content1') }}</h6>
        <h1 class="color-title" >{{ __('core.landing1_header_title1') }}</h1>
      <h6 class="color-content">{{ __('core.landing1_header_content2') }}</h6>
        <!-- Register Countdown -->
        <div class="register-countdown">
            <div class="events-cd d-flex flex-wrap" data-countdown="2019/03/01"></div>
        </div>
    </div>

    <div class="register-contact-form mb-50">
        <div class="container-fluid">
            @include('front.units.notify')
            <div class="row">
                <div class="col-12" style="text-align: center;">
                    <div class="forms">
                      <ul class="ul-taps">
                        <li>
                          <h4>{{ __('core.register') }}</h4>
                        </li>
                        <li>
                          <a href="{{ route('login') }}">
                            <h4 class="unactive">{{ __('core.login') }}</h4>
                          </a>
                        </li>
                      </ul>

                        <form method="POST" class="row justify-content-center" action="{{ route('register') }}">
                            @csrf

                            <input type="hidden" class="form-control" name="join_in" id="join_in"  value="Landing" ="name" >

                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"  value="{{ old('name') }}" required autocomplete="name" style="color: black" autofocus placeholder="{{ __('field.name') }}">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" style="color: black" autofocus placeholder="{{ __('field.email') }}">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                               <div class="col-12 col-lg-5">
                                    <div class="form-group">
                                      @isset($countries)
                                        <select  class="form-control" name="country_id" id="country">
                                            <option value="" {{old('country_id') == null? 'selected':''}}>{{ __('field.country') }}</option>
                                            @foreach($countries as $country)
                                                <option value="{{$country->id }}"  attr-code="{{$country->phonecode}}"{{old('country_id') == $country->id ? 'selected':''}}>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('country_id')

                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                      @endisset
                                    </div>
                                </div>
                                <div class="col-12 col-lg-7 row ">
                                    <div class="form-group col-9 " style="    padding-left: 0 !important;">
                                      <input id="phone" class="form-control @error('number') is-invalid @enderror" type="number" name="phone"  value="{{old('phone')}}" placeholder="{{ __('field.phone') }}">

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-3 p-0">
                                      <input readonly id="code_country" class="form-control @error('code_country') is-invalid @enderror" type="text" name="code_country" value="{{old('code_country')}}" placeholder="{{ __('field.code_country') }}">

                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="color: black" placeholder="{{ __('field.password') }}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" style="color: black" required autocomplete="current-password" placeholder="{{ __('field.password_confirmation') }}">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                            @foreach(['0' => __('core.select') ,'1' =>  __('core.male')  ,'2' =>  __('core.female')  ] as $id => $title)
                                                <option {{ old('gender') == $id ? 'selected' : '' }} value="{{ $id }}"> {{ $title }} </option>
                                            @endforeach
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>



                                <div class="col-12" style="text-align: center;">
                                    <button type="submit" class="btn clever-btn w-100">
                                        {{ __('core.register') }}
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Now Countdown -->
</section>
<!-- ##### Register Now End ##### -->
@endsection
@section('script')

<script>
$( "#country" )
  .change(function() {
    var str = "";
    $( "#country option:selected" ).each(function() {
      str = '+' + $( this ).attr('attr-code') ;
    });
    if(str !='+undefined'){

      $('input[name=code_country]#code_country').attr('value',str);

    }


  })
  .trigger( "change" );
</script>

@endsection
