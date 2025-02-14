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
    <div class="row justify-content-center container">
        <div class="col-md-8">
            <div class="form-gradient pt-5  mt-100 mb-100">
                <div class="card card-login">
                    <div class="card-header card-header-primary text-center">
                        <h4 class="card-title">{{ __('core.Verify_email') }}</h4>
                    </div>
                    <div class="card-body mx-2 pb-4 text-center">
                        @include('front.units.notify')
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('core.fresh_verification') }}
                            </div>
                        @endif
                        <p>
                            {{ __('core.Before_proceeding') }}
                        </p>
                        <div>
                            {{ __('core.not_receive_email') }},
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('core.request_another') }}</button>.
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
