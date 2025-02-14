@extends('front.layouts.the-index')

@section('head')

<style>
    select#country ,select#gender {
        display: block !important;
    }
    .nice-select.form-control {
      display: none !important;
      }
      input#code_country {
      direction: ltr;
      padding: 5px;
      }
      .register-now .register-contact-form .forms .form-control {
  color: #000000bf !important;
}
</style>
@endsection
@section('content')


 <!-- Start Page Title Area -->
        <!-- no class "page-title-area" because page-title-area will make banner hidden in mobile-->
        <div class="item-bg2 jarallax" data-jarallax='{"speed": 0.3}'
          style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );
          top: 197px;margin-bottom: 200px;">
            <div class="container" style="text-align: center;">
                <div class="" style="text-align: center;">

                  <div class="alert alert-success" role="alert" style="border-radius: 13px;box-shadow: 1px 10px 15px #0000004a">
                    <!-- <h4 class="alert-heading" style="font-size: 30px;font-weight: bold;">{!! $ended !!}</h4> -->

                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div></div>
                                <div class="alert alert-danger" role="alert">
                                    {{$error}}
                                </div>
                            @endforeach
                        @endif


                        @if($ended)
                          <span style="font-size: 20px;font-weight: bold;">{{ $ended }}</span><br>
                        @endif

                        @if( isset($registeredBefore))
                          @if(!$registeredBefore)
                          <form action="{{ route('front.prizes.subscribe_from_outside', ['course_id' => request('course_id'), 'outside' => request('outside') ]) }}" method="post">
                            @csrf
                            <!-- onChange="this.form.submit()" -->
                            <input type="checkbox" name="agree" value="1" style="width: 40px;height: 40px;margin-top: 15px;">
                            <span style="font-size: 31px;padding: 10px;color: black;">{{ $ended }}</span><br>
                            <button type="submit" class="btn btn-success" style="font-size: 15px;box-shadow: 1px 5px 7px #0000004f;border: 1px solid green;"><span style="font-size: 20px;font-weight: bold;">والله على ما أقول شهيد</span></button>
                          </form>
                          @endif


                          {{--
                          @if($registeredBefore)
                            <span style="font-size: 20px;font-weight: bold;">{{ $ended }}</span><br>
                          @endif
                          --}}
                          <a href="{{ $course_link ?? '' }}" class="alert-link" style="font-size: 25px;text-decoration: underline;">
                            {{ $course_title ?? '' }}
                          </a>
                        @endif


                    {{-- <hr><p class="mb-0"></p> --}}
                  </div>

                </div>

                <a href="{{ route('home') }}" style="padding: 8px 52px;font-size: 14px;border: 1px solid;border-radius: 9px;background-color: white;">الرئيسية</a>
            </div>
        </div>

@endsection
