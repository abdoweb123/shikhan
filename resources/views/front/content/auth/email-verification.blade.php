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
</style>

@endsection
@section('content')


<section class="bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
  <div class="container h-100">
    <div class="row h-100 align-items-center">
      <div class="col-12" style="padding: 20px;text-align: center;">
          <!-- <h2>أهلا بك فى شاشة تفعيل البريد الإكترونى</h2> -->
      </div>
    </div>
  </div>
</section>


<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="form-gradient pt-5  mt-100 mb-100" style="padding-bottom: 35px;">
        <div class="" style="text-align: center;">


          <!-- general error -->
          @if(session()->has('error'))
              <div class="alert alert-danger">
                  {{ session()->get('error') }}
              </div>
          @endif

          <!-- email sent success -->
          @if(session()->has('verify_sent_message'))
              <div class="alert alert-success">
                  {!! session()->get('verify_sent_message') !!}
              </div>

              <div>

                {{--
                <form class="d-inline" method="POST" action="{{ route('show_verification_email') }}">
                  @csrf
                  اذا لم تصلك رسالة على البريد اضغط على الزر التالى
                  <button type="submit" class="btn btn-primary">إرسال</button>
                </form>
                --}}

                إعادة إرسال رابط التفعيل
                <a href="{{ route('show_verification_email') }}" class="btn btn-primary">إعادة إرسال</a>

              </div>
          @endif

          <!-- email not found -->
          @if(session()->has('verify_error_message'))
              <div class="alert alert-danger">
                  {!! session()->get('verify_error_message') !!}
              </div>

              <form method="POST" action="{{ route('correct_email') }}">
                @csrf
                <div class="row justify-content-center">
                  <div class="col-lg-3" style="padding: 10px 0px;">
                    <label for="correct_email" class="visually-hidden">ادخل البريد الإلكترونى الصحيح</label>
                  </div>
                  <div class="col-lg-4">
                    <input type="text" class="form-control input-default" name="correct_email" id="correct_email" required placeholder="">
                  </div>
                  <div class="col-lg-2">
                    <button type="submit" class="btn btn-primary mb-3">تصحيح البريد</button>
                  </div>
                </div>
              </form>
          @endif

          <!-- google form link -->
          @if(session()->has('google_form_message'))
            <br>
            {!! session()->get('google_form_message') !!}
          @endif






          {{--
            @if(! session()->has('verify_sent_message'))
              <div class="alert alert-secondary" role="alert">
                  اضغط زرار ارسال لارسال رسالة تفعيل على بريدكم الشخصى المسجل لدينا

                  <br>
                  <span style="color: #27865b;">{{ auth()->user()->email }}</span>

                  <br>
                  <div>
                    <form class="d-inline" method="POST" action="{{ route('send_verification_email') }}">
                      @csrf
                      <button type="submit" class="btn btn-primary">إرسال</button>
                    </form>
                  </div>

                  <br>
                  اذا كان البريد الإكترونى غير صحيح وتود تغيره اضغط الرابط التالى لتغيير البريد الإكترونى
                  <a style="color: #0f5aaa;font-size: 18px;" href="{{ route('profile') }}" target="_blank">تغير البريد الإلكترونى</a>
                  <br><br>
              </div>
            @endif
          --}}



        </div>
    </div>
  </div>
</div>


@endsection
