@extends('front.layouts.the-index')

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
    .show_div{ display: block; text-align: center;} /*float: left;*/
    .hide_div{ display: none}
    .active_but{padding: 5px 26px; color: white;}
    .normal_but{padding: 5px 26px; border: 1px solid #b5b2b2;}

    .zoom_day_course{ font-weight: bold; color: red;}
    .zoom_day_date_at{ font-weight: bold;}
</style>

@endsection
@section('content')


    <!-- d-none d-md-block   hide on mobile -->
    <div class=" item-bg2 jarallax" data-jarallax='{"speed": 0.3}' style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
        <div class="container">
            {{--
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
            --}}
        </div>
    </div>


    {{--
    @if ( Session::has('global_message'))
    <div class="alert alert-success" style="text-align: center;" role="alert">
      {!! Session::get('global_message') !!}
    </div>
    @endif
    --}}








    @include('front.content.auth.register_every_page')

    @include('front.include.global_alert')


    <div class="col-12" style="text-align: center; padding: 15px 0px;">
      @include('front.include.support_videos.how_to_study')
    </div>


    <!-- Start Courses Area -->


    <section class="courses-area ptb-100" style="padding-top: 18px;">
      <div class="container">

        <div class="page-title-content bread-crumb">
            <ul>
                <li><a style="color: gray" href="{{ route('home') }}">الرئيسية</a></li> /
                <li style="color: gray">الدبلومات</li>
                <!-- <li></li> -->
            </ul>
        </div>

        <!-- <div class="courses-topbar d-none d-md-block"> this still dosnt see in mobile -->
        <div style="padding-bottom: 20px;">
            <div class="row justify-content-center">

                <!-- subscribe in all sites (test) -->
                @if (! Auth::guard('web')->user())
                  @if (Auth::id() == 5651)
                  <div class="col-lg-12" style="text-align: center;padding-bottom: 25px;">
                      <span class="btn btn-success sub-dip"><a href="{{ route('diplomas.subscribers', 'all') }}">الإشتراك فى كل الدبلومات</a></span>
                  </div>
                  @endif
                @endif

                {{--
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="topbar-result-count" style="padding-bottom: 25px;">
                        <h3>{{ __('core.please_select_course') }}</h3>
                    </div>
                </div>
                --}}

                <div class="col-lg-5 col-md-6 col-sm-6" id="filter_btns" style="text-align: center;padding-bottom: 25px;">
                    <button class="btn active_but sec-back-color-dark" onclick="filterSelection('all')">جميع الدبلومات</button>
                    <button class="btn normal_but" onclick="filterSelection('finished')">مرحلة اولى</button>
                    <button class="btn normal_but" onclick="filterSelection('new')">مرحلة ثانية</button>
                </div>

                {{--
                <div class="col-lg-4 col-md-8">
                    <div class="topbar-ordering-and-search">
                        <div class="row align-items-center">

                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <div class="topbar-search" style="text-align: center;padding-bottom: 25px;">
                                   <form>
                                        <!-- <label><i class="bx bx-search"></i></label> -->
                                        <input id='myInput' type="text" class="search_text" placeholder="بحث..." >
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                --}}

                <!-- <div class="col-lg-12 new_sites_subs_alert_main hide_div">التسجيل فى الدورات القادمة فقط لمن أنهى دبلوم واحد على الأقل من الدبلومات الجاهزة</div> -->

            </div>
        </div>

        <div id="stage_01"></div>
        <div class="row" id="ItemsDiv">
            @foreach ($result as $item)
              @if ( $item->created_at < '2022-02-06')   <!-- old sites -->
                <div class="ItemDiv col-lg-4 col-md-6">
                    <div class="single-courses-item mb-30 owl-item filterDiv {{ $item->created_at < '2022-02-06' ? 'finished' : 'new' }} show_div">
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
                            <!-- <span>دبلومات المرحلة الأولى</span> -->
                            <div class="d-flex">
                              <span style="margin: 7px 4px;display: block;font-size: 15px;font-weight: bold;color: #aaa38c;">{{ $item->courses_count}} دورة</span>
                              <span style="margin: 7px 4px;display: block;font-size: 15px;font-weight: bold;" class="prim-color"><i class="far fa-clock" style="padding: 0px 5px;"></i>{{ $item->getVideosDuration()  }} ساعة</span>
                              <span style="margin: 7px 4px;display: block;font-size: 15px;font-weight: bold;" class="prim-color"><i class="bx bx-user" style="padding: 0px 5px;"></i>{{ $item->site_subscription_count  }} مشترك</span>

                              @if($item->siteNotCompleted == 1)
                                <span class="but-special" style="padding-top: 14px; margin-bottom: 10px;border-radius: 14px;" >جاهز</span>
                              @endif

                            </div>


                            {{-- <p>{!! $item->description !!}</p> --}}

                            @if(Auth::guard('web')->user())
                                @include('front.include.subscribe_In_site', [ 'siteToSubscribe' => $item, 'userFinishedAtLeastOneSite' => $item->userFinishedAtLeastOneSite ] )
                            @else
                                <a href="{{ route('login') }}" class="btn but-more">اشترك فى الدبلوم</a>
                            @endif

                            <span>{{ $item->siteCourseZoomDayStatus }}</span>

                            <!-- <div class="addthis_inline_share_toolbox"></div> -->
                        </div>
                    </div>
                </div>
              @endif
          @endforeach
        </div>

        <div id="stage_02"></div>
        <div class="row" id="ItemsDiv">
            @foreach ($result as $item)

                @if ($item->created_at >= '2022-02-06')  <!-- new sites -->
                  <div class="ItemDiv col-lg-4 col-md-6">
                      <div class="single-courses-item mb-30 owl-item filterDiv {{ $item->created_at < '2022-02-06' ? 'finished' : 'new' }} show_div">
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
                              <!-- <span>دبلومات المرحلة الثانية</span> -->
                              <div class="d-flex">
                                <span style="margin: 7px 5px;display: block;font-size: 15px;font-weight: bold;color: #aaa38c;">{{ $item->courses_count}} دورة</span>
                                <span style="margin: 7px 5px;display: block;font-size: 15px;font-weight: bold;" class="prim-color"><i class="far fa-clock" style="padding: 0px 6px;"></i>{{ $item->getVideosDuration()  }} ساعة</span>
                                <span style="margin: 7px 4px;display: block;font-size: 15px;font-weight: bold;" class="prim-color"><i class="bx bx-user" style="padding: 0px 5px;"></i>{{ $item->site_subscription_count  }} مشترك</span>

                                @if($item->siteNotCompleted == 1)
                                  <span class="but-special" style="padding-top: 14px; margin-bottom: 10px;border-radius: 14px;" >جاهز</span>
                                @endif

                              </div>
                              {{-- <p>{!! $item->description !!}</p> --}}

                              @if(Auth::guard('web')->user())
                                  {{--
                                  <!-- null means this property doesn't exixts in the site, if there is this property means this site has spacific sites to finish before subscribe -->
                                  @if ($item->userFinishDependents !== null)
                                      @if ($item->userFinishDependents === true ) <!-- user finished depndents site -->
                                            <div id="div_sub_{{ $item->id }}" class="{{ $item->isUserSubscribedInSite ? 'show_div' : 'hide_div' }}">
                                              <button att-id="{{ $item->id }}" att-URL="{{ route('diplomas.subscribers', ['site'=>$item->alias] ) }}"
                                                  class="btn btn-success subscribe_in_site" style="background-color: #b57f4b;border: none;">اشترك فى الدبلوم
                                              </button>
                                            </div>
                                            <div id="div_already_sub_{{ $item->id }}" class="{{ $item->isUserSubscribedInSite ? 'hide_div' : 'show_div' }}" style="font-weight: bold;color: green;"><i class="fas fa-users" style="font-size: 22px;"></i> مشترك </div>
                                      @else
                                            <div class="new_sites_subs_alert">التسجيل فقط لمن أنهى {{ $item->sitesMustFinishToSubscribeTitles }}</div>
                                      @endif
                                  @else
                                      <!-- if no dependnts for this site so user must finish any site from old sites -->
                                      @if ($userFinishedAtLeastOneSite)
                                          <div id="div_sub_{{ $item->id }}" class="{{ $item->isUserSubscribedInSite ? 'show_div' : 'hide_div' }}">
                                            <button att-id="{{ $item->id }}" att-URL="{{ route('diplomas.subscribers', ['site'=>$item->alias] ) }}"
                                                class="btn btn-success subscribe_in_site" style="background-color: #b57f4b;border: none;">اشترك فى الدبلوم
                                            </button>
                                          </div>
                                          <div id="div_already_sub_{{ $item->id }}" class="{{ $item->isUserSubscribedInSite ? 'hide_div' : 'show_div' }}" style="font-weight: bold;color: green;"><i class="fas fa-users" style="font-size: 22px;"></i> مشترك </div>
                                      @else
                                          <div class="new_sites_subs_alert">التسجيل فقط لمن أنهى دبلوم واحد على الأقل من الدبلومات الجاهزة</div>
                                      @endif
                                  @endif
                                  --}}
                                  @include('front.include.subscribe_In_site', [ 'siteToSubscribe' => $item, 'userFinishedAtLeastOneSite' => $item->userFinishedAtLeastOneSite ] )

                              @else
                                  <a href="{{ route('login') }}" class="btn btn-success" style="background-color: #b57f4b;border: none;">اشترك فى الدبلوم</a>
                              @endif

                              <span>{!! $item->siteCourseZoomDayStatus !!}</span>

                              <!-- <div class="addthis_inline_share_toolbox"></div> -->
                          </div>
                      </div>
                  </div>
                @endif


            @endforeach
          {{--
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="pagination-area text-center">
                    <span class="page-numbers current" aria-current="page">1</span>
                    <a href="#" class="page-numbers">2</a>
                    <a href="#" class="page-numbers">3</a>
                    <a href="#" class="page-numbers">4</a>
                    <a href="#" class="page-numbers">5</a>
                    <a href="#" class="next page-numbers"><i class='bx bx-chevron-right'></i></a>
                </div>
            </div>
          --}}
        </div>

      </div>
    </section>
    <!-- End Courses Area -->







@endsection
@section('script')
<script>

// --------------------------------------------------- filter divs
var anchorFromUrl = window.location.hash;
if (anchorFromUrl){
  anchorFromUrl = anchorFromUrl.substring(anchorFromUrl.indexOf("#")+1);
  if (anchorFromUrl == 'stage_02'){
    filterSelection("new");
  }
  if (anchorFromUrl == 'stage_01'){
    filterSelection("finished");
  }
} else {
  filterSelection("all")
}


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
    this.className += " active_but sec-back-color-dark";
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
