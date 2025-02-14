@extends('front.layouts.the-index')
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
          span.input-group-text {
    /* height: 42px; */
    background-color: #dd4b39;
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
        <div class="col-md-8"style="padding: 60px 0 !important;">
            <div class="card">
                <div class="card-header card-header-primary text-center">
                    <h4 class="card-title">{{ __('core.reset_password') }}</h4>
                </div>
                <div class="card-body">
                    @include('front.units.notify')
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <span class="bmd-form-group">
                            <div class="input-group bmd-form-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('field.email') }}">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </span>
                        <div class="form-group row mb-0 p-5 pt-3  text-center">
                            <div class="col-md-6 offset-md-4 text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('core.send_password_reset_link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
