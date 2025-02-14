{{--
  <div class="main-logo text-center">
  <a href="{{ route('front.home') }}">
    <img src="{{ asset('assets/front/images/logo.svg') }}" class="img-fluid mx-auto" alt="logo">
  </a>
</div>
--}}

<div class="kt-header-mobile__logo">
  <a href="{{ route('admin.home') }}">
    <img alt="Logo" src="{{ $settings['logo'] }}" style="width: 100%;"/>
  </a>
</div>
