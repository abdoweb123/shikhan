@extends('front.layouts.the-index')
@section('head')
<style>
  span.star_icon {
    cursor: pointer;
  }
  li#rating_bar {
    width: 30%;
  }
  #rating_bar:hover > span:before,#rating_bar:hover >span.star_icon.fa-star-o:before {
    color: #f2b827 !important;
  }
  span.star_icon.fa-star {
    color: #f2b827;
  }
  span.star_icon.star_half{

  }
  span.star_icon.star_o{
    color: #dedede !important;
    text-shadow: 1px 2px 12px #eabc12 !important;
  }
  #rating_bar > span:hover ~ span:before,#rating_bar > span.star_icon.fa-star-o:hover ~ span.star_icon.fa-star-o:before {
    color: #dedede !important;
    text-shadow: 1px 2px 12px #eabc12 !important;
  }
  .single-blog-post .post-image img {
      -webkit-transition: all 2s cubic-bezier(0.2, 1, 0.22, 1);
      transition: all 2s cubic-bezier(0.2, 1, 0.22, 1);
      height: 380px !important;
  }
  .main-banner-content h1 , .main-banner-content span , .main-banner-content p , .main-banner-content.text-center .sub-title  {
    color: #1d5ea4;
  }
  .main-banner-content .default-btn .label  {
    color: white;
  }
  .social i {
    color: #f2b827;
    font-size: 16px;
    margin-right: -2px;
  }
  .courses-details-desc .courses-accordion .accordion .accordion-item .accordion-title:hover, .courses-details-desc .courses-accordion .accordion .accordion-item .accordion-title.active {
    background-color: #623c16;
    color: #ffffff;
    border: #964d04 !important;
    box-shadow: 1px 0px 7px 2px #7c6044;
  }
  li.accordion-item {
    background: #e8bb8f !important;
    color: white !important;
  }
  .accordion-content.show {
    background: aliceblue;
  }
  .label .col-lg-3.col-sm-6 {
    max-width: 100% !important;
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
  .social i {
    margin: 2px !important;text-shadow: 1px 2px 12px #eabc12;
  }
</style>
@endsection
@section('content')

<section class="hero-area bg-img bg-overlay-2by5 inner_banner" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 "style=" margin-top: 10px;">
                <div class="page-title-content">
                    <h2 class="inner_banner">{{$teacher->title}}</h2>
                </div>
            </div>
        </div>
    </div>
</section>

@include('front.content.auth.register_every_page')

@include('front.include.global_alert')

<div class="container page-title-content bread-crumb">
    <ul>
        <li><a style="color: gray" href="{{ route('home') }}">{{ __('trans.home') }}</a></li> /
        <li><a style="color: gray" href="{{ route('teachers.index') }}">{{ __('trans.teachers') }}</a></li> /
        <li style="color: gray">{{ $teacher->title }}</li>
        <!-- <li></li> -->
    </ul>
</div>

 <!-- Start Instructor Details Area -->
<section class="instructor-details-area pt-100 pb-70">
    <div class="container">
        <div class="instructor-details-desc">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="instructor-details-sidebar">
                        <img src="{{ url($teacher->logo_path) }}" alt="{{$teacher->title}}" class="owl-item" style="border-radius: 20px;">
                    </div>
                </div>

                <div class="col-lg-8 col-md-8">
                    <div class="instructor-details">
                        <h3>{{$teacher->title}}</h3>
{{--
                        <h6>{{$teachers->qualification ? __('words.qualification') : '' }}</h6>
                        <h6>{{$teachers->specialization ? __('words.specialization') : '' }}</h6>
                        <h6>{{$teachers->position ? __('words.position') : '' }}</h6>
--}}
                        <h6>{{$teacher->birthdate ? __('words.birthdate') : '' }}</h6>
                        <h6>
                          {{--
                          @if ($teachers->country)
                            @php
                              $names = json_decode($teachers->country->name, true);
                              if (isset($names[app()->getlocale()])){
                                $name = $names[app()->getlocale()];
                              } else {
                                $name = $names['ar'];
                              }
                            @endphp

                            {{ __('words.country').' : '.$name }}

                          @endif
                          --}}
                        </h6>
                        <span class="sub-title">
                            {{--
                            <ul class="social">
                                <li class="starrr" id="rating_bar">
                                  <input type="hidden" name="rate" id="rate" value="{{@$teachers-> rated}}">
                                </li>
                                <li id="number_rated">
                                    {{$teachers-> rated}} ({{$teachers-> number_rated}})
                                </li>
                            </ul>
                            --}}
                        </span>
                        <div>

                            @if(file_exists('storage/app/public/'.$teacher->description) == true)
                                {!!  file_get_contents('storage/app/public/'.$teacher->description) !!}
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($courses->isNotEmpty())
        <!-- Start Courses Details Area -->
        <section class="courses-details-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="courses-details-desc">
                            <div class="courses-accordion">
                                <ul class="accordion">
                                    <li class="accordion-item">
                                        <a class="accordion-title item-dip active sec-back-color-dark" style="border: none !important;box-shadow: none !important;" href="javascript:void(0)">
                                            <i class='bx bx-chevron-down'></i>{{__('core.Courses by')}} : {{$teacher->title}}
                                        </a>

                                        <div class="accordion-content show">
                                            <ul class="courses-lessons">

                                              {{--
                                              @if (!Auth::guard('web')->user())
                                              <li class="single-lessons">
                                                  <div class="d-md-flex d-lg-flex align-items-center">
                                                      <span class="number">0.</span>
                                                      <span class="lessons-title">{{__('core.plase_logen_for_teacher')}}</span>
                                                  </div>

                                                  <div class="lessons-info">
                                                    <span class="label">
                                                        @include('front.units.steps')
                                                    </span>
                                                  </div>
                                              </li>
                                              @endif
                                              --}}

                                              @isset($courses)

                                                 @foreach($courses as $course)
                                                 {{--@if ( $course->sites()->MainSite()->first() )--}}
                                                    <li class="single-lessons">
                                                        <div class="d-md-flex d-lg-flex align-items-center">
                                                            <span class="number">{{$loop->iteration  <= 9 ? '0'.$loop->iteration   : $loop->iteration  }}.</span>
                                                            <a href="{{ route('courses.show',['site' => $course->sites()->first()->slug,'course' => $course->alias]) }}" class="lessons-title">{{$course->name}} </a>
                                                            <a href="{{ route('courses.index',$course->sites()->first()->slug)}}" class="lessons-title"> -- {{$course->sites()->first()->name}}</a>
                                                        </div>

                                                        {{--
                                                        <div class="lessons-info">
                                                          @if (Auth::guard('web')->user())
                                                              @if (Auth::guard('web')->user()->courses()->find($course->id))
                                                                  @if(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() >= 3 )
                                                                    <span class="alert alert-warning" >{{__('core.invalid_quiz_count')}} </span>
                                                                  @else
                                                                  <button type="submit" url="{{ route('courses.quiz',['site' => $course->sites()->MainSite()->first()->alias,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>

                                                                    <a href="{{ route('courses.quiz',['site' => $course->sites()->MainSite()->first()->alias,'course' => $course->alias]) }}" class="btn btn-success" style="margin: 10px;"> @lang('core.test_now') </a>
                                                                  @endif
                                                                  @if(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() >= 1 )
                                                                      <a class="btn btn-info" href="{{ route('certificates') }}" style="margin: 10px;">
                                                                          <i class="fas fa-certificate"></i>
                                                                          @lang('meta.title.certificates')
                                                                      </a>
                                                                  @else
                                                                    <a href="{{ route('courses.unsubscription',['site' => $course->sites()->MainSite()->first()->alias,'course' => $course->alias]) }}" class="btn btn-danger" style="margin: 10px;"> @lang('core.unsubscribe') </a>
                                                                  @endif
                                                              @else

                                                                  <a href="{{ route('courses.subscription',['site' => $course->sites()->MainSite()->first()->alias,'course' => $course->alias]) }}" class="default-btn">
                                                                      <i class='bx bx-paper-plane icon-arrow before'></i>
                                                                      <span class="label">@lang('core.newsletter_submit') </span>
                                                                      <i class="bx bx-paper-plane icon-arrow after"></i>
                                                                  </a>


                                                              @endif
                                                          @endif
                                                        </div>
                                                        --}}
                                                    </li>
                                                 {{--@endif--}}
                                                 @endforeach
                                             @endisset

                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Courses Details Area -->
        @endif



    </div>
</section>
<!-- End Instructor Details Area -->


@endsection
@section('script')
<script>

        $(document).ready(function () {
          // rate cuase menu disapeared



$( '.v_q_alert' ).click(function(e) {
     e.preventDefault();
     var url = $(this).attr('url');
     Swal.fire({
       title: '{{__("words.q_title")}}',
       icon: 'question',
       html: "{{__('words.q_alert1')}} <br/> {{__('words.q_alert2')}}",

       confirmButtonText: '{{__("words.q_Yes")}}',
       cancelButtonText: '{{__("words.no")}}',
       showCancelButton: true,
       showCloseButton: true
     }).then((result) => {
                   if (result.value) {
                             window.location.href = url
                   }
                 })

             });
});
</script>
@endsection
