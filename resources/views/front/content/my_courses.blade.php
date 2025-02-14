@extends('front.layouts.new')
@section('head')
<style>
  @media only screen and (max-width: 767px){
      .hero-area {
        height: 260px !important;
      }
  }
  .courses-title {
      text-align: center;
      font-size: x-large;
      font-weight: 900;
  }
  .course-serial {
      color: black;
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
  .courses-details-desc .courses-accordion .accordion .accordion-item .accordion-title:hover, .courses-details-desc .courses-accordion .accordion .accordion-item .accordion-title.active {
      background-color: #623c16;
      color: #ffffff;
      border: #964d04 !important;
      box-shadow: 1px 0px 7px 2px #7c6044;
  }
  li.accordion-item {
      background: #f0f8ff !important;
      color: white !important;
  }
  .accordion-content.show {
      background: aliceblue;
  }
  .btn-danger {
      color: #f70707 ;
      background-color: #ffffff00 ;
      border-color: #ffffff00;
  }
  .courses_done {
    color: white;background-color: #17c811;;padding: 5px;border-radius: 5px;
  }
  .courses_done_label{
    color: #17c811;border-radius: 5px;padding: 53px 0px 0px 0px;font-size: 30px;font-weight: bold;
  }
  .courses_less {
    color: white;background-color: #f04f4f;;padding: 5px;border-radius: 5px;margin-right: 10px;
  }
  .courses_less_label {
    color: #f04f4f;border-radius: 5px;margin-right: 10px;padding: 53px 0px 0px 0px;font-size: 30px;font-weight: bold;
  }
</style>

@endsection
@section('content')

<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 300px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">.
                <!-- Hero Content -->
                <div class="hero-content text-center">
                  <div class="p-5"></div>
                  <div class="name mt-5" style="display: flex;">
                      <h4 class="title courses_done">@lang('meta.alias.my_courses')</h4>
                      <!-- <a href="#courses_done"><h4 class="title courses_done">@lang('meta.alias.my_courses')</h4></a>
                      <a href="#courses_less"><h4 class="title courses_less">  دورات لم يتم الإشتراك بها </h4></a> -->
                  </div>

                </div>
            </div>
        </div>
    </div>
</section>

<div class="profile-content">
  <div class="container" >
    <section class="courses-details-area">
      <div class="container">
        <div class="row">

          <!-- tested courses -->
          <div class="col-lg-12">
              <div class="courses-details-desc">
                  <div class="courses-accordion">
                      <ul class="accordion">
                        @foreach ($diplomas as $diploma)
                          @php
                            $notTestedCoursesCount = 0;
                            $coursesNotStartedCount = 0;
                            $totalDiplomaCourses = 0;
                          @endphp

                          <li class="accordion-item">
                            <a class="accordion-title item-dip active" href="javascript:void(0)">
                                <i class='bx bx-chevron-down'></i>
                                 {{$loop->iteration }}: {{$diploma->title}}
                            </a>
                            <div id="alert_{{ $diploma->id }}" style="display: none;background-color: #d9ac5c;border-radius: 10px;padding: 5px 45px;margin: 20px;"></div>
                            <!--    -->
                            {{--
                            <span style="font-size: 19px;padding: 10px;">
                              @if ( count($notificationsInPage) )
                                 @foreach($notificationsInPage as $notificationInPage)
                                   {!! $notificationInPage->body !!}
                                 @endforeach
                              @endif
                            </span>
                            --}}

                            <div class="accordion-content show">
                              <ul class="courses-lessons">
                                <?php $loop_cours=0; ?>

                                <!-- courses user subscrip in -->
                                @foreach ($result as $course)
                                  @foreach ($course->sites as $site)
                                    @if($site->id == $diploma->id)
                                      <?php $loop_cours++; ?>
                                      <li class="single-lessons">
                                          <div class="d-md-flex d-lg-flex align-items-center">
                                              <span class="course-serial">{{$loop_cours <= 9 ? '0'.$loop_cours  : $loop_cours }}.</span>
                                              <a href="{{ route('courses.show',['site' => $diploma->alias,'course' => $course->alias]) }}" class="lessons-title">{{$course->name}}</a>
                                          </div>
                                          <div class="lessons-info">
                                            @if ( $course->isExamOpened() )
                                                @if(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() < 1 )
                                                  {{--<button type="submit" url="{{ route('courses.quiz',['site' => $diploma->alias,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>--}}
                                                  <a href="{{ route('courses.quiz',['site' => $diploma->alias,'course' => $course->alias]) }}" class="btn btn-success" style="margin: 10px;">@lang('core.test_now')</a>
                                                  <!-- <a href="{{ route('courses.unsubscription',['site' => $diploma->alias,'course' => $course->alias]) }}" class="btn btn-danger" style="margin: 10px;"> @lang('core.unsubscribe') </a> -->
                                                  @php $notTestedCoursesCount++; @endphp
                                                @elseif(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() < 2 )
                                                  <a href="{{ route('courses.quiz',['site' => $diploma->alias,'course' => $course->alias]) }}" class="btn btn-success" style="margin: 10px;">@lang('core.test_REPETITON')</a>
                                                  <!-- <button type="submit" url="{{ route('courses.quiz',['site' => $diploma->alias,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_REPETITON')</button> -->
                                                  <a class="btn btn-info" href="{{ route('certificates') }}" style="margin: 10px;">
                                                      <i class="fas fa-certificate"></i>
                                                      @lang('meta.title.certificates')
                                                  </a>
                                                @else
                                                  <span class="alert alert-warning">{{__('core.invalid_quiz_count')}} </span>
                                                    <a class="btn btn-info" href="{{ route('certificates') }}" style="margin: 10px;">
                                                        <i class="fas fa-certificate"></i>
                                                        @lang('meta.title.certificates')
                                                    </a>
                                                @endif
                                            @else
                                                <!-- <span style="color: red;">تبدأ في  </span> -->
                                                <span style="color: red;border: 1px solid;padding: 2px 17px;border-radius: 7px;">  {{ $course->getDateDay($course->date_at) }}  {{$course->date_at}}  </span>
                                                @php $coursesNotStartedCount++; @endphp
                                            @endif
                                          </div>
                                      </li>
                                    @endif
                                  @endforeach
                                @endforeach

                                <!-- courses user not subcrup in -->
                                @foreach ($coursesUserDoesntSubscripeIn as $courseNot)
                                  @if($diploma->id == $courseNot->site_id)
                                      @php $notTestedCoursesCount++; @endphp
                                      <li class="single-lessons">
                                          <div class="d-md-flex d-lg-flex align-items-center">
                                              <span class="course-serial">{{ $loop->iteration }}.</span>
                                              <a href="{{ route('courses.show',['site' => $courseNot->site_alias,'course' => $courseNot->course_alias]) }}" class="lessons-title">{{$courseNot->course_name}}</a>
                                          </div>
                                          <div class="lessons-info">
                                             <!-- <a href="#" att-URL="{{ route('courses.ajax.unsubscription',['site' => $courseNot->site_alias,'course' => $courseNot->course_alias]) }}" class="btn btn-danger unsubscribe @if (!Auth::guard('web')->user()->courses()->find($course->course_id)) d-none @endif" att-id="{{$courseNot->course_id}}" att-name="{{$courseNot->course_id}}_{{$courseNot->site_id}}" id="unsubscribe_{{$courseNot->course_id}}_{{$courseNot->site_id}}" style="font-size: 13px;"> @lang('core.unsubscribe') </a> -->
                                             <!-- <a href="#" att-URL="{{ route('courses.ajax.subscription',['site' => $courseNot->site_alias,'course' => $courseNot->course_alias]) }}" class="btn btn-success subscribe @if (Auth::guard('web')->user()->courses()->find($courseNot->course_id)) d-none @endif " att-id="{{$courseNot->course_id}}" att-name="{{$courseNot->course_id}}_{{$courseNot->site_id}}" id="subscribe_{{$courseNot->course_id}}_{{$courseNot->site_id}}" > @lang('core.newsletter_submit') <a> -->
                                             <a href="{{route('diplomas.subscribers',$courseNot->site_alias)}}" class="default-btn">
                                               <i class='bx bx-paper-plane icon-arrow before'></i><span class="label">إشترك فى الدبلوم </span><i class="bx bx-paper-plane icon-arrow after"></i>
                                             </a>
                                          </div>
                                      </li>
                                  @endif
                                @endforeach

                                @if ( $notTestedCoursesCount <= 3 )
                                    <script>
                                        var currentAlert = document.getElementById('alert_{{ $diploma->id }}');
                                        // console.log(currentAlert);
                                        currentAlert.style.display = 'block';
                                        // console.log(currentAlert);
                                        currentAlert.innerHTML = "<li style='background-color: #d9ac5c;border-radius: 10px;'>"+
                                          "<p style='text-align:center'><span style='color:#000000'><span style='font-size:18px'>ما شاء الله تبارك الله , جهد مبارك تقومون به</span><br />" +
                                          "<p style='text-align:center'><span style='color:#000000'><span style='font-size:18px;font-weight: bold;'>{{ Auth::guard('web')->user()->name }}</span><br />" +
                                          "<span style='font-size:16px'>لم يبق لكم إلا " + "{{ $notTestedCoursesCount }}" + " دورة" + " لإنهاء الدورات الجاهزة من الدبلوم" + "</span></span></p>"
                                          // "<p style='text-align:center'><span style='color:#000000'>للانضمام لقائمة الشرف في يوم السبت 8/ 1 في الدبلوم، وللمنافسة في مهرجان الجوائز الثاني،&nbsp; نقترح عليكم سرعة إنهاء دورات الدبلوم الباقية قبل التاريخ المحدد</span></p>"+
                                          "</li>";
                                    </script>
                                @endif

                                @if ( $coursesNotStartedCount )
                                    <script>
                                        var currentAlert = document.getElementById('alert_{{ $diploma->id }}');
                                        // console.log(currentAlert);
                                        currentAlert.style.display = 'block';
                                        // console.log(currentAlert);
                                        currentAlert.innerHTML = currentAlert.innerHTML + "<li style='background-color: #d9ac5c;border-radius: 10px;'>"+
                                        "<p style='text-align:center'><span style='color:#000000'><span style='font-size:18px'>" +
                                        "<span style='font-size:16px'> - باقى " + "{{ $coursesNotStartedCount }}" +
                                        " دورة " +
                                        " ستقدم مواعيدها " +
                                        "</span></span></p>" +
                                          "</li>";
                                    </script>
                                @endif









                              </ul>
                            </div>
                          </li>
                        @endforeach

                      </ul>
                  </div>
              </div>
          </div>




          <!-- not subscriped courses -->
          {{--
          <div class="col-lg-12">
              <div id="courses_less" class="courses_less_label"> دورات لم يتم الإشتراك بها </div>
              <div class="courses-details-desc">
                  <div class="courses-accordion">
                      <ul class="accordion">

                        @foreach ($coursesUserDoesntSubscripeIn as $site)
                          @foreach ($site as $course)

                          @if($loop->first)
                          <li class="accordion-item">
                              <a class="accordion-title item-dip active" href="javascript:void(0)">
                                  <i class='bx bx-chevron-down'></i>
                                   {{$loop->iteration }}: {{$course->site_name}}
                              </a>
                              @endif

                              <div class="accordion-content show">
                                  <ul class="courses-lessons">
                                    <li class="single-lessons">
                                        <div class="d-md-flex d-lg-flex align-items-center">
                                            <span class="course-serial">{{ $loop->iteration }}.</span>
                                            <a href="{{ route('courses.show',['site' => $course->site_alias,'course' => $course->course_alias]) }}" class="lessons-title">{{$course->course_name}}</a>
                                        </div>
                                        <div class="lessons-info">
                                           <a href="#" att-URL="{{ route('courses.ajax.unsubscription',['site' => $course->site_alias,'course' => $course->course_alias]) }}" class="btn btn-danger unsubscribe @if (!Auth::guard('web')->user()->courses()->find($course->course_id)) d-none @endif" att-id="{{$course->course_id}}" id="unsubscribe_{{$course->course_id}}" style="font-size: 13px;"> @lang('core.unsubscribe') </a>
                                           <a href="#" att-URL="{{ route('courses.ajax.subscription',['site' => $course->site_alias,'course' => $course->course_alias]) }}" class="btn btn-success subscribe @if (Auth::guard('web')->user()->courses()->find($course->course_id)) d-none @endif " att-id="{{$course->course_id}}" id="subscribe_{{$course->course_id}}" > @lang('core.newsletter_submit') </a>
                                        </div>
                                    </li>
                                  </ul>
                              </div>

                          @if($loop->last)
                          </li>
                          @endif
                          @endforeach
                        @endforeach

                      </ul>
                  </div>
              </div>
          </div>
          --}}


        </div>
      </div>
    </section>
  </div>
</div>

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

<x-subscripe-unsubscripe-ajax-js/>

@endsection
