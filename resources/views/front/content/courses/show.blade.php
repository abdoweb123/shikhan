@extends('front.layouts.the-index')
@section('head')

<link rel="preconnect" href="https://www.youtube.com">
<!-- swal -->
@include('front.layouts.new_design.css.sweetalert3')


<style>
  .for_mob{
    display: none !important;
  }
  @media only screen and (max-width: 767px){
    .hero-area{
      height: 150px !important;
    }
    .for_web{
      display: none !important;

    }
    .for_mob{
      display: block !important;
    }
    .courses-details-header .courses-meta ul li {
    font-size: 14px;
    margin-top: 15px;
    margin-right: 5px !important;
    padding-right: 0px !important;
    padding-left: 0;
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
            height: 350px;

        }
        .swal2-popup .swal2-select {
            display: none;
          }
          .courses-details-image.text-center {
              height: auto;
          }
          .courses-details-image.text-center iframe {
                height: 360px;
            }
          audio {
              width: 100%;
          }
          i.icon_live {
            color: red !important;
            text-transform: uppercase;
            font-size: 12px !important;
            padding: 3px;
            font-weight: 900;
            border: dashed 2px;
            margin: auto -15px;
        }
        label.lesson_titel {
            color: #5cc9df;
            font-size: 19px;
            font-weight: 900;
            cursor: pointer;
            text-decoration: underline;
        }
        .top_user_img{
          display: block;
          width: 50px;
          height: 50px;
          /* margin: 1em auto; */
          background-size: cover;
          background-repeat: no-repeat;
          background-position: center center;
          -webkit-border-radius: 99em;
          -moz-border-radius: 99em;
          border-radius: 99em;
          border: 5px solid white;
          box-shadow: 0 3px 2px rgba(0, 0, 0, 0.3);
          background-color: #efefef;
        }
        .show_div{ display: block; float: left;}
        .hide_div{ display: none}
</style>
@endsection

@section('content')


  @php
    $google_info = [];
    $google_info['name'] = $site->name . ' - ' . $course->name;
    $google_info['description'] = $course->meta_description;
    $google_info['created_at'] = $course->created_at;
    $google_info['updated_at'] = $course->updated_at;
    $google_info['author'] = __('core.app_name');
    $google_info['image'] = url($course->ImageDetailsPath);
    $google_info['video'] = '';
  @endphp


  <section class="courses-details-area" style="padding-top: 45px;">
    <div class="container">

      @include('front.include.global_alert')

      <div class="container page-title-content bread-crumb">
          <ul>
              <li><a href="{{ route('home') }}">{{ __('trans.home') }}</a></li> /
              <li><a href="{{ route('diplomas.index') }}">{{ __('trans.diplomas') }}</a></li> /
              <li><a href="{{ route('courses.index',$site->slug) }}">{{ $site->name }}</a></li> /
              <li>{{ $course->name }}</li>
          </ul>
      </div>


      <div class="courses-details-header">
          <div class="row align-items-center">


            <!-- courseTestResultsMoreThan -->
            @if(!empty($courseTestResultsMoreThan))
              <div class="col-lg-12" style="padding-bottom: 15px;border-bottom: 1px solid #dbdbdb;">
                <i class="fas fa-graduation-cap" style="font-size: 27px;color: #b57f4b;"></i>
                <span style="padding-left: 15px;padding-right: 10px;font-size: 30px;font-weight: bold;color: #659d16;"> أوائل الدورة</span>
              </div>
              <div class="col-lg-12" style="padding-top: 15px;">
                <div class="row">
                  @foreach($courseTestResultsMoreThan as $member)
                    <div class="col-lg-3" style="display: flex; text-align: right;padding-bottom: 5px;">
                      <div class="top_user_img" style="padding: 7px 3px;display: flex;">
                        <div style="color: #cb589d;font-size: 10px;">%</div>
                        <div style="color: #cb589d;font-size: 20px;">{{ $member->max_degree }} </div>
                      </div>
                      <span style="padding: 0px 5px;color: gray;font-weight: bold;">{{ $member->name }}</span>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif




              <div class="col-lg-8 ">
                  <div class="courses-title" style="font-size: 40px;">{{ $course->name }}</div>
                  <div class="courses-meta">
                      <ul>
                          <li>
                              <i class='bx bx-folder-open'></i>
                              <span class="prim-color">{{__('trans.diploma')}}</span>
                              <a href="{{route('courses.index',$site->slug)}}">{{$site->name}}</a>
                          </li>

                          <li>
                              <i class='bx bx-group'></i>
                              <span class="prim-color">{{__('trans.teachers')}}</span>
                              @if($lessons->first())
                                @php $currentTeacher = $lessons->first()->teacher; @endphp
                              @else
                                @php $currentTeacher = null; @endphp
                              @endif
                              <a href="@if($currentTeacher) {{ route('teachers.show', ['name' => $currentTeacher->alias]) }} @endif">
                                @if ($lessons->first())
                                  {{ $currentTeacher ? $currentTeacher->title : __('core.app_name')}}
                                  @php $google_info['author'] = $currentTeacher ? $currentTeacher->title : __('core.app_name'); @endphp
                                @else
                                  {{__('core.app_name') }}
                                @endif
                              </a>
                          </li>


                              @php
                                if($course->date_at != null){
                                  $to = \Carbon\Carbon::createFromFormat('Y-m-d', trim($course->date_at));
                                  $from = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                                  if($to > $from){
                                     $diff_in_days = $to->diffInDays($from);
                                 } else {
                                    $diff_in_days = $to->diffInDays($from) * -1;
                                 }

                                 $date = $to->addDays($course->duration - 1);
                                 if($date > $from){
                                     $diff_end_in_days = $date->diffInDays($from);
                                 }else{
                                     $diff_end_in_days = $date->diffInDays($from)* -1;
                                 }
                                }else{
                                 $diff_end_in_days =0;
                                 $diff_in_days=0;
                                }
                              @endphp


                              @if (Auth::guard('web')->user())
                                @if( $isUserSubscribedInSite )
                                    @if(date('Y-m-d',strtotime($course->date_at)) == date('Y-m-d'))
                                      <li>
                                          <i class="icon_live">{{__('words.live')}} </i>
                                          <span>{{__('words.link_zoom')}}</span>
                                          @if($lessons->first())
                                            <a href="{{$lessons->first()->link_zoom != null ? $lessons->first()->link_zoom : '#' }}" target="_blank">  {{$lessons->first()->link_zoom != null ? __('words.link_z') : __('words.link_z_soon') }} </a>
                                          @endif
                                      </li>
                                    @endif
                                @endif
                              @endif



                              @if($course->visual_test)
                                @if (Auth::guard('web')->user() )
                                    @if ($isUserSubscribedInSite)
                                      <a href="{{ route('front.course_tests_visual_show', [ 'site' => $site->slug, 'course' => $course->alias ]) }}">{!! __('trans.course_upload_but_title_189')!!}</a>
                                  @endif
                                @endif
                              @endif



                              <!-- display visual correction page to teachers -->
                              @if (Auth::guard('web')->user())
                                @if($lessons->first() && $lessons->first()->teacher && $lessons->first()->teacher->id == Auth::guard('web')->user()->teacher_id)
                                  <a href="{{ route('front.course_tests_visual_correction', [ 'site' => $site->slug, 'course' => $course->alias ]) }}">تصحيح الاختبار</a>
                                @endif
                              @endif







                              <!-- mob old way working -->
                              <li class="for_mob">
                                @if (Auth::guard('web')->user())
                                    @if ( $isUserSubscribedInSite )
                                        <!-- privouse tests same course  -->
                                        {{--@include('front.include.prev_results_same_course', ['prevResultsSameCourse' => $previousTestsSameCourse])--}}



                                        @if (! $isUserTestedCourse )
                                            @if ($course->isExamOpened())
                                              @if (ourAuth())
                                                <button type="submit" url="{{ route('courses.quiz',['site' => $site->slug,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>
                                              @endif
                                            @else
                                              <span style="color: red;border: 1px solid;padding: 2px 17px;border-radius: 7px;">  {{ $course->getDateDay($course->date_at) }}  {{$course->date_at}}  </span>
                                            @endif
                                        @elseif(Auth::guard('web')->user()->courseTestsCount($course->id, app()->getlocale()) < $trays )
                                          @if (ourAuth())
                                            <button type="submit" url="{{ route('courses.quiz',['site' => $site->slug,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_REPETITON')</button>
                                          @endif
                                        @else
                                            <span class="alert alert-warning " >{{__('core.invalid_quiz_count')}} </span>
                                        @endif
                                @else
                                    @include('front.include.subscribe_in_site', [ 'siteToSubscribe' => $site, 'reloadpage' => true ] )
                                @endif
                                @else
                                    <div class="courses-sidebar-information prim-color-border" style="padding: 20px 0px;margin: 15px 0px;">
                                      <div style="color: #d73d3d;font-size: 20px;font-weight: bold;padding-bottom: 10px;">@lang('core.test_now')</div>
                                      <a href="{{ route('login') }}" class="default-btn but-login">
                                        <i class="bx bx-log-in icon-arrow before" style="color: #8de7bf;"></i><span class="label">{{ __('trans.already_registered') }}</span><i class="bx bx-log-in icon-arrow after"></i>
                                      </a>
                                      <a href="{{ route('register') }}" class="default-btn but-login">
                                        <i class="bx bx-log-in-circle icon-arrow before" style="color: #8de7bf;"></i><span class="label">{{ __('trans.new_account') }}</span><i class="bx bx-log-in-circle icon-arrow  after"></i>
                                      </a>
                                    </div>
                                @endif
                              </li>


                      </ul>
                  </div>
              </div>

              <div class="col-lg-4 for_web">

              </div>
          </div>
      </div>



      <div class="row">
          <div class="col-lg-7">

              @php $videosCount = null ; @endphp


              <div class="row">
                <div class="col-8">
                  <div class="addthis_inline_share_toolbox"></div>
                </div>
                <div class="col-4">
                  <a onclick="incrementLikes(event,this,'course_likes_count')" style="cursor: pointer;display: flex;"
                    url="{{ route('front.course.increment_likes', ['lang' => app()->getLocale() ,'id' => $course->id ]) }}">
                    <div id='course_likes_count' style="font-size: 20px;">{{ $course->likes_count }} </div>
                    <i class="fas fa-thumbs-up" style="font-size: 26px;padding: 0px 8px;"></i>
                  </a>
                </div>
              </div>


              @if ($course->link_arabiceasily)
              <div class="col-lg-12">
                <a href="{{$course->link_arabiceasily}}" target="_blank" style="background-color: #fbb93a;  padding: 5px 30px;">arabiceasily.com</a>
              </div>
              @endif


              <div class="courses-details-desc">




                @if(file_exists('storage/app/public/'.$course->description) == true)
                    {!!  file_get_contents('storage/app/public/'.$course->description) !!}
                @endif





              </div>



              <!-- options-->

                 @foreach($lessons as $lesson)
                    @foreach($lesson->options->sortBy('sort') as $item)
                      @if($item->options->alias == 'video')
                        @php $videosCount = $videosCount + 1; @endphp
                        @include('components.options.video',['item' => $item, 'main_title' =>  __('words.lesson_video') . ' ' . $lesson->title])
                        @php $videosCount = $videosCount + 1; @endphp
                        @php
                          if (! $google_info['video']) {
                            $google_info['video'] = $item->value;
                          }
                        @endphp
                      @endif
                      @if($item->options->alias == 'source')
                        @include('components.options.source',['item' => $item])
                      @endif
                      @if($item->options->alias == 'pdf_download')
                        @include('components.options.pdf_download',['item' => $item, 'main_title' =>  __('words.lesson_pdf') . ' ' . $lesson->title ])
                      @endif
                      @if($item->options->alias == 'pdf_read')
                        @include('components.options.pdf_read',['item' => $item])
                      @endif
                      @if($item->options->alias == 'doc_read')
                        @include('components.options.doc_read',['item' => $item])
                      @endif
                      @if($item->options->alias == 'sound')
                        @include('components.options.sound',['item' => $item, 'main_title' =>  __('words.lesson_sound') . ' ' . $lesson->title ])
                      @endif
                    @endforeach
                 @endforeach


                 <img src="{{ url($course->ImageDetailsPath) }}" alt="{{ $course->name }}"  >

          </div>




          <div class="col-lg-5">


            <!-- web old way working -->
            <div class="courses-price text-center">
                <!-- <h1 class="text-center mb-3 ">{{ $course->name }}</h1> -->
                @if (Auth::guard('web')->user())
                    @if ( $isUserSubscribedInSite )

                        @include('front.include.prev_results_same_course', ['prevResultsSameCourse' => $previousTestsSameCourse])

                        @if (ourAuth())
                            <button type="submit" url="{{ route('courses.quiz',['site' => $site->slug,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_REPETITON')</button>
                        @elseif(! $course->isExamOpened())
                            <span style="color: red;border: 1px solid;padding: 2px 17px;border-radius: 7px;">  {{ $course->getDateDay($course->date_at) }}  {{$course->date_at}}  </span>
                        @elseif (! $isUserTestedCourse )
                            <button type="submit" url="{{ route('courses.quiz',['site' => $site->slug,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>
                        @elseif(Auth::guard('web')->user()->courseTestsCount($course->id, app()->getlocale()) < $trays )
                            <button type="submit" url="{{ route('courses.quiz',['site' => $site->slug,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_REPETITON')</button>
                        @else
                            <div class="alert alert-primary " >{{__('core.invalid_quiz_count')}} </div>
                        @endif
                    @else
                        @include('front.include.subscribe_in_site', [ 'siteToSubscribe' => $site, 'reloadpage' => true ] )
                    @endif
                @else
                    <div class="courses-sidebar-information prim-color-border" style="padding: 20px 0px;margin: 15px 0px;">
                      <div style="color: #d73d3d;font-size: 20px;font-weight: bold;padding-bottom: 10px;">@lang('core.test_now')</div>
                      <a href="{{ route('login') }}" class="default-btn but-login">
                        <i class="bx bx-log-in icon-arrow before" style="color: #8de7bf;"></i><span class="label">{{ __('words.login') }} {{ __('trans.already_registered')}} </span><i class="bx bx-log-in icon-arrow after"></i>
                      </a>
                      <a href="{{ route('register') }}" class="default-btn but-login">
                        <i class="bx bx-log-in-circle icon-arrow before" style="color: #8de7bf;"></i><span class="label">{{ __('trans.new_account')}}</span><i class="bx bx-log-in-circle icon-arrow  after"></i>
                      </a>
                    </div>
                @endif
            </div>


              <div class="courses-sidebar-information prim-color-border">
                  <ul>

                      <li>
                          <span><i class='bx bx-video-recording'></i> {{__('trans.video')}}:</span>
                          {{ $videosCount }}
                      </li>
                      <li>

                          <span><i class='bx bx-calendar'></i>  {{__('trans.course_start_at')}}: </span>
                          @if($course->date_at != null)
                              @if( $diff_end_in_days == 0 || $diff_in_days == 0)
                                    {{__('trans.live_course')}}
                              @elseif($diff_end_in_days < 0)
                                  {{__('trans.start_course')}}
                              @elseif($diff_in_days > 0)
                                {{$course->getDateDay($course->date_at)}} {{$course->date_at}} م
                              @endif
                          @else
                              {{__('words.not_have_date')}}
                          @endif
                      </li>
                      <li>
                          <span><i class='bx bx-time'></i> {{__('trans.duration')}}:</span>{{ $course->video_duration }}
                      </li>
                  </ul>
              </div>

              <div class="courses-details-desc courses-sidebar-syllabus p-0 text-center" style="border: none;">
                  <h3 class="text-center">{{__('trans.courses')}}</h3>
                  @if (Auth::guard('web')->user())
                      @if( $isUserSubscribedInSite )
                         <div class="courses-accordion">
                              <ul class="accordion">
                                  <li class="accordion-item">
                                      <a class="accordion-title active prim-back-color sec-color" style="border: none;" href="javascript:void(0)"><i class='bx bx-chevron-down'></i>{{$site->name}}</a>

                                      <div class="accordion-content show">
                                          <ul class="courses-lessons">
                                            @foreach( $otherCoursesInSite as $loopcourse )
                                               <li class="single-lessons">
                                                   <div class="d-md-flex d-lg-flex align-items-center">
                                                       <span class="number">{{$loop->iteration  <= 9 ? '0'.$loop->iteration   : $loop->iteration  }}.</span>
                                                       <a href="{{ route('courses.show',['site' => $site->slug,'course' => $loopcourse->alias]) }}" class="lessons-title">{{$loopcourse->name}}</a>
                                                   </div>

                                                   <div class="lessons-info">
                                                     @if (Auth::guard('web')->user())
                                                         @if (Auth::guard('web')->user()->courses()->find($loopcourse->id))
                                                             @if(Auth::guard('web')->user()->test_results->where('course_id', $loopcourse->id )->count() >= 3 )
                                                             @else
                                                              {{--<button type="submit" url="{{ route('courses.quiz',['site' => $site->slug,'course' => $loopcourse->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>--}}
                                                             @endif
                                                         @else

                                                         @endif
                                                     @endif
                                                   </div>
                                               </li>
                                            @endforeach
                                          </ul>
                                      </div>
                                  </li>
                              </ul>
                          </div>
                      @else
                          <p>{{__('words.ple_subscribe_for_see')}}</p>
                          @include('front.include.subscribe_in_site', ['siteToSubscribe' => $site, 'reloadpage' => true] )
                      @endif
                  @else
                      <div class="courses-accordion">
                           <ul class="accordion">
                               <li class="accordion-item">
                                   <a class="accordion-title active" href="javascript:void(0)">
                                       <i class='bx bx-chevron-down'></i>
                                       {{$site->name}}
                                   </a>


                                   <div class="accordion-content show">
                                       <ul class="courses-lessons">
                                         @foreach( $otherCoursesInSite as $loopcourse )
                                            <li class="single-lessons">
                                                <div class="d-md-flex d-lg-flex align-items-center">
                                                    <span class="number">{{$loop->iteration  <= 9 ? '0'.$loop->iteration   : $loop->iteration  }}.</span>
                                                    <a href="{{ route('courses.show',['site' => $site->slug,'course' => $loopcourse->alias]) }}" class="lessons-title">{{$loopcourse->name}}</a>
                                                </div>

                                                <div class="lessons-info">
                                                  @if (Auth::guard('web')->user())
                                                      @if (Auth::guard('web')->user()->courses()->find($loopcourse->id))
                                                          @if(Auth::guard('web')->user()->test_results->where('course_id', $loopcourse->id )->count() >= 3 )
                                                          @else
                                                           {{--<button type="submit" url="{{ route('courses.quiz',['site' => $site->slug,'course' => $loopcourse->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>--}}
                                                          @endif
                                                      @else

                                                      @endif
                                                  @endif
                                                </div>
                                            </li>
                                         @endforeach
                                       </ul>
                                   </div>


                               </li>




                           </ul>
                       </div>
                  @endif
              </div>

              <!-- display facebook comment -->
              {{--
              <div class="fb-comments" data-href="{{ request()->fullUrl() }}" data-width="400" data-numposts="5"></div>
              <div id="fb-root"></div>
              <script async defer crossorigin="anonymous" src="https://connect.facebook.net/ar_AR/sdk.js#xfbml=1&version=v12.0&appId=350513093516522&autoLogAppEvents=1" nonce="xWY9iXST"></script>
              --}}




          </div>
      </div>
    </div>
  </section>

  @if (Auth::guard('web')->user())
    @if($userTestsCountInCourse == 0)
      <div id="course_swear_text" style="display: none;">
        @include('front.include.course_swear_text_01')
      </div>
    @else
      <div id="course_swear_text" style="display: none;">
        @include('front.include.course_swear_text_02')
      </div>
    @endif
  @endif

@endsection


@section('script')
  <x-subscripe-in-site/>

  @include('front.layouts.new_design.js.sweetalert3-min')
  @include('front.include.course_swear_script')

  @include('front.include.google_schema.course', ['google_info' => $google_info ])
  @include('front.include.google_schema.video', ['google_info' => $google_info ])

<script>
function incrementLikes(e,me,div_result){
     e.preventDefault();
     var url = $(me).attr('url');
     console.log('output course');

     $.ajax({
         url: url,
         type: "get",
         data: {},
         success: function (data) {
           // console.log(data);
          document.getElementById(div_result).innerHTML = data['likes_count'];
          },error:function(data){
              // console.log(data.responseJSON);
          }
      });
};
</script>

@endsection
