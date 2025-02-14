<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>

  @include('front.layouts.new_design.head')
  @yield('head')

  <style>
    @media only screen and (max-width: 767px) {
      .page-title-area {
        height: 20px !important;
        visibility: collapse;
      }
    }
    .section-padding-100-0 {
      padding-top: 20px !important;
    }
  </style>

</head>
  <body>
    <div style="text-align: center;">
      <a href="{{ url(app()->getLocale()) }}">
          <img src="{{asset('assets/img/logo2.png')}}" style="max-width: 85px;" alt="logo">
      </a>
      <a href="{{ url(app()->getLocale()) }}">@lang('core.app_name')</a>
    </div>
    @yield('content')
  </body>
</html>
