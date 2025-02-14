
<div class="container">
    <div class="description text-center">

      @include('front.include.global_alert')

      {{--
      <div class="col-12" style="text-align: center; padding: 15px 0px;">
        @include('front.include.support_videos.get_course_certificate')
      </div>
      --}}

      <!-- term sirtficate -->
{{--        @if($term)--}}
{{--            @isset($term->term_results)--}}
{{--                @if ($term->term_results->rate > 0)--}}
{{--                    <div class="row text-center prim-color-border" style="font-size: 20px;font-weight: bold;padding-top: 15px;box-shadow: 1px 6px 20px #0000001f;border-radius: 12px;padding-bottom: 12px;margin-top: 15px;">--}}
{{--                <div class="col-12">{{$term->name }}</div>--}}
{{--                <div class="col-lg-3 text-center" style="color: #22a45b;"> {{ __('trans.rating')}} : {{ __('trans.rate.'.$term->user_site_rate) }}</div>--}}


{{--                <div class="col-lg-4 text-center">--}}
{{--                <span style="font-size: 18px;font-weight: normal;">{{ __('trans.diploma_cirt') }}</span>--}}
{{--                <a data-href="{{ route('site-certificate-show', ['id' => $term->id, 'type' => 'jpg' ]) }}"--}}
{{--                  class="download_image btn but-default">--}}
{{--                  <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;{{ __('trans.image') }}&nbsp;</i>--}}
{{--                </a>--}}
{{--                <a data-href="{{ route('site-certificate-show', ['id' => $term->id, 'type' => 'pdf' ]) }}"--}}
{{--                  class="download_image btn but-default">--}}
{{--                  <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;Pdf&nbsp;</i>--}}
{{--                </a>--}}
{{--                </div>--}}

{{--                <div class="col-lg-5 text-center">--}}
{{--                <span style="font-size: 18px;font-weight: normal;">{{ __('trans.download_result_details') }}</dpan>--}}
{{--                <a data-href="{{ route('download-site-courses-certificate-show', ['id' => $term->id, 'type' => 'jpg' ]) }}"--}}
{{--                  class="download_image btn but-default">--}}
{{--                  <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;{{ __('trans.image') }}&nbsp;</i>--}}
{{--                </a>--}}
{{--                <a data-href="{{ route('download-site-courses-certificate-show', ['id' => $term->id, 'type' => 'pdf' ]) }}"--}}
{{--                  class="download_image btn but-default">--}}
{{--                  <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;Pdf&nbsp;</i>--}}
{{--                </a>--}}
{{--                </div>--}}

{{--                </div>--}}
{{--                @endif--}}
{{--            @endisset--}}
{{--        @endif--}}


    </div>























    @include('front.units.notify')


    <div class="row justify-content-center" style="margin-top: 20px;">

