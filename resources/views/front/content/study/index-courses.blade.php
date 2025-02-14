@extends('front.layout.master')


@section('content')

<div class="main-banner inner-banner" id="top">

</div>

<div class="container" style="padding-top: 40px;">

  <section class="section courses" id="courses" style="padding-top: 0px; margin-top: 0px;">
    <div class="container">

      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="section-heading" style="margin-bottom: 10px;">
            <h6>
              {{ $enrolled->faculty->title }} /
              {{ $enrolled->section?->title }} /
              {{ $enrolled->certificate->title }}
              {{-- $enrolled->enrolled_terms->first()->term->title --}}
            </h6>
            <!-- <h2>{{ __('domain.courses') }}</h2> -->
          </div>
        </div>
      </div>

      <!-- <ul class="event_filter">
        <li>
          <a class="is_active" href="#!" data-filter="*">Show All</a>
        </li>
        <li>
          <a href="#!" data-filter=".design">Webdesign</a>
        </li>
        <li>
          <a href="#!" data-filter=".development">Development</a>
        </li>
        <li>
          <a href="#!" data-filter=".wordpress">Wordpress</a>
        </li>
      </ul> -->

      <div class="row ">

        <h2 style="text-align: center;padding: 30px 0px;">الدراسة الحالية</h2>
        @foreach ($enrolled->enrolled_terms as $enrolledTerm)
          <!-- <h2 style="text-align: center;padding: 30px 0px;">{{ $enrolledTerm->term->title }}</h2> -->

          @foreach ($enrolledTerm->enrolled_term_courses as $termCourse)

              <div class="col-lg-4 col-md-6 align-self-center mb-30 event_outer col-md-6 design">
                <div class="events_item">

                  <div class="thumb">
                    <!-- <a href="#"><img src="assets/images/course-01.jpg" alt=""></a> -->
                    <span class="category">{{ $termCourse->course?->study_hours }}</span>
                    <span class="price"><h6><em>$</em>160</h6></span>
                  </div>
                  <div class="down-content">
                     <h4 style="color: #7b6ada;padding-bottom: 14px;">
                        @if ($termCourse->course)
                         <a style="color: #7b6ada;" href="{{ route('front.enrolls.courses.show', ['enrolled' => $enrolled->id, 'course' => $termCourse->course_id ]) }}">
                            {{ $termCourse->course->title }}
                         </a>
                        @endif
                     </h4>



                       @if ($termCourse->isFinished())
                         <div style="display: flex;text-align: center;border-radius: 6px;border: 1px solid #d4d2ee;padding: 7px 0px;margin-bottom: 10px;">
                            <div style=" width: 33%">{{ __('domain.degree') }}<br>{{ $termCourse->degree }}</div>
                            <div style=" width: 33%">{{ __('domain.rate') }}<br>{{ $termCourse->getRate() }}</div>

                            @if ($termCourse->isEqualRealStudy())
                              <div style=" width: 33%"><i class="fas fa-check" style="color: green;"></i><br>{{ __('domain.equivalent') }}</div>
                            @endif
                          </div>
                        @endif





                     <div class="author list-item-details d-flex">
                       <span><i class="far fa-clock details-item-icon"></i></span>
                       <span>{{ $termCourse->course?->study_hours }} {{ __('domain.study_hour') }}</span>
                     </div>
                     <div class="author list-item-details d-flex">
                       <span><i class="fas fa-film details-item-icon"></i></span>
                       <span>{{ $termCourse->course?->count_lessons }} {{ __('domain.lesson') }}</span>
                     </div>
                     <div class="author list-item-details d-flex">
                       <span><i class="fas fa-tasks details-item-icon"></i></span>
                       <span>{{ $termCourse->course?->count_tests }} {{ __('domain.test') }}</span>
                     </div>

                  </div>
                </div>
              </div>
            
          @endforeach
        @endforeach
      </div>


    </div>
  </section>

</div>


@endsection
