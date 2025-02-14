@extends('front.layouts.new')

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
    .col-md-12 {
        text-align: right;
        direction: rtl;
    }
    .topbar-result-count h2 {
        text-align: center;
        width: 100%;
    }
    .courses-topbar {
    margin-bottom: 0 !important;
  }
  h3 {
      text-decoration: underline;
      display: list-item;
      list-style: square;
      margin: 15px 25px !important;
      color: #eb8d80;
      text-shadow: 2px 1px 3px #4a4a4a4a;
  }
  table {
      margin: 0 2% 20px;
      width: 100%;
  }
  table, th, td {
  border: 2px solid #e2b183;
  padding: 6px;
  box-shadow: 1px 2px 5px 2px #6b4b292b;
  text-align: center;
  }
  a.link-course {
      text-decoration: underline;
      color: #5e8826;
      text-shadow: 1px 2px 3px #00000042;
      font-size: 16px;
      font-weight: bold;
  }
  th.date-th {
      width: 15%;
  }
</style>

@endsection
@section('content')


 <!-- Start Page Title Area -->
        <div class="item-bg2 jarallax" data-jarallax='{"speed": 0.3}' style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
            <div class="container">
                <div class="page-title-content">
                    <ul>
                        <li><a>{{ __('core.header_title') }}</a></li>
                    </ul>
                  <h5 style="color: white;">{{ __('core.header_content') }}</h5>
                     <br>
                     {{--
                     @if (Auth::guard('web')->user())
                        @if (Auth::guard('web')->user()->courses()->count() < App\course::where('status',1)->count())
                            @if(Auth::guard('web')->user()->email_verified_at == null )
                                <span class="btn btn-success sub-dip"><a href="#" att-URL="all" class="" att-id="all" id="subscribe_all" style="font-size: 13px;"> اشترك  في الكل </a></span>
                            @else
                                <span class="btn btn-success sub-dip"><a href="{{route('diplomas.subscribers','all')}}">اشترك  في الكل</a></span>
                            @endif
                        @endif
                    @else
                        @include('front.units.steps')
                    @endif
                    --}}
                </div>
            </div>
        </div>
        <!-- End Page Title Area -->
@include('front.content.auth.register_every_page')
        @include('front.include.global_alert')

        <!-- Start Courses Area -->
        <section class="courses-area ">
            <div class="container">
              <div class="courses-topbar">
                  <div class="row align-items-center">
                        <div class="topbar-result-count w-100  p-3 ">
                            <h2>المناهج</h2>
                        </div>
                  </div>
              </div>


              <div class="row" style="text-align: center;">
                @foreach ($sites as $item)
                  <div class="col-lg-3 col-sm-12" style="padding: 10px">
                    <a class="link-d" href="#{{$item->id}}">
                      <!-- <img src="{{ url($item->logo_path) }}" style="max-width: 50px;" alt="image"> -->
                      <h5>{{$item->name}}</h5>
                    </a>
                  </div>
                @endforeach
              </div>


                <div class="row justify-content-center">

                    @foreach ($sites as $item)
                    <div class="col-md-10">
                      <a class="link-d" href="{{route('courses.index',['site' => $item->alias])}}" id="{{$item->id}}"><h3 style="text-align: right;"> {{$item->name}} </h3></a>
                      <table>
                        <thead>
                          <tr>
                            <th>{{__('words.name_course')}}</th>
                            {{--<th class="date-th">{{__('words.created_in')}}</th>--}}
                            <th>{{__('words.time_course')}}</th>
                            {{--<th>{{__('words.description_course')}}</th>--}}
                          </tr>
                        </thead>
                        <tbody>
                          @php $courses = $item->courses()->where('status',1)->get()->sortBy('date_at', 0,false); @endphp
                          @foreach ($courses as $course)
                          <tr>
                            <td><a class="link-course" href="{{ route('courses.show',['site' => $item->alias,'course' => $course->alias]) }}">{{$course->name}}<a></td>
                              {{--
                                <td>
                                @php
                                    if($course->date_at != null){
                                      $to = \Carbon\Carbon::createFromFormat('Y-m-d', $course->date_at);
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
                                @if($course->date_at != null)
                                    @if( $diff_end_in_days == 0 || $diff_in_days == 0)
                                          {{__('words.live_course')}}
                                    @elseif($diff_end_in_days < 0)
                                        {{__('words.start_course')}}
                                    @elseif($diff_in_days > 0)
                                      {{$course->getDateDay($course->date_at)}} {{$course->date_at}} م
                                    @endif
                                @else
                                    {{__('words.not_have_date')}}
                                @endif
                              </td>
                              --}}
                            <td>
                               @if($course->duration == 1){{__('words.one_day')}} @elseif( $course->duration == 2 )  {{__('words.two_day')}}@elseif( $course->duration < 10  ) {{$course->duration }}{{ __('words.days')}} @else {{$course->duration }}{{ __('words.day')}} @endif
                            </td>
                            {{--
                            <td>
                              @if(file_exists('storage/app/public/'.$course->description) == true)
                              {!!  file_get_contents('storage/app/public/'.$course->description) !!}
                              @endif
                            </td>
                            --}}
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
        <!-- End Courses Area -->







@endsection
@section('script')
<script>
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

@endsection
