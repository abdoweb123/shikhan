@extends('front.layouts.new')
@section('head')
<style>
.row.justify-content-center {
    overflow-x: scroll;
}
th, td {
    text-align: center;
}
</style>
@endsection
@section('content')

<!-- ##### Hero Area Start ##### -->
<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 350px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12"style=" margin-top: 130px;">
                <!-- Hero Content -->
                <div class="hero-content text-center row">
                    <div class="col-4">
                        @if(!empty(Auth::guard('web')->user()->avatar))
                            <div class="avatar">
                                {{--dd(url(Auth::guard('web')->user()->avatar))--}}
                                <img src="{{ url(Auth::guard('web')->user()->avatar_path) }}" class="bg-light img-raised img-fluid" style="width: 80px;border-radius: 18px;" alt="{{ Auth::guard('web')->user()->name }}">
                            </div>
                        @else
                            <div class="p-5"></div>
                        @endif
                    </div>
                    <div class="col-8">
                        <h1 style="color: white;">@lang('meta.title.certificates')</h1>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- ##### Hero Area End ##### -->

    <div class="container">
        <div class="description text-center">
            {{-- <p> {{ $site->description }} </p> --}}
        </div>
        @include('front.units.notify')



<!-- /////////  sites with degrees(overall degree) with courses that user didn't subscribe in  ////////// -->
    {{--
        <div class="row justify-content-center">
            <table class="table table-striped mt-3">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">{{__('words.Category')}}</th>
                  <th scope="col">{{__('words.my_ended_courses')}}</th>
                  <!-- <th scope="col">{{__('words.sum_points')}}</th> -->
                  <th scope="col">{{__('words.courses_count')}}</th>
                  <th scope="col">{{__('words.active_courses_count')}}</th>
                  <!-- <th scope="col">{{__('words.degree')}}</th> -->
                  <th scope="col"></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($site_degree as $site)
                    <tr>
                      <th scope="row">{{$loop->iteration}}</th>
                      <td>{{ $site->site_title }}</td>
                      <td>{{ $site->count_user_courses }}</td>
                      <td>{{ $site->all_courses }}</td>
                      <td>{{ $site->active_courses }}</td>
                      <!-- <td>{{ round($site->site_degree,2) }}</td> -->
                      <td>

                        @if($site->coursesUserDoesntSubscripeIn->isNotEmpty())
                          <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#site_{{$site->site_id}}">دورات لم يتم الإشتراك بها</button>
                        @endif

                      </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
        </div>


        <!-- modals -->
        @foreach ($site_degree as $site)
            @if($site->coursesUserDoesntSubscripeIn->isNotEmpty())
              <!-- Modal -->
              <div id="site_{{$site->site_id}}" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                      <div style="text-align: right;">
                        <h6 class="modal-title" style="text-align: right;"> دورات لم يتم الإشتراك بها فى</h6>
                        <h4 style="text-align: right;">{{ $site->site_title }}</h4>
                      </div>
                    </div>

                      <div class="modal-body">
                        @foreach ($site->coursesUserDoesntSubscripeIn as $course)
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
                        @endforeach
                      </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">اغلاق</button>
                    </div>
                  </div>
                </div>
              </div>
            @endif
        @endforeach
--}}

<!-- ////////////////////// -->








        <!-- courses -->
        <div class="row justify-content-center">
            <table class="table table-striped mt-3">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">{{__('words.Category')}}</th>
                  <th scope="col">{{__('words.course')}}</th>
                  <th scope="col">{{__('words.rate')}}</th>
                  <th scope="col">{{__('words.degree')}}</th>
                  <th scope="col">{{__('words.douwnlod')}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($result as $certificate)
                    <tr>
                      <th scope="row">{{$loop->iteration}}</th>
                      <td>{{ $certificate->title }}</td>
                      <td>{{ $certificate->course_title }}</td>
                      <td>{{ __('trans.rate.'.$certificate->rate) }}</td>
                      <td>{{ round($certificate->degree,2) }}</td>
                      <td>
                        <a href="{{ route('certificates-show', $certificate->id.'-'.$certificate->site_id ) }}" class="btn btn-primary">
                          <i class="fa fa-download"></i>{{ __('core.press_to_douwnlod') }}
                        </a>
                      </td>
                    </tr>
                @endforeach
              </tbody>
            </table>
        </div>

    </div>
@endsection




@section('script')
<x-subscripe-unsubscripe-ajax-js/>
@endsection
