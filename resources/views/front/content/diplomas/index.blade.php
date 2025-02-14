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
    .show_div{ display: flex; text-align: center;} /*float: left;*/
    .hide_div{ display: none}
    .active_but{padding: 5px 26px; color: white;background-color: green}
    .normal_but{padding: 5px 26px; border: 1px solid #b5b2b2; }

    .zoom_day_course{ font-weight: bold; color: red;}
    .zoom_day_date_at{ font-weight: bold;}
</style>

@endsection
@section('content')



<!-- Start Page Title Area -->
<div class=" item-bg3 jarallax inner_banner" data-jarallax='{"speed": 0.3}' style="background-image: url({{ asset('assets/front/img/bg-img/bg1.jpg') }});">
    <div class="container">
        <div class="page-title-content" style="padding-top: 10px !important;">
            <h2 class="inner_page_title">{{ $parent_site?->name }}</h2>
        </div>
    </div>
</div>


    @include('front.content.auth.register_every_page')

    @include('front.include.global_alert')

    <div class="col-12" style="text-align: center; padding: 15px 0px;">
      @include('front.include.support_videos.how_to_study')
    </div>





    <section class="courses-area ptb-100" style="padding-top: 18px;">
      <div class="container">

        <div class="page-title-content bread-crumb">
            <ul>
                <li><a style="color: gray" href="{{ route('home') }}">{{ __('trans.home') }}</a></li> /
                @if (! $parent_site)
                  <li style="color: gray">{{ __('trans.diplomas') }}</li>
                @else
                  <li style="color: gray"><a href="{{route('diplomas.index')}}">{{ __('trans.diplomas') }}</a></li> /
                  <li style="color: gray">{{ $parent_site->name }}</li>
                @endif

            </ul>
        </div>



        <div style="padding-bottom: 20px;">
            <div class="row justify-content-center">

                {{--
                @if (! Auth::guard('web')->user())
                  @if (Auth::id() == 5972)
                  <div class="col-lg-12" style="text-align: center;padding-bottom: 25px;">
                      <span class="btn btn-success sub-dip"><a href="{{ route('diplomas.subscribers', 'all') }}">الإشتراك فى كل الدبلومات</a></span>
                  </div>
                  @endif
                @endif
                --}}

                <div class="col-lg-5 col-md-6 col-sm-6" id="filter_btns" style="text-align: center;padding-bottom: 0px;">
                    <button class="btn active_but " onclick="filterSelection('finished')">{{ __('trans.diplomas_all') }}</button>
                    <a href="#stage_03" class="btn normal_but" onclick="filterSelection('newest')">{{ __('trans.diplomas_next') }}</a>
                </div>

            </div>
        </div>


        <div id="stage_01"></div>





        <div class="row filterDiv finished show_div" id="ItemsDiv">

          <div class="container">
            <div class="row" style="text-align: center;padding: 25px 0px;">
                @foreach ($sitesTree as $root)
                  <div class="col-4" style="text-align: center; padding: 3px 1px;">
                    <a style="color: red; text-decoration: underline;" class="link-sites"  href="#div_root_site{{ $root->id }}">{{ $root->name }}</a>
                  </div>
                @endforeach
            </div>
          </div>



            @foreach ($sitesTree as $root)
                <div class="col-lg-12" id="div_root_site{{ $root->id }}" style=" text-align: left;padding: 0px 0px 30px 0px;">
                  <h2 class="prim-color" style="font-weight: bold;"><i class="far fa-list-alt"></i>{{ $root->name }}</h2>
                </div>

                @foreach ($root->children ?? [] as $site)
                <div class="ItemDiv col-lg-4 col-md-6">
                    <div class="single-courses-item mb-30 owl-item">
                        <div class="courses-image">

                            @if (isset($site->userSiteDegree))
                            <div class="corner-ribbon bottom-left sticky orange"
                                style="{{ $site->userSiteRate > 0 ? 'background-color: #0ea921 !important; background-image: none;' :  'background-color: red !important; background-image: none;'  }}"
                            >{{ $site->userSiteDegree }} %</div>
                            @endif

                            <a href="{{ route('courses.index',$site->slug) }}" class="d-block"><img src="{{ url($site->ImageDetailsPath) }}" alt="image"></a>
                        </div>
                        <div class="courses-content">
                            <div class="d-flex justify-content-between align-items-center">
                              <div class="course-author d-flex align-items-center">
                                <img class="shadow img-owl"  src="{{ asset('assets/img/logo2.png') }}" alt="{{ $site->name }}" >
                                <span>@lang('core.app_name')</span>
                              </div>
                            </div>
                            <h3><a href="{{ route('courses.index',$site->slug) }}" class="d-inline-block">{{ $site->name }}</a></h3>

                            <div class="d-flex">
                                <div style="width: 20%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">{{ $site->courses_count}}<br>{{ __('trans.course') }}</div>
                                <div style="width: 50%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">&nbsp;&nbsp;{{ $site->getVideosDuration() }} <br><i class="far fa-clock" style="padding: 0px 4px;"></i>{{ __('trans.hour') }}</div>
                                <div style="width: 30%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">{{ $site->site_subscription_count  }}<br><i class="bx bx-user" style="padding: 0px 4px;"></i>{{ __('trans.subscribers') }}</div>

                            </div>

                            @if(Auth::guard('web')->user())
                                @include('front.include.subscribe_in_site', [ 'siteToSubscribe' => $site ] )
                            @else
                                <a href="{{ route('login') }}" class="btn but-more">{{ __('trans.subscribe_in_diploma') }}</a>
                            @endif

                            <div style="text-align: center;">
                                <span>
                                    @if ($site->siteCourseZoomDayStatus['status'] == 'course_zoom_today')
                                        <div><i class="fas fa-wifi" style="color: gray;"></i>{{ __('trans.onair_today') }}<span class="zoom_day_course">{{ $site->siteCourseZoomDayStatus['course_name'] }}</span></div>
                                    @endif
                                    @if ($site->siteCourseZoomDayStatus['status'] == 'course_zoom_after_today')
                                      @if ($site->new_flag == 2)
                                        <div style="color: orangered;font-weight: bold;text-align: center;padding: 16px 0px 0px 0px;"><i class="fas fa-wifi" style="color: orangered;"></i>{{ __('trans.diploma_start_at') }}<span class="zoom_day_date_at"> {{ $site->siteCourseZoomDayStatus['course_date_at'] }}</span></div>
                                      @else
                                        <div><i class="fas fa-wifi" style="color: gray;"></i>{{ __('trans.next_course') }}<span class="zoom_day_course">{{ $site->siteCourseZoomDayStatus['course_name'] }}</span><br>{{ __('trans.onair_at') }}<span class="zoom_day_date_at"> {{ $site->siteCourseZoomDayStatus['course_date_at'] }}</span></div>
                                      @endif
                                    @endif
                                </span>


                                @if($site->siteNotCompleted == 1)
                                  <span class="" style="padding-top: 14px; margin-bottom: 10px;border-radius: 14px;color: #563ff2 !important;font-weight: bold;" >
                                    <i class="fas fa-user-graduate" style="font-size: 20px;padding: 0px 7px;"></i>{{ __('trans.diploma_done_can_study') }}
                                  </span>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>
                @endforeach

            @endforeach
        </div>

          <!-- عرض الفصول الدراسية(terms) التابعة لهذه السنة الدراسية(diplomas)  -->
          <div class="row filterDiv finished show_div" id="ItemsDiv">
              @foreach ($sitesTree as $site)
                  <div class="ItemDiv col-lg-4 col-md-6">
                      <div class="single-courses-item mb-30 owl-item">
                          <div class="courses-image">

                              @if (isset($site->userSiteDegree))
                                  <div class="corner-ribbon bottom-left sticky orange"
                                       style="{{ $site->userSiteRate > 0 ? 'background-color: #0ea921 !important; background-image: none;' :  'background-color: red !important; background-image: none;'  }}"
                                  >{{ $site->userSiteDegree }} %</div>
                              @endif

                              @if (empty($site->children))
                                  <a href="{{ route('courses.index', $site->slug) }}" class="d-block"><img src="{{ url($site->ImageDetailsPath) }}" alt="image"></a>
                              @else
                                  <a href="{{ route('diplomas.index', $site->slug) }}" class="d-block"><img src="{{ url($site->ImageDetailsPath) }}" alt="image"></a>
                              @endif
                          </div>
                          <div class="courses-content">
                              <div class="d-flex justify-content-between align-items-center">
                                  <div class="course-author d-flex align-items-center">
                                      <img class="shadow img-owl"  src="{{ asset('assets/img/logo2.png') }}" alt="{{ $site->name }}" >
                                      <span>@lang('core.app_name')</span>
                                  </div>
                              </div>
                              <h3>
                                  @if (empty($site->children))
                                      <a href="{{ route('courses.index',$site->slug) }}" class="d-inline-block">{{ $site->name }}</a>
                                  @else
                                      <a href="{{ route('diplomas.index',$site->slug) }}" class="d-inline-block">{{ $site->name }}</a>
                                  @endif
                              </h3>

                              <div class="d-flex">
                                  <div style="width: 20%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">{{ $site->courses_count}}<br>{{ __('trans.course') }}</div>
                                  <div style="width: 50%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">&nbsp;&nbsp;{{ $site->getVideosDuration() }} <br><i class="far fa-clock" style="padding: 0px 4px;"></i>{{ __('trans.hour') }}</div>
                                  <div style="width: 30%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">{{ $site->site_subscription_count  }}<br><i class="bx bx-user" style="padding: 0px 4px;"></i>{{ __('trans.subscribers') }}</div>

                              </div>

                              @if(Auth::guard('web')->user())
                                  @include('front.include.subscribe_in_site', [ 'siteToSubscribe' => $site ] )
                              @else
                                  <a href="{{ route('login') }}" class="btn but-more">{{ __('trans.subscribe_in_diploma') }}</a>
                              @endif

                              <div style="text-align: center;">
                            <span>
                                @if ($site->siteCourseZoomDayStatus['status'] == 'course_zoom_today')
                                    <div><i class="fas fa-wifi" style="color: gray;"></i>{{ __('trans.onair_today') }}<span class="zoom_day_course">{{ $site->siteCourseZoomDayStatus['course_name'] }}</span></div>
                                @endif
                                @if ($site->siteCourseZoomDayStatus['status'] == 'course_zoom_after_today')
                                    @if ($site->new_flag == 2)
                                        <div style="color: orangered;font-weight: bold;text-align: center;padding: 16px 0px 0px 0px;"><i class="fas fa-wifi" style="color: orangered;"></i>{{ __('trans.diploma_start_at') }}<span class="zoom_day_date_at"> {{ $site->siteCourseZoomDayStatus['course_date_at'] }}</span></div>
                                    @else
                                        <div><i class="fas fa-wifi" style="color: gray;"></i>{{ __('trans.next_course') }}<span class="zoom_day_course">{{ $site->siteCourseZoomDayStatus['course_name'] }}</span><br>{{ __('trans.onair_at') }}<span class="zoom_day_date_at"> {{ $site->siteCourseZoomDayStatus['course_date_at'] }}</span></div>
                                    @endif
                                @endif
                            </span>

                                  @if($site->siteNotCompleted == 1)
                                      <span class="" style="padding-top: 14px; margin-bottom: 10px;border-radius: 14px;color: #563ff2 !important;font-weight: bold;" >
                                <i class="fas fa-user-graduate" style="font-size: 20px;padding: 0px 7px;"></i>{{ __('trans.diploma_done_can_study') }}
                              </span>
                                  @endif
                              </div>


                          </div>
                      </div>
                  </div>

              @endforeach
          </div>


        {{--
          <!-- عرض صفحة للروت الاول وعند الضغط يدخل للروت الثانى -->
        <div class="row filterDiv finished show_div" id="ItemsDiv">
            @foreach ($sitesTree as $site)
            <div class="ItemDiv col-lg-4 col-md-6">
                <div class="single-courses-item mb-30 owl-item">
                    <div class="courses-image">

                        @if (isset($site->userSiteDegree))
                        <div class="corner-ribbon bottom-left sticky orange"
                            style="{{ $site->userSiteRate > 0 ? 'background-color: #0ea921 !important; background-image: none;' :  'background-color: red !important; background-image: none;'  }}"
                        >{{ $site->userSiteDegree }} %</div>
                        @endif

                        @if (empty($site->children))
                          <a href="{{ route('courses.index', $site->slug) }}" class="d-block"><img src="{{ url($site->ImageDetailsPath) }}" alt="image"></a>
                        @else
                          <a href="{{ route('diplomas.index', $site->slug) }}" class="d-block"><img src="{{ url($site->ImageDetailsPath) }}" alt="image"></a>
                        @endif
                    </div>
                    <div class="courses-content">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="course-author d-flex align-items-center">
                            <img class="shadow img-owl"  src="{{ asset('assets/img/logo2.png') }}" alt="{{ $site->name }}" >
                            <span>@lang('core.app_name')</span>
                          </div>
                        </div>
                        <h3>
                          @if (empty($site->children))
                            <a href="{{ route('courses.index',$site->slug) }}" class="d-inline-block">{{ $site->name }}</a>
                          @else
                            <a href="{{ route('diplomas.index',$site->slug) }}" class="d-inline-block">{{ $site->name }}</a>
                          @endif
                        </h3>

                        <div class="d-flex">
                            <div style="width: 20%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">{{ $site->courses_count}}<br>{{ __('trans.course') }}</div>
                            <div style="width: 50%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">&nbsp;&nbsp;{{ $site->getVideosDuration() }} <br><i class="far fa-clock" style="padding: 0px 4px;"></i>{{ __('trans.hour') }}</div>
                            <div style="width: 30%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">{{ $site->site_subscription_count  }}<br><i class="bx bx-user" style="padding: 0px 4px;"></i>{{ __('trans.subscribers') }}</div>

                        </div>

                        @if(Auth::guard('web')->user())
                            @include('front.include.subscribe_in_site', [ 'siteToSubscribe' => $site ] )
                        @else
                            <a href="{{ route('login') }}" class="btn but-more">{{ __('trans.subscribe_in_diploma') }}</a>
                        @endif

                        <div style="text-align: center;">
                            <span>
                                @if ($site->siteCourseZoomDayStatus['status'] == 'course_zoom_today')
                                    <div><i class="fas fa-wifi" style="color: gray;"></i>{{ __('trans.onair_today') }}<span class="zoom_day_course">{{ $site->siteCourseZoomDayStatus['course_name'] }}</span></div>
                                @endif
                                @if ($site->siteCourseZoomDayStatus['status'] == 'course_zoom_after_today')
                                  @if ($site->new_flag == 2)
                                    <div style="color: orangered;font-weight: bold;text-align: center;padding: 16px 0px 0px 0px;"><i class="fas fa-wifi" style="color: orangered;"></i>{{ __('trans.diploma_start_at') }}<span class="zoom_day_date_at"> {{ $site->siteCourseZoomDayStatus['course_date_at'] }}</span></div>
                                  @else
                                    <div><i class="fas fa-wifi" style="color: gray;"></i>{{ __('trans.next_course') }}<span class="zoom_day_course">{{ $site->siteCourseZoomDayStatus['course_name'] }}</span><br>{{ __('trans.onair_at') }}<span class="zoom_day_date_at"> {{ $site->siteCourseZoomDayStatus['course_date_at'] }}</span></div>
                                  @endif
                                @endif
                            </span>

                            @if($site->siteNotCompleted == 1)
                              <span class="" style="padding-top: 14px; margin-bottom: 10px;border-radius: 14px;color: #563ff2 !important;font-weight: bold;" >
                                <i class="fas fa-user-graduate" style="font-size: 20px;padding: 0px 7px;"></i>{{ __('trans.diploma_done_can_study') }}
                              </span>
                            @endif
                        </div>


                    </div>
                </div>
            </div>

            @endforeach
        </div>
        --}}






        @if (isset($newstSites))
        <div id="stage_03"></div>
        <div class="row filterDiv newest hide_div" id="ItemsDiv">
            <div class="col-lg-12" style=" margin-top: 40px; text-align: left;padding: 0px 0px 20px 0px;"><h2 class="prim-color" style="font-weight: bold; font-size: 21px !important;"><i class="far fa-list-alt"></i>{{ __('trans.diplomas_next') }}</h2></div>

            @foreach ($newstSites as $site)
                  <div class="ItemDiv col-lg-4 col-md-6">
                      <div class="single-courses-item mb-30 owl-item">
                          <div class="courses-image">

                              @if (isset($site->userSiteDegree))
                              <div class="corner-ribbon bottom-left sticky orange"
                                  style="{{ $site->userSiteRate > 0 ? 'background-color: #0ea921 !important; background-image: none;' :  'background-color: red !important; background-image: none;'  }}"
                              >{{ $site->userSiteDegree }} %</div>
                              @endif

                              <a href="{{ route('courses.index',$site->slug) }}" class="d-block"><img src="{{ url($site->ImageDetailsPath) }}" alt="image"></a>
                          </div>

                          <div class="courses-content">
                              <div class="d-flex justify-content-between align-items-center">
                                  <div class="course-author d-flex align-items-center">
                                      <img class="shadow img-owl"  src="{{ asset('assets/img/logo2.png') }}" alt="{{ $site->name }}" >
                                      <span>@lang('core.app_name')</span>
                                  </div>
                              </div>

                              <h3><a href="{{ route('courses.index',$site->slug) }}" class="d-inline-block">{{ $site->name }}</a></h3>
                              <div class="d-flex">
                                <div style="width: 20%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">{{ $site->courses_count}}<br>{{ __('trans.course') }}</div>
                                <div style="width: 50%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">&nbsp;&nbsp;{{ $site->getVideosDuration() }} <br><i class="far fa-clock" style="padding: 0px 4px;"></i>{{ __('trans.hour') }}</div>
                                <div style="width: 30%;display: block;font-size: 15px;font-weight: bold;text-align: center;" class="prim-color">{{ $site->site_subscription_count  }}<br><i class="bx bx-user" style="padding: 0px 4px;"></i>{{ __('trans.subscribers') }}</div>

                                {{--
                                @if($site->siteNotCompleted == 1)
                                  <span class="but-special" style="padding-top: 14px; margin-bottom: 10px;border-radius: 14px;" >جاهز</span>
                                @endif
                                --}}

                              </div>

                              @if(Auth::guard('web')->user())
                                  @include('front.include.subscribe_in_site', [ 'siteToSubscribe' => $site ] )
                              @else
                                  <a href="{{ route('login') }}" class="btn btn-success" style="background-color: #b57f4b;border: none;">{{ __('trans.subscribe_in_diploma') }}</a>
                              @endif

                              <span>
                                  @if(! $site->siteCourseZoomDayStatus)
                                      {{--dd($site)--}}
                                  @endif

                                  @if($site->siteCourseZoomDayStatus)
                                      @if ($site->siteCourseZoomDayStatus['status'] == 'course_zoom_after_today')
                                        @if ($site->new_flag == 2)
                                          <div style="color: orangered;font-weight: bold;text-align: center;padding: 16px 0px 0px 0px;"><i class="fas fa-wifi" style="color: orangered;"></i>{{ __('trans.onair_start_at') }}<span class="zoom_day_date_at"> {{ $site->siteCourseZoomDayStatus['course_date_at'] }}</span></div>
                                        @else
                                          <div><i class="fas fa-wifi" style="color: gray;"></i>{{ __('trans.next_course') }}<span class="zoom_day_course">{{ $site->siteCourseZoomDayStatus['course_name'] }}</span><br>{{ __('trans.onair_at') }}<span class="zoom_day_date_at"> {{ $site->siteCourseZoomDayStatus['course_date_at'] }}</span></div>
                                        @endif
                                      @endif
                                  @endif
                              </span>

                          </div>
                      </div>
                  </div>
            @endforeach
        </div>
        @endif




    </section>








@endsection
@section('script')
<script>

// --------------------------------------------------- filter divs
var anchorFromUrl = window.location.hash;
if (anchorFromUrl){
    anchorFromUrl = anchorFromUrl.substring(anchorFromUrl.indexOf("#")+1);
    if (anchorFromUrl == 'stage_03'){
      filterSelection("newest");
    }
    if (anchorFromUrl == 'stage_02'){
      filterSelection("new");
    }
    if (anchorFromUrl == 'stage_01'){
      filterSelection("finished");
    }
} else {
    // filterSelection("all")
    filterSelection("finished")
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
    // if (c == "new"){
    //   subs_alert[0].classList.remove("hide_div")
    //   subs_alert[0].classList.add("show_div")
    // } else {
    //   subs_alert[0].classList.remove("show_div")
    //   subs_alert[0].classList.add("hide_div")
    // }
}


var filter_btns = document.getElementById("filter_btns");
var btns = filter_btns.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active_but");
    current[0].className = current[0].className.replace(" active_but", " normal_but");
    this.className += " active_but ";
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

<x-subscribe-in-site/>

@endsection