{{--      @foreach ($results as $term)--}}

        <!-- term -->
        {{--
          <div class="col-12 card-body">
              <!-- $currentTerm->term_test_id means user tested this term else not tested -->
              @include('front.include.term_result', [
                'site_name' => $term->name,
                'term_name' => $term->title,
                'openTermTestToUser' => $term->openTermTestToUser,
                'userResultsOfTerm' => $term->userResultsOfTerm,
                'userHasTrays' => $term->userHasTrays,

                'user_tested_term' => ($term->term_test_id ? true : false),
                'term_rate' => ($term->term_test_id ? $term->rate : ''),
                'term_degree' => ($term->term_test_id ? $term->degree : ''),

                'show_certificate' => $term->userFinalTestOfTerm?->user_successed,
                'term_test_id' => $term->term_test_id,
                'term_id' => $term->id,
              ])
        </div>
      --}}


            @foreach ($courses as $course)

              <!-- courses -->
              <div class="col-11 card prim-color-border" style="margin: 10px;border-radius: 13px;">
                <!-- <div class="card-header">
                  <div>

                  </div>
                </div> -->
                <div class="row card-body">
                  <div class="col-lg-6 col-sm-12 text-left">
                    <div>
                      <h4 class="sec-color"> {{$loop->iteration}} @php $serial = $loop->iteration; @endphp
{{--                        <a href="{{ route('courses.show',['site' => $course->site_alias,'course' => $course->course_alias]) }}" class="sec-color" style="text-decoration: underline;" >{{$course->course_title}}</a>--}}
                        <a href="#" class="sec-color" style="text-decoration: underline;" >{{$course->course->translations->name}}</a>
                      </h4>
                    </div>

                    <div style="padding-top: 15px;"><span> {{ __('field.rate') }} : </span>
                        <span class="@if($course->rate > 0 ) success-rate @else failed-rate @endif">
                            {{ $course->id ? __('trans.rate.'.$course->rate) : '' }}
                        </span>
                    </div><!-- $course->id means user tested this course else not tested -->
                    <div style="padding-top: 15px;"><span> {{ __('field.degree') }} : </span><span>{{ $course->degree ? ($course->id ? round($course->degree,2) : '') : '' }}</span></div>

                    @if($course->id) <!-- user tested this course -->
                          @if($course->rate > 0)

                              <!-- download buttons -->
                              <div style="display: flex;text-align: right;padding-top: 7px;">
                                  &nbsp;{{ __('trans.download') }}&nbsp; <div class="loading_div_{{$course->id.'-'.$course->term_id}}" style="padding: 0px 4px;margin: 0px 4px;"></div>
                                  <a data-href="{{ route('download-certificate', ['id' => $course->id.'-'.$course->term_id, 'type' => 'jpg']) }}" data-id="{{ $course->id.'-'.$course->term_id }}"
                                    class="download_image btn but-default">
                                    <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;{{ __('trans.image') }}&nbsp;</i>
                                  </a>
                                  <a data-href="{{ route('download-certificate', ['id' => $course->id.'-'.$course->term_id, 'type' => 'pdf']) }}" data-id="{{ $course->id.'-'.$course->term_id }}"
                                    class="download_image btn but-default">
                                    <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;Pdf&nbsp;</i>
                                  </a>
                              </div>

                              @if($course->userHasEjazaCertificate)
                                <a data-href="{{ route('download-ejaza-certificate-show', ['id' => $course->id.'-'.$course->term_id, 'type' => 'jpg']) }}"
                                  class="download_image btn but-default">
                                  <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp; الاجازة صورة&nbsp;</i>
                                </a>
                                <a data-href="{{ route('download-ejaza-certificate-show', ['id' => $course->id.'-'.$course->term_id, 'type' => 'pdf']) }}"
                                  class="download_image btn but-default">
                                  <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp; الاجازة بى دى اف&nbsp;</i>
                                </a>
                              @endif
                          @endif



                          @if($course->id)
                            @if($course->faildInEjazaVisualTest)
                              @if($course->visualTestResult && $course->visualTestResult->comment)
                                <span style="color: red;">ملاحظات الشيخ : </span><br>
                                <span style="color: red;">{{ $course->visualTestResult->comment }}</span>
                              @else
                                <span style="color: red;">(إعادة تسجيل المتن)<br>
                                  استمع الشيخ لتسجيل المتن ، وطلب استماعكم لقراءته هو للمتن من الموقع وتصحيح القراءة ثم إعادة التسجيل والرفع
                                </span>
                              @endif
                            @endif
                          @endif

                          <br>

                          <!-- trays  -->
{{--                          @if(Auth::guard('web')->user()->courseTestsCount($course->course_id, app()->getlocale()) < $course->trays )--}}
{{--                              <!-- <button type="submit" url="{{ route('courses.quiz',['site' => $course->site_alias,'course' => $course->course_alias]) }}" class="btn btn-success v_q_alert" style="margin: 5px 0px;">@lang('core.test_REPETITON')</button> -->--}}
{{--                              <a class="btn btn-danger" href="{{ route('courses.show',['site' => $course->site_alias,'course' => $course->course_alias]) }}" class="lessons-title">@lang('core.test_REPETITON')</a>--}}
{{--                          @else--}}
{{--                              <div class="alert alert-warning">{{__('core.invalid_quiz_count')}}</div>--}}
{{--                          @endif--}}


                    @else <!-- user not tested this course -->
                          @if ( isExamOpened($course->exam_at) )
                              <a class="btn btn-danger" href="{{ route('courses.show',['site' => $course->site_alias,'course' => $course->course_alias]) }}" class="lessons-title">@lang('core.test_now')</a>
                          @else
                              @if($course->date_at)
                                <div style="color: red;border: 1px solid;padding: 2px 17px;border-radius: 7px;">  {{$course->date_at}}  </div>
                              @endif
                          @endif
                    @endif
                  </div>


{{--                  <div class="col-lg-6 col-sm-12 text-center ">--}}
{{--                    <!-- privouse tests same course  -->--}}
{{--                    @include('front.include.prev_results_same_course', ['prevResultsSameCourse' => $course->previousTestsSameCourse])--}}
{{--                  </div>--}}



                </div>
                <!-- <div class="card-footer text-muted">
                  2 days ago
                </div> -->
              </div>

            @endforeach
{{--      @endforeach--}}
    </div>






      <div id="div-download-img" style="width: 1px;height: 1px; position: absolute;z-index: -10;" >a</div>

      <div style="text-align: center;font-weight: bold;color: #058c14;padding: 25px;">
         - {{ __('trans.to_improve_points')}}
         <br>
         - {{ __('trans.tray_to_finish_diplomas')}}
      </div>


    </div>













    {{--
    <script>
      $('.download_image').click(function(){
        document.getElementById('div-download-img').innerHTML = '';
        var url = $(this).attr('data-href') ;

          $.ajax({
            url: url,
            type: "GET",
            data:{},
            success: function(result){
                // console.log(result);
                $("#div-download-img").append(result.data);
                document.getElementById('div-download-img').innerHTML = '';
            },error:function(error){
                console.log(error);
            }
        });
      });
    </script>
    --}}
