@extends('front.layouts.new')
@section('head')
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

</style>

@endsection

@section('content')
{{----}}
<!-- ##### Hero Area Start ##### -->
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
<!-- ##### Hero Area End ##### -->
        <!-- Start Courses Details Area -->
        <section class="courses-details-area pt-100 pb-70">
            <div class="container">
                @if (session()->has('flashAlerts'))
                      <div class="kt-section">
                        <div class="kt-section__content">
                          @foreach (session('flashAlerts') as $key => $flashAlert)
                            @if (! isset($flashAlert['type']))
                              <div class="alert alert-{{ ($key == 'faild') ? 'danger' : $key }}" role="alert">
                                <div class="alert-text text-center">{{ $flashAlert['msg'] }}</div>
                              </div>
                            @endif
                          @endforeach
                        </div>
                      </div>
                    @endif
                <div class="courses-details-header">
                    <div class="row align-items-center">
                        <div class="col-lg-8 ">
                            <div class="courses-title">
                                {{ $course->name }}
                            </div>

                            <div class="courses-meta">
                                <ul>
                                    <li>
                                        <i class='bx bx-folder-open'></i>
                                        <span>{{__('words.Category')}}</span>
                                        <a href="{{route('courses.index',$site->alias)}}">{{$site->name}}</a>
                                    </li>
                                    <li>
                                        <i class='bx bx-group'></i>
                                        <span>{{__('words.teachers')}}</span>

                                        <a href=" @if($lessons->first()){{$lessons->first()->teacher != null? route('teachers.show',str_replace(' ', '_', $lessons->first()->teacher->name)) : url('/'.app()->getlocale()) }}@else {{url('/'.app()->getlocale()) }} @endif">
                                          @if($lessons->first())
                                            {{$lessons->first()->teacher != null ? $lessons->first()->teacher->name : __('core.app_name') }}
                                          @else
                                           {{__('core.app_name') }}
                                          @endif
                                        </a>
                                    </li>
                                        @php

                                        $pdf=$lessons->first() != null ? $lessons->first()->pdf : null;
                                        if($pdf !=null){
                                          $pdfname =App\LessonOption::where(['lesson_id'=>$lessons->first()->id,'option_id'=>1])->first();
                                          $pdf = str_replace('/uc?id=','/file/d/',$pdf);
                                          $pdf= str_replace(['/view?usp=sharing','&export','/preview'],'',$pdf);
                                        }

                                            if($course->date_at != null){
                                              $to = \Carbon\Carbon::createFromFormat('Y-m-d', $course->date_at);
                                              $from = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                                              if($to > $from){
                                                 $diff_in_days = $to->diffInDays($from);
                                             }else{
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
                                          @if( Auth::guard('web')->user()->courses()->find($course->id) )
                                              @if(date('Y-m-d',strtotime($course->date_at)) == date('Y-m-d'))
                                                <li>
                                                    <i class="icon_live">{{__('words.live')}} </i>
                                                    <span>{{__('words.link_zoom')}}</span>
                                                    <a href="{{$lessons->first()->link_zoom != null ? $lessons->first()->link_zoom : '#' }}" target="_blank">  {{$lessons->first()->link_zoom != null ? __('words.link_z') : __('words.link_z_soon') }} </a>
                                                </li>
                                              @endif
                                          @endif
                                        @endif
                                        <li class="for_mob">
                                          @if (Auth::guard('web')->user())
                                              @if (Auth::guard('web')->user()->courses()->find($course->id))
                                                @if(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() < 1 )
                                                  <button type="submit" url="{{ route('courses.quiz',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>
                                                  <a href="{{ route('courses.unsubscription',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-danger" style="margin: 10px;"> @lang('core.unsubscribe') </a>

                                                @elseif(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() < 3 )
                                                  <button type="submit" url="{{ route('courses.quiz',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_REPETITON')</button>
                                                  <a href="{{ route('courses.unsubscription',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-danger" style="margin: 10px;"> @lang('core.unsubscribe') </a>

                                                @else
                                                  <span class="alert alert-warning mt-1" >{{__('core.invalid_quiz_count')}} </span>
                                                @endif
                                              @else
                                                  <a href="{{ route('courses.subscription',['site' => $site->alias,'course' => $course->alias]) }}" class="default-btn"><i class='bx bx-paper-plane icon-arrow before'></i><span class="label">@lang('core.newsletter_submit') </span><i class="bx bx-paper-plane icon-arrow after"></i></a>
                                              @endif
                                          @else
                                              <a class="btn" href="{{ route('login') }}"  style="margin: 0px;color: white;background-color: #d73d3d;">@lang('core.test_now')</a>
                                              {{--@include('front.units.steps')--}}
                                          @endif
                                        </li>
                                </ul>
                            </div>
                        </div>



                        <div class="col-lg-4 for_web">
                            <div class="courses-price text-center">
                                <h1 class="text-center mb-3 ">{{ $course->name }}</h1>
                                @if (Auth::guard('web')->user())
                                    @if (Auth::guard('web')->user()->courses()->find($course->id))
                                        @if(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() < 1 )
                                          <button type="submit" url="{{ route('courses.quiz',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>
                                          <a href="{{ route('courses.unsubscription',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-danger" style="margin: 10px;"> @lang('core.unsubscribe') </a>

                                        @elseif(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() < 3 )
                                          <button type="submit" url="{{ route('courses.quiz',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_REPETITON')</button>
                                          <a href="{{ route('courses.unsubscription',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-danger" style="margin: 10px;"> @lang('core.unsubscribe') </a>

                                        @else
                                          <span class="alert alert-warning " >{{__('core.invalid_quiz_count')}} </span>
                                        @endif
                                    @else
                                        <a href="{{ route('courses.subscription',['site' => $site->alias,'course' => $course->alias]) }}" class="default-btn"><i class='bx bx-paper-plane icon-arrow before'></i><span class="label">@lang('core.newsletter_submit') </span><i class="bx bx-paper-plane icon-arrow after"></i></a>
                                    @endif
                                @else
                                    <a class="btn" href="{{ route('login') }}"  style="margin: 0px;color: white;background-color: #d73d3d;">@lang('core.test_now')</a>
                                    {{--@include('front.units.steps')--}}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-7">


                        <!-- show lesson without login -->
                        <div class="courses-details-image text-center">
                          @if($diff_end_in_days <= 0 && $course->date_at != null )
                              @if( $lessons->first() != null )

                                @foreach($lessons as $kay_lesson => $lesson)

                                  @if($lesson->video != null)

                                      <label class="lesson_titel">{{ __('words.lesson_video')}} : @if(str_contains($lesson->title, 'دورة')){{$lesson->title}}@else دورة {{$lesson->title}} @endif  </label>

                                      <iframe src="{{$lesson->video}}"  poster="{{ url($course->logo_path) }}" width="100%" height="100%"></iframe>

                                  @endif
                                @endforeach
                              @endif
                          @endif
                        </div>



                        <div class="courses-details-desc">

                            @if($diff_end_in_days <= 0 && $course->date_at != null && $lessons->first() != null )

                                @foreach($lessons as $kay_lesson => $lesson)
                                    @if($lesson->sound != null)
                                    <label class="lesson_titel">{{ __('words.lesson_sound')}} : @if(str_contains($lesson->title, 'دورة')){{$lesson->title}}@else دورة {{$lesson->title}} @endif  </label>
                                          @if ( strpos($lesson->sound, 'google') ) {{-- google path --}}
                                                @if (strpos($lesson->sound, 'google.com/uc?id='))
                                                  @php $value= explode('id=',$lesson->sound);
                                                      $value_split=$value[1];
                                                  @endphp

                                                  <div class="  sound-div">
                                                    <audio controls>
                                                        @php  $url_sound="https://docs.google.com/uc?export=open&id=$value_split";@endphp
                                                        <source src="https://docs.google.com/uc?export=open&id={{$value_split}}">
                                                    </audio>
                                                  </div>
                                                @else

                                                  @php
                                                    $lesson->sound = str_replace('/file/d/','/uc?id=',$lesson->sound);
                                                    $lesson->sound = str_replace('/view?usp=sharing','&export',$lesson->sound);
                                                  @endphp

                                                    <div class="  sound-div">
                                                      <audio controls>
                                                          <source src="{{$lesson->sound}}">
                                                      </audio>
                                                    </div>

                                                @endif
                                          @else    {{-- real path --}}
                                              <div class="  sound-div" >
                                                <audio controls>
                                                    <source src="{{ asset('storage/app/public/'.$lesson->sound) }}">
                                                </audio>
                                              </div>
                                          @endif
                                      @endif
                                @endforeach

                                @if(file_exists('storage/app/public/'.$lessons->first()->description) == true)
                                    {!!  file_get_contents('storage/app/public/'.$lessons->first()->description) !!}
                                @endif

                          @endif


                          @if(file_exists('storage/app/public/'.$course->description) == true)
                              {!!  file_get_contents('storage/app/public/'.$course->description) !!}
                          @endif
                          @if($pdf != null)
                            <a href="{{ $pdf }}/view" target="_blank" download><label class="lesson_titel">{{ __('words.lesson_pdf')}} : {{$pdfname != null ? $pdfname->value  : ''}}  &nbsp; &nbsp; {{__('words.douwnlod')}} </label></a>
                            <iframe src="{{ $pdf }}/preview" width="100%" height="500px"></iframe>
                          @endif

                        </div>



                            <img src="{{ url($course->logo_path) }}" alt="{{ $course->name }}"  >


                    </div>




                    <div class="col-lg-5">
                        <div class="courses-sidebar-information">
                            <ul>
                                <li>
                                    <span><i class='bx bx-group'></i> {{__('words.Students_Enrolled')}}:</span>
                                    {{ $course->subscribers()->count() }}
                                </li>
                                <li>
                                    <span><i class='bx bx-video-recording'></i> {{__('words.Video')}}:</span>
                                    {{  $course->lessons()->count() }}
                                </li>
                                {{--<li>
                                    <span><i class='bx bx-time'></i> {{__('words.Duration')}}:</span>
                                    {{ $course->lessons()->count()*3 }} {{__('words.Hours')}}
                                </li>--}}
                                <li>

                                    <span><i class='bx bx-calendar'></i>  {{__('words.created_in')}}: </span>
                                    @if($course->date_at != null)
                                        @if( $diff_end_in_days == 0 || $diff_in_days == 0)
                                              {{__('words.live_course')}}
                                        @elseif($diff_end_in_days < 0)
                                            {{__('words.start_course')}}
                                        @elseif($diff_in_days > 0)
                                          {{$course->getDateDay($course->date_at)}} {{-- {{$course->getDateHijri($course->date_at)}} هـ  <br/> --}}{{$course->date_at}} م
                                        @endif
                                    @else
                                        {{__('words.not_have_date')}}
                                    @endif
                                </li>
                                <li>
                                    <span><i class='bx bx-time'></i> {{__('words.duration')}}:</span>
                                    @if($course->duration == 1){{__('words.one_day')}} @elseif( $course->duration == 2 )  {{__('words.two_day')}}@elseif( $course->duration < 10  ) {{$course->duration }}{{ __('words.days')}} @else {{$course->duration }}{{ __('words.day')}} @endif
                                </li>

                            </ul>
                        </div>

                        <div class="courses-details-desc courses-sidebar-syllabus  p-0 text-center">

                            <h3 class="text-center">{{__('words.Courses_Video')}}</h3>

                            @if (Auth::guard('web')->user())
                                    @if (Auth::guard('web')->user()->courses()->find($course->id))
                                       <div class="courses-accordion">
                                            <ul class="accordion">
                                                <li class="accordion-item">
                                                    <a class="accordion-title active" href="javascript:void(0)">
                                                        <i class='bx bx-chevron-down'></i>
                                                        {{$site->name}}
                                                    </a>

                                                    <div class="accordion-content show">
                                                        <ul class="courses-lessons">
                                                          @foreach(App\course::where('site_id',$site->id)->where('id','!=',$course->id)->where('status',1)->get() as $loopcourse)
                                                         <li class="single-lessons">
                                                             <div class="d-md-flex d-lg-flex align-items-center">
                                                                 <span class="number">{{$loop->iteration  <= 9 ? '0'.$loop->iteration   : $loop->iteration  }}.</span>

                                                                 <a href="{{ route('courses.show',['site' => $loopcourse->site->alias,'course' => $loopcourse->alias]) }}" class="lessons-title">{{$loopcourse->name}}</a>

                                                             </div>

                                                             <div class="lessons-info">

                                                               @if (Auth::guard('web')->user())
                                                                   @if (Auth::guard('web')->user()->courses()->find($loopcourse->id))
                                                                       @if(Auth::guard('web')->user()->test_results->where('course_id', $loopcourse->id )->count() >= 3 )
                                                                       @else
                                                                       <button type="submit" url="{{ route('courses.quiz',['site' => $loopcourse->site->alias,'course' => $loopcourse->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>

                                                                         {{--<a href="{{ route('courses.quiz',['site' => $loopcourse->site->alias,'course' => $loopcourse->alias]) }}" class="btn btn-success" style="margin: 10px;"> @lang('core.test_now') </a>--}}
                                                                       @endif
                                                                       @if(Auth::guard('web')->user()->test_results->where('course_id', $loopcourse->id )->count() >= 1 )
                                                                           <a class="btn btn-info" href="{{ route('certificates') }}" style="margin: 10px;">
                                                                               <i class="fas fa-certificate"></i>
                                                                               @lang('meta.title.certificates')
                                                                           </a>
                                                                       @else
                                                                         <a href="{{ route('courses.unsubscription',['site' => $loopcourse->site->alias,'course' => $loopcourse->alias]) }}" class="btn btn-danger" style="margin: 10px;"> @lang('core.unsubscribe') </a>
                                                                       @endif
                                                                   @else

                                                                       <a href="{{ route('courses.subscription',['site' => $loopcourse->site->alias,'course' => $loopcourse->alias]) }}" class="default-btn">
                                                                           <i class='bx bx-paper-plane icon-arrow before'></i>
                                                                           <span class="label">@lang('core.newsletter_submit') </span>
                                                                           <i class="bx bx-paper-plane icon-arrow after"></i>
                                                                       </a>

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
                                        <a href="{{ route('courses.subscription',['site' => $site->alias,'course' => $course->alias]) }}" class="default-btn"><i class='bx bx-paper-plane icon-arrow before'></i><span class="label">@lang('core.newsletter_submit') </span><i class="bx bx-paper-plane icon-arrow after"></i></a>
                                    @endif
                            @else
                                <p>{{__('words.ple_login_for_sub')}}</p>
                                @include('front.units.steps')
                                <div class="courses-accordion">
                                     <ul class="accordion">
                                         <li class="accordion-item">
                                             <a class="accordion-title active" href="javascript:void(0)">
                                                 <i class='bx bx-chevron-down'></i>
                                                 {{$site->name}}
                                             </a>

                                             <div class="accordion-content show">
                                                 <ul class="courses-lessons">
                                                   @foreach(App\course::where('site_id',$site->id)->where('id','!=',$course->id)->where('status',1)->get() as $loopcourse)
                                                  <li class="single-lessons">
                                                      <div class="d-md-flex d-lg-flex align-items-center">
                                                          <span class="number">{{$loop->iteration  <= 9 ? '0'.$loop->iteration   : $loop->iteration  }}.</span>

                                                          <a href="{{ route('courses.show',['site' => $loopcourse->site->alias,'course' => $loopcourse->alias]) }}" class="lessons-title">{{$loopcourse->name}}</a>

                                                      </div>

                                                      <div class="lessons-info">
                                                          <a href="{{ route('courses.subscription',['site' => $loopcourse->site->alias,'course' => $loopcourse->alias]) }}" class="default-btn">
                                                              <i class='bx bx-paper-plane icon-arrow before'></i>
                                                              <span class="label">@lang('core.newsletter_submit') </span>
                                                              <i class="bx bx-paper-plane icon-arrow after"></i>
                                                          </a>
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


                    </div>
                </div>
            </div>
        </section>
        <!-- End Courses Details Area -->

@endsection
@section('script')
<script>

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

</script>
@endsection
