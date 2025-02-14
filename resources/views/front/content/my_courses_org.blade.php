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
      background: #e8bb8f !important;
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
</style>

@endsection
@section('content')
<!-- ##### Hero Area Start ##### -->
<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 300px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">.
                <!-- Hero Content -->
                <div class="hero-content text-center">
                  <div class="p-5"></div>
                  <div class="name mt-5">
                      <h3 class="title" style="color: white;"> @lang('meta.alias.my_courses') </h3>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <div class="profile-content">
      <div class="container" >
        <!-- Start Courses Details Area -->
        <section class="courses-details-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="courses-details-desc">
                            <div class="courses-accordion">
                                <ul class="accordion">
                                  @foreach ($diplomas as $diploma)
                                    <li class="accordion-item">
                                        <a class="accordion-title item-dip active" href="javascript:void(0)">
                                            <i class='bx bx-chevron-down'></i>
                                             {{$loop->iteration }}: {{$diploma->title}}
                                        </a>

                                        <div class="accordion-content show">
                                            <ul class="courses-lessons">
                                              <?php $loop_cours=0; ?>
                                              @foreach ($result as $course)
                                                @if($course->site_id == $diploma->id)
                                                <?php $loop_cours++; ?>
                                                <li class="single-lessons">
                                                    <div class="d-md-flex d-lg-flex align-items-center">
                                                        <span class="number">{{$loop_cours <= 9 ? '0'.$loop_cours  : $loop_cours }}.</span>
                                                        <a href="{{ route('courses.show',['site' => $diploma->alias,'course' => $course->alias]) }}" class="lessons-title">{{$course->name}}</a>
                                                    </div>
                                                    <div class="lessons-info">                                                      
                                                      @if(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() < 1 )
                                                        <button type="submit" url="{{ route('courses.quiz',['site' => $diploma->alias,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_now')</button>
                                                        <a href="{{ route('courses.unsubscription',['site' => $diploma->alias,'course' => $course->alias]) }}" class="btn btn-danger" style="margin: 10px;"> @lang('core.unsubscribe') </a>
                                                      @elseif(Auth::guard('web')->user()->test_results->where('course_id', $course->id )->count() < 2 )
                                                        <button type="submit" url="{{ route('courses.quiz',['site' => $diploma->alias,'course' => $course->alias]) }}" class="btn btn-success v_q_alert" style="margin: 10px;">@lang('core.test_REPETITON')</button>
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
                                                    </div>
                                                </li>
                                                @endif
                                              @endforeach

                                            </ul>
                                        </div>
                                    </li>
                                  @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Courses Details Area -->
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
@endsection
