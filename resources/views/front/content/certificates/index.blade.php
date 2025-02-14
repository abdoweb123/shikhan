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


<div class="container">
    <div class="description text-center">
        {{-- <p> {{ $site->description }} </p> --}}
    </div>
    @include('front.units.notify')

      <div style="text-align: center;padding-top: 15px;padding-bottom: 10px;">
        <a style="background-color: #e3b282;padding: 10px 30px;font-size: 21px;border-radius: 8px;" href="#diploma_certificate"> <i class="fas fa-arrow-down"></i>{{ __('trans.download_diploma_cirt') }}</a>
      </div>

      <!-- /////////  sites with degrees(overall degree) with user courses and courses that user didn't subscribe in  ////////// -->
      <div class="row justify-content-center">
          <table class="table table-striped mt-3">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">{{__('words.course')}} / {{__('words.Category')}}</th>
                <th scope="col">{{__('words.rate')}}</th>
                <th scope="col">{{__('words.degree')}}</th>
                <th scope="col">{{__('words.douwnlod')}}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <!-- diplome -->
              @foreach ($result as $site)
                  <tr style="background-color: #623c16;color: white;font-size: 20px;font-weight: bold;">
                    <th scope="row">{{$loop->iteration}}</th>
                    <td>{{ $site->site_title }}</td>
                    <td></td>
                    <td>مجموع : {{ $site->active_courses_full_degree }} / {{ $site->site_degree }}</td>
                    <td></td>
                  </tr>

                      <!-- 1- user tests and download cirtficate -->
                      @foreach ($site as $course)
                      <tr>
                        <td scope="row">{{$loop->iteration}} @php $serial = $loop->iteration; @endphp</td>
                        <td>{{ $course->course_title }}</td>
                        <td>{{ __('trans.rate.'.$course->rate) }}</td>
                        <td>{{ round($course->degree,2) }}</td>
                        <td>

                          {{--
                          <!-- old pdf dpwnload -->
                          <a href="{{ route('certificates-show', $course->id.'-'.$course->site_id ) }}" class="btn btn-primary">
                            <i class="fa fa-download"></i>{{ __('core.press_to_douwnlod') }}
                          </a>
                          --}}

                          <!-- new pdf, image download -->
                          &nbsp;تحميل&nbsp;
                          <a data-href="{{ route('download-certificate', ['id' => $course->id.'-'.$course->site_id, 'type' => 'jpg']) }}"
                            class="download_image btn btn-outline-success">
                            <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;صورة&nbsp;</i>
                          </a>
                          <a href="{{ route('download-certificate', ['id' => $course->id.'-'.$course->site_id, 'type' => 'pdf']) }}"
                            class="btn btn-outline-success" style="">
                            <i class="fa fa-file-pdf" style="font-size: 13px;padding-left: 1px;padding-right: 1px;color: black;">&nbsp;بى دى اف&nbsp;</i>
                          </a>


                          {{--
                          <!-- test just for our emails -->
                          @if (Auth::guard('web')->user() && Auth::guard('web')->id() == 5651)
                            <i class="fa fa-download">&nbsp;تحميل&nbsp;</i>
                            <a href="javascript:;" data-href="{{ route('download-certificate', ['id' => $course->id.'-'.$course->site_id, 'type' => 'jpg']) }}"
                              class="download_image btn btn-outline-success">
                              <i class="fa fa-images" style="padding-left: 5px;padding-right: 5px;">&nbsp;صورة&nbsp;</i>
                            </a>
                            <a href="{{ route('download-certificate', ['id' => $course->id.'-'.$course->site_id, 'type' => 'pdf']) }}"
                              class="btn btn-outline-success" style="">
                              <i class="far fa-file-pdf" style="padding-left: 5px;padding-right: 5px;">&nbsp;بى دى اف&nbsp;</i>
                            </a>
                          @endif

                          @if (Auth::guard('web')->user() && Auth::guard('web')->id() == 5671)
                            <a data-href="{{ route('download-certificate', ['id' => $course->id.'-'.$course->site_id, 'type' => 'jpg']) }}"
                              class="download_image btn btn-primary" style="color:white;">
                              <i class="fa fa-download"></i><i class="fa fa-images" style="padding-left: 5px;padding-right: 8px;"></i>
                            </a>
                            <a href="{{ route('download-certificate', ['id' => $course->id.'-'.$course->site_id, 'type' => 'pdf']) }}"
                              class="btn btn-primary" style="color:white;">
                              <i class="fa fa-download"></i><i class="far fa-file-pdf" style="padding-left: 5px;padding-right: 8px;"></i>
                            </a>
                          @endif
                          --}}




                        </td>
                      </tr>
                      @endforeach


                        <!-- 2- user courses not tested in -->
                        @php
                          $globalService = new \App\Services\globalService();
                          $coursesNotTested = $globalService->getCoursesNotTestedForUser( \Auth::guard('web')->user(), ['site_id' => $site->site_id , 'all_courses' => true]);
                          $coursesNotTested = $coursesNotTested->sortBy('date_at');
                        @endphp
                        @foreach ($coursesNotTested as $courseNot)

                          <tr>
                            <td scope="row">@php $serial = $serial+1; @endphp {{ $serial }}</td>

                            <td>{{ $courseNot->title }}</td>
                            <td></td>
                            <td>@if( $courseNot->isExamOpened() ) 0 @endif</td>
                            <td>
                               @if ( $courseNot->isExamOpened() )
                                   @if( Auth::guard('web')->user()->courses()->where('course_id', $courseNot->course_id )->first() )
                                     <a class="btn btn-danger subscribe" href="{{ route('courses.show',['site' => $courseNot->site_alias,'course' => $courseNot->course_alias]) }}" class="lessons-title">@lang('core.test_now')</a>
                                   @else
                                     <a class="btn btn-danger subscribe" href="{{ route('courses.show',['site' => $courseNot->site_alias,'course' => $courseNot->course_alias]) }}" class="lessons-title">@lang('core.test_now')</a>
                                   @endif
                               @else
                                   <span style="color: red;border: 1px solid;padding: 2px 17px;border-radius: 7px;">  {{$courseNot->date_at}}  </span>
                               @endif
                             </td>
                          </tr>
                        @endforeach




              @endforeach
            </tbody>
          </table>
      </div>
      <!-- ////////////////////// -->




      <br><br>



      <h5><a id="diploma_certificate"></a>.</h5>

      <!-- /////////  sites with degrees(overall degree) with user courses and courses that user didn't subscribe in  ////////// -->
      <div class="row justify-content-center">
          <table class="table table-striped mt-3">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">{{__('words.Category')}}</th>
                <th scope="col">مجموع</th>
                <th scope="col">{{__('words.douwnlod')}}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <!-- diplome -->

            @foreach ($result as $site)

                  <tr style="background-color: #623c16;color: white;font-size: 20px;font-weight: bold;">
                    <th scope="row">{{$loop->iteration}}</th>
                    <td>{{ $site->site_title }}</td>
                    <td>{{ $site->active_courses_full_degree }} / {{ $site->site_degree }}</td>
                    <td>
                      {{-- if(auth()->id() == 5972) --}}  <!-- 5671 loqaa@hotmail.com  //  5651 tarik // 5663 bahmmam  // sabah 5972  // samia  5668-->


                          @if( $site->siteNotCompleted )
                              <!-- قريبا -->
                          @else
                            @if ($site->userFinishedSite === true && $site->userSucess)
                                @if ($site->lessThan70)
                                  <span style="font-size: 14px;font-weight: normal;">&nbsp;تحميل&nbsp;</span>
                                  <a data-href="{{ route('site-certificate-show', ['id' => $site->site_id, 'type' => 'jpg' ]) }}"
                                    class="download_image btn btn-outline-success">
                                    <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;صورة&nbsp;</i>
                                  </a>
                                  <a href="{{ route('site-certificate-show', ['id' => $site->site_id, 'type' => 'pdf' ]) }}" style="color: white;font-weight: bold;"
                                    class="btn btn-outline-success">
                                    <i class="fa fa-file-pdf" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;بى دى اف&nbsp;</i>
                                  </a>

                                  <span style="font-size: 14px;font-weight: normal;"> تحميل كشف الدرجات</dpan>
                                  <a data-href="{{ route('download-site-courses-certificate-show', ['id' => $site->site_id, 'type' => 'jpg' ]) }}"
                                    class="download_image btn btn-outline-success">
                                    <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;صورة&nbsp;</i>
                                  </a>

                                  <a href="{{ route('download-site-courses-certificate-show', ['id' => $site->site_id, 'type' => 'pdf' ]) }}" style="color: white;font-weight: bold;"
                                    class="btn btn-outline-success">
                                    <i class="fa fa-file-pdf" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;بى دى اف&nbsp;</i>
                                  </a>
                                @endif
                            @endif
                          @endif


                      {{-- @endif --}}
                    </td>
                  </tr>

            @endforeach

                @php $all_sites_full_degree = $result->sum('active_courses_full_degree'); @endphp
                @php $sum_site_degree = $result->sum('site_degree'); @endphp
              <tr style="background-color: #25731a;color: white;font-size: 20px;font-weight: bold;">
                <th scope="row"></th>
                <td>اجمالي</td>
                <td>{{ $all_sites_full_degree }} / {{ $sum_site_degree }}</td>
                <td>
                  @if(auth()->id() == 5671)  <!-- id for loqaa@hotmail.com  شهادة دبلوم مخصص-->
                    <a href="{{ route('site-certificate-specialist-show',['id' => 1 ])}} " >download specialist</a>
                  @endif
                </td>
              </tr>

            </tbody>
          </table>
      </div>
      <!-- ////////////////////// -->

      <br>
      <div style="text-align: right;text-align: right;font-weight: bold;color: #058c14;">
         - {{ __('trans.to_improve_points') }}
         <br>
         - {{ __('trans.tray_to_finish_diplomas') }}
      </div>
      <br><br><br>





        <!-- courses old -->
        {{--
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
        --}}



          <div id="div-download-img" style="width: 1px;height: 1px; position: absolute;z-index: -10;" >a</div>



    </div>
@endsection







@section('script')
<x-subscripe-unsubscripe-ajax-js/>

<script type='text/javascript'>
    $('.download_image').click(function(){
        document.getElementById('div-download-img').innerHTML = '';
        var url = $(this).attr('data-href') ;
        $.ajax({
            url: url,
            type: "GET",
            data:{},
            success: function(result){
                $("#div-download-img").append(result.data);
                document.getElementById('div-download-img').innerHTML = '';
            },error:function(error){
                console.log(error);
            }
        });
    });
</script>
@endsection
