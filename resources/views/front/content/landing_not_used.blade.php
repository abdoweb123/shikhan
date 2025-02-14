@extends('front.layouts.new')

@section('head')

<style>
    .single-courses-item .courses-image {
        height: 230px;
        overflow: hidden;
    }
    .course-author span.btn.btn-success a {
        color: white;
    }
    .course-author span.btn.btn-success:hover a {
        color: #218838;
    }
    .course-author span.btn.btn-success:hover  {
        background-color: #ffffff;
            box-shadow: 1px 2px 9px 1px #1e7e34;
        border-color: #1e7e34;
    }
    .search_text{
        height: 50px;
        color: #252525;
        background-color: #eef5f9;
        display: block;
        width: 100%;
        border-radius: 30px;
        padding: 3px 15px 0 48px;
        border: none;
        -webkit-transition: .5s;
        transition: .5s;
        font-size: 14px;
        font-weight: 400;
        padding: 0px 20px;
    }
    .new_sites_subs_alert{ color: red;}
    .new_sites_subs_alert_main{ color: red; text-align: center;}
    .show_div{ display: block; float: left;}
    .hide_div{ display: none}
    .active_but{padding: 5px 26px; background-color: #b57f4b; color: white;}
    .normal_but{padding: 5px 26px; border: 1px solid #b5b2b2;}
    .section_title{ background-color: #3d7b3d; font-size: 18px; font-weight: bold; color: white;}
</style>

@endsection
@section('content')


        <!-- d-none d-md-block   hide on mobile -->
        <div class="page-title-area item-bg2 jarallax" data-jarallax='{"speed": 0.3}' style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
            <div class="container">
                <div class="page-title-content">
                    <ul>
                        <li><a>{{ __('core.header_title') }}</a></li>
                    </ul>
                  <h5 style="color: white;">{{ __('core.header_content') }}</h5>
                     <br>
                     @if (! Auth::guard('web')->user())
                        @include('front.units.steps')
                    @endif
                </div>
            </div>
        </div>


        @if ( Session::has('global_message'))
        <div class="alert alert-success" style="text-align: center;" role="alert">
          {!! Session::get('global_message') !!}
        </div>
        @endif


        <!-- Start Courses Area -->

        <section class="courses-area ptb-100" style="padding-top: 50px;">
            <div class="container">


           <!-- <div class="courses-topbar d-none d-md-block"> this still dosnt see in mobile -->
                <div style="padding-bottom: 20px;">
                    <div class="row align-items-center">

                        <!-- subscribe in all sites (test) -->
                        @if (Auth::id() == 5651)
                        <div class="col-lg-12" style="text-align: center;padding-bottom: 25px;">
                            <span class="btn btn-success sub-dip"><a href="{{ route('diplomas.subscribers', 'all') }}">الإشتراك فى كل الدبلومات</a></span>
                        </div>
                        @endif

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="topbar-result-count" style="padding-bottom: 25px;">
                                <h3>{{ __('core.please_select_course') }}</h3>
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-6 col-sm-6" id="filter_btns" style="text-align: center;padding-bottom: 25px;">
                            <div style="font-size: 23px;">جميع الدبلومات</div>
                        </div>

                        <div class="col-lg-12" id="filter_btns" style="text-align: center;padding-bottom: 25px;">
                            <button class="btn section_title">مرحلة أولى</button>
                        </div>

                    </div>
                </div>





                <div class="row" id="ItemsDiv">
                    @foreach ($result as $item)
                      @if ( $item->created_at < '2022-02-06')   <!-- old sites -->
                        <div class="ItemDiv col-lg-4 col-md-6">
                            <div class="single-courses-item mb-30">
                                <div class="courses-image">
                                    <a href="{{ route('courses.index',$item->alias) }}" class="d-block"><img src="{{ url($item->logo_path) }}" alt="image"></a>
                                </div>
                                <div class="courses-content">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="course-author d-flex align-items-center">
                                            <img class="shadow img-owl"  src="{{ asset('assets/img/logo2.png') }}" alt="{{ $item->name }}" >
                                            <span>@lang('core.app_name')</span>
                                        </div>
                                    </div>
                                    <h3><a href="{{ route('courses.index',$item->alias) }}" class="d-inline-block">{{ $item->name }}</a></h3>

                                    @if(Auth::guard('web')->user())
                                        @include('front.include.subscribe_In_site', [ 'siteToSubscribe' => $item ] )
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-success" style="background-color: #b57f4b;border: none;">اشترك فى الدبلوم</a>
                                    @endif

                                </div>
                            </div>
                        </div>
                      @endif
                  @endforeach
                </div>

                <br><br>
                <div class="col-lg-12" id="filter_btns" style="text-align: center;padding-bottom: 25px;">
                    <button class="btn section_title">مرحلة ثانية</button>
                </div>

                <div class="col-lg-12 new_sites_subs_alert_main hide_div">التسجيل فى الدورات القادمة فقط لمن أنهى دبلوم واحد على الأقل من الدبلومات الجاهزة</div>

                <div class="row" id="ItemsDiv">
                    @foreach ($result as $item)

                        @if ($item->created_at >= '2022-02-06')  <!-- new sites -->
                          <div class="ItemDiv col-lg-4 col-md-6">
                              <div class="single-courses-item mb-30">
                                  <div class="courses-image">
                                      <a href="{{ route('courses.index',$item->alias) }}" class="d-block"><img src="{{ url($item->logo_path) }}" alt="image"></a>
                                  </div>
                                  <div class="courses-content">
                                      <div class="d-flex justify-content-between align-items-center">
                                          <div class="course-author d-flex align-items-center">
                                              <img class="shadow img-owl"  src="{{ asset('assets/img/logo2.png') }}" alt="{{ $item->name }}" >
                                              <span>@lang('core.app_name')</span>
                                          </div>
                                      </div>
                                      <h3><a href="{{ route('courses.index',$item->alias) }}" class="d-inline-block">{{ $item->name }}</a></h3>


                                      @if(Auth::guard('web')->user())
                                          @include('front.include.subscribe_In_site', [ 'siteToSubscribe' => $item ] )
                                      @else
                                          <a href="{{ route('login') }}" class="btn btn-success" style="background-color: #b57f4b;border: none;">اشترك فى الدبلوم</a>
                                      @endif

                                  </div>
                              </div>
                          </div>
                        @endif

                    @endforeach
                </div>

            </div>
        </section>
        <!-- End Courses Area -->







@endsection
@section('script')
<script>

// --------------------------------------------------- filter divs
filterSelection("finished")
function filterSelection(c) {
  var x, i;
  x = document.getElementsByClassName("filterDiv");
    if (c == "all") c = "";

    for (i = 0; i < x.length; i++) {
      x[i].classList.remove("hide_div")
      x[i].classList.add("show_div")

      if (x[i].className.indexOf(c) == -1) {
        x[i].classList.remove("show_div")
        x[i].classList.add("hide_div")
      };
    }

    subs_alert = document.getElementsByClassName("new_sites_subs_alert_main")
    if (c == "new"){
      subs_alert[0].classList.remove("hide_div")
      subs_alert[0].classList.add("show_div")
    } else {
      subs_alert[0].classList.remove("show_div")
      subs_alert[0].classList.add("hide_div")
    }
}


var filter_btns = document.getElementById("filter_btns");
var btns = filter_btns.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active_but");
    current[0].className = current[0].className.replace(" active_but", " normal_but");
    this.className += " active_but";
  });
}
// --------------------------------------------------- filter divs


$(document).ready(function(){
    //  $('html,body').animate({
    //     scrollTop: $("#div_words").offset().top
    // }, 'slow');

  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#ItemsDiv .ItemDiv").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

<x-subscripe-in-site/>

@endsection
