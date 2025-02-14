@extends('front.layouts.the-index')
@section('head')

<style>
    .single-courses-item .courses-image {
        height: 230px;
        overflow: hidden;
    }
    span.sub-dip a {
        color: white;
    }
    span.sub-dip:hover a {
        color: #218838;
    }
    span.sub-dip:hover  {
        background-color: #ffffff;
            box-shadow: 1px 2px 9px 1px #1e7e34;
        border-color: #1e7e34;
    }
    .link-from-here {
      color: #218838;
      font-size: x-large;
    }
    .link-from-here:hover {
      color: #fdfdfd;
      font-size: x-large;
      text-shadow: 0px -2px 4px #28a745;
  }
  .show_div{ display: block; float: left;}
  .hide_div{ display: none}
</style>

@endsection
@section('content')

@if ( Session::has('global_message'))
<div class="alert alert-success" style="text-align: center;" role="alert">
  {!! Session::get('global_message') !!}
</div>
@endif

<!-- diplome page -->
<section class="bg-img bg-overlay-2by5 inner_banner" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100">
        <div class="row  align-items-center">
            <div class="col-12" >
                <!-- Hero Content -->
                <div class="hero-content text-center row">
                    {{--
                    <div class="col-md-12">
                        <img src="{{ url($site->ImageDetailsPath) }}" alt="{{ $site->name }}" class="bg-light img-raised img-fluid" style="width: 100px;border-radius: 18px;"> <!-- class="p-3 bg-light img-raised rounded-circle img-fluid" -->
                    </div>
                    --}}
                    <div class="col-md-12 justify-content-center" style="text-align: center;display: flex;">
                        <img src="{{ url($site->ImageDetailsPath) }}" alt="{{ $site->name }}" class="bg-light img-raised img-fluid" style="width: 100px;border-radius: 18px;"> <!-- class="p-3 bg-light img-raised rounded-circle img-fluid" -->
                        <h1 style="color: #23524f;font-size: 27px;padding: 15px;">{{ $site->name }}</h1>
                    </div>

                    <div class="col-md-12 justify-content-center" style="text-align: center;display: flex;padding-top: 5px;">
                      <div><span style="margin: 0px 0px 0px 23px;display: block;font-size: 17px;font-weight: bold;color: #fff3cf;padding: 12px 32px;background-color: white;border-radius: 4px;color: brown;border: 1px solid #b5b5b5;">{{ $siteCoursesCount }} {{ __('trans.course') }}</span></div>
                      <div><span style="margin: 0px 0px 0px 23px;display: block;font-size: 17px;font-weight: bold;color: #fff3cf;padding: 12px 32px;background-color: white;border-radius: 4px;color: brown;border: 1px solid #b5b5b5;"><span class="prim-color">{{ $subs_count }} {{ __('trans.subscribers') }}</span></span></div>
                      @if (Auth::guard('web')->user())
                          @include('front.include.subscribe_in_site', [ 'siteToSubscribe' => $site, 'reloadpage' => true] )
                      @else
                          @include('front.units.steps')
                      @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="profile-content">

      @include('front.include.global_alert')

      <div class="container page-title-content bread-crumb">
          <ul>
              <li><a style="color: gray" href="{{ route('home') }}">{{ __('trans.home') }}</a></li> /
              <li><a style="color: gray" href="{{ route('diplomas.index') }}">{{ __('trans.diplomas') }}</a></li> /
              <li style="color: gray">{{ $site->name }}</li>
              <!-- <li></li> -->
          </ul>
      </div>


        <div class="description text-center" style="padding: 10px;font-weight: bold;">
                <!-- it will be if no courses in this site then display image as it is else put it in modal -->
                  {{--
                  @if($result->count() > 0)
                    <div class="col-12">
                      <img src="{{ url($site->image_details_path) }}" alt="{{ $site->name }}" data-toggle="modal" data-target="#exampleModal"
                          class="bg-light img-raised img-fluid" style="width: 150px;border-radius: 18px;cursor: pointer;">
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <img src="{{ url($site->image_details_path) }}" alt="{{ $site->name }}">
                          </div>
                          <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                          </div> -->
                        </div>
                      </div>
                    </div>
                  @endif
                  --}}

                  {{--
                    لابد من انهاء
                  @if (Auth::guard('web')->user())
                      @include('front.include.note_cant_print_cirt', ['site' => $site, 'note' => $note])
                  @endif
                  --}}
        </div>

        <br>
        @include('front.units.notify')


        <div class="container" style="padding: 0px 20px 0px 20px;">
            <div id="result" class=" alert alert-danger d-none" >

            </div>
            <div class="row">
                @foreach ($result as $course)
                    <!-- Single Popular Course -->
                     <div class="col-lg-4 col-md-6">

                        <div class="single-courses-item mb-30 owl-item">

                            <div class="courses-image">
                                @php $userCoursesResultes = $userCoursesResulte->where('course_id', $course->id)->first(); @endphp
                                @if($userCoursesResultes)
                                  <!-- ribbone -->
                                  <div class="corner-ribbon bottom-left sticky orange"
                                      style="{{ $userCoursesResultes->rate > 0 ? 'background-color: #0ea921 !important; background-image: none;' :  'background-color: red !important; background-image: none;'  }}"
                                  >{{ $userCoursesResultes->max_degree }} %</div>
                                @endif

                                <a href="{{ route('courses.show',['site' => $site->slug,'course' => $course->alias]) }}" title="{{ $course->name }}" class="d-block">
                                    <img src="{{ url($course->ImageDetailsPath) }}" alt="{{ $course->name }}">
                                </a>
                            </div>


                            <div class="courses-content">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="course-author d-flex align-items-center">
                                        <img class="shadow img-owl"  src="{{ asset('assets/img/logo2.png') }}" alt="{{ $course->name }}" >
                                        <span>@lang('core.app_name')</span>
                                    </div>
                                </div>
                                <h3>
                                  <a href="{{ route('courses.show',['site' => $site->slug,'course' => $course->alias]) }}" title="{{ $course->name }}" class="d-inline-block">
                                  <span style="background-color: #8c5159;border-radius: 30px;padding: 0px 10px;margin: 0px 1px;color: #cfeade;">{{ $loop->iteration }}</span>
                                  {{ $course->name }}
                                  </a>
                                </h3>
                            </div>

                            <div class="courses-box-footer">
                                <ul>
                                    {{--
                                    <li class="students-number text-center">
                                        <i class='bx bx-user'></i>مشتركين <span class="prim-color">{{ $subs_count }} طالب</span>
                                    </li>
                                    --}}


                                    @if (! $course->isExamOpened())
                                      <li class="courses-lesson text-center">
                                        <span class="text-center"><i class='bx bx-time'></i>عدد مرات البث
                                              <span class="prim-color">@if($course->duration == 1){{__('words.one_day')}} @elseif( $course->duration == 2 )  {{__('words.two_day')}}@elseif( $course->duration < 10  ) {{$course->duration }}{{ __('words.days')}} @else {{$course->duration }}{{ __('words.day')}} @endif</span>
                                        </span>
                                      </li>
                                    @else
                                      <li class="courses-lesson text-center">
                                        <span class="text-center"><i class='bx bx-time'></i> {{__('trans.duration')}}
                                              <span class="prim-color">{{ $course->video_duration }} </span>
                                        </span>
                                      </li>
                                    @endif


                                    <li class="courses-price text-center">
                                      @if ($course->isExamOpened())
                                        <div class="prim-color">
                                          <div class="prim-back-color-darker" style="margin: auto; width: 13px;height: 13px;color: #fbfbfb;border-radius: 50px;"></div>{{ __('trans.active') }}
                                        </div>
                                      @endif
                                    </li>

                                    <div class="text-center" style="padding: 17px 19px 0px 0px;">
                                        <span class="sec-color">{{ $course->zoom_day_status }} </span>
                                    </div>












                                    {{--
                                    <li class="courses-price">

                                         @if (Auth::guard('web')->user())
                                                <a href="#" att-URL="{{ route('courses.ajax.unsubscription',['site' => $site->slug,'course' => $course->alias]) }}" class="btn btn-danger unsubscribe @if (!Auth::guard('web')->user()->courses()->find($course->id)) d-none @endif" att-id="{{$course->id}}" id="unsubscribe_{{$course->id}}" style="font-size: 13px;"> @lang('core.unsubscribe') </a>
                                                <a href="#" att-URL="{{ route('courses.ajax.subscription',['site' => $site->slug,'course' => $course->alias]) }}" class="btn btn-success subscribe @if (Auth::guard('web')->user()->courses()->find($course->id)) d-none @endif " att-id="{{$course->id}}" id="subscribe_{{$course->id}}" > @lang('core.newsletter_submit') </a>
                                        @endif
                                    </li>
                                    --}}
                                </ul>
                            </div>
                        </div>



                    </div>

                @endforeach
            </div>

        </div>

        <div class="container" style="padding: 0px 20px 0px 20px;text-align: right;">
          <div class="col-12" style="padding: 20px;"> {!! $site->description !!} </div>
        </div>



          @if(isset($successedUsersInEachCountry) && $successedUsersInEachCountry->isNotEmpty())
          <div class="container">
            <h3 style="text-align: initial;padding-top: 30px;">
              @if($site->new_flag == 0)
                {{ __('trans.diplome_successed_users') }} <span style="font-weight: bold;"> {{ $site->name }}</span><span class="label label-default"> {{ __('trans.until_today') }}  {{ $successedUsersInEachCountryDate }}</span>
                <span style="font-weight: bold;">( {{ $successedUsersInEachCountry->sum('count_success') }}  {{ __('trans.student') }} )</span>
              @else
                اسماء الطلاب الذين اجتازوا الدورات المقدمة في <span style="font-weight: bold;"> {{ $site->name }}</span><span class="label label-default"> حتى يوم  {{ $successedUsersInEachCountryDate }}</span>
              @endif
            </h3><br>
            <div class="row">
              @foreach($successedUsersInEachCountry as $country)
              <div style="padding: 5px;">
                <a href="{{ route('successed_users_country_site' , ['site' => $site->slug , 'country' => $country->nicename ]) }}" class="btn btn-primary" style="color: black;background-color: white;border-radius: 50px;border: 1px solid #c6c6c6;">
                  {{ $country->flag }} {{ $country->nicename }}
                  <span class="badge badge-light" style="background-color: #79be1b;border-radius: 50px;">{{ $country->count_success }}</span>
                  <span class="sr-only">unread messages</span>
                </a>
              </div>
              @endforeach
            </div>
          </div>
          @endif



    </div>
@endsection


@section('script')
<x-subscripe-in-site/>
@include('front.include.google_schema.course_items', ['google_schema_course_items' => $google_schema_site_items ])
{{--<!-- <x-subscripe-unsubscripe-ajax-js/> -->--}}
@endsection
