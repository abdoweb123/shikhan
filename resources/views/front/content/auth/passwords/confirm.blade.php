@extends('front.layouts.new')
@section('head')
<style>
  @media only screen and (max-width: 767px){
    .hero-area{
      height: 150px !important;
    }
  }

    .courses-title {
            text-align: center;
            font-size: x-large;
            font-weight: 900;
        }
        .courses-details-desc .courses-accordion .accordion .accordion-item .accordion-content .courses-lessons .single-lessons .lessons-info .duration {
            text-align: center;
            margin-right: 0;
            direction: ltr;
            margin-left: 10px;
        }
        .courses-details-image img {
            height: 250px;

        }
        .swal2-popup .swal2-select {
            display: none;
          }
</style>

@endsection
@section('content')
<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 100px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12"style=" margin-top: 130px;">
                <!-- Hero Content -->
                <div class="hero-content text-center row">



                </div>
            </div>
        </div>
    </div>
</section>
    <div class="container row justify-content-center">
        <div class="col-md-8 "style="padding: 60px 0 !important;">
            <div class="card">
                <div class="card-header card-header-primary text-center">
                    <h4 class="card-title">{{ __('field.password_confirmation') }}</h4>
                </div>

                <div class="card-body">
                    @include('front.units.notify')
                    {{ __('Please confirm your password before continuing.') }}

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <span class="bmd-form-group">
                            <div class="input-group bmd-form-group">
                                <label class="bmd-label-static"> {{ __('field.password') }} </label>
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </span>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('field.password_confirmation') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
