@extends('front.layouts.the-index')

@section('head')
<!-- Styles -->
@if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
<link rel="stylesheet" href="{{ asset('assets/front/style_rtl.css') }}">
@else
<link rel="stylesheet" href="{{ asset('assets/front/style.css') }}">
@endif

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<style>
   .header-area {
      position: absolute !important;
   }
   .section-padding-100-0 {
      padding-top: 145px;
      padding-bottom: 0;
    }
    body {

      background-color: #fff !important;
    }
    .ul-taps h4 {
      font-size: large;
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

<div id="up"></div>

 <!-- Start Page Title Area -->
        <div class=" item-bg2 jarallax inner_banner" data-jarallax='{"speed": 0.3}' style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
            <div class="container">
                <div class="page-title-content" style="text-align: center;">
                    <ul>
                        {{--<li><h1>{{ __('core.prizes') }}</h1></li>--}}
                        <li>
                          {{--<h3>⭐️مسابقة مهرجان الجوائز النقدية الكبرى⭐️</h3>--}}
                          <!-- <h3 class="sec-color">💵  المسابقة الخامسة من أكاديمية البلدة الطيبة   💵</h3>
                          <h3 class="sec-color">💵 أكثرمن مليوني وثمانمائة الف ريال يمني 💵</h3> -->
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Page Title Area -->

        @include('front.content.auth.register_every_page')

        <!-- Start Courses Area -->
        <section class="courses-area ptb-300">
            <div class="container">
                <div class="courses-topbar">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-8">
                            <div class="topbar-ordering-and-search">
                                <div class="row align-items-center">
                                    <div class="col-lg-5 col-md-6 col-sm-6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="text-align: center;">
                  <div class="col-lg-12">
                        {!! $html !!}

                    <br><br><br>
                  </div>
                </div>
        </section>








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
<script>
$(document).ready(function(){
    //  $('html,body').animate({
    //     scrollTop: $("#div_words").offset().top
    // }, 'slow');

    $('html, body').animate({
        scrollTop: $('#up').offset().top
    }, 'slow');

  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#ItemsDiv .ItemDiv").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

@endsection
