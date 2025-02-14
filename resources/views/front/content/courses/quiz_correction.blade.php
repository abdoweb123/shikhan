
@extends('front.layouts.the-index')

@section('head')

<link rel="preconnect" href="https://www.youtube.com">

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

<section class="courses-details-area pt-100 pb-70">
  <div class="container">

    <div class="courses-details-header">
        <div class="row align-items-center">

            <div class="col-lg-12">
                <div class="courses-title">{{ $course->name }}</div>
                <div class="courses-meta">
                    <ul>
                        <li>
                            <i class='bx bx-folder-open'></i>
                            {{$site->name}} - {{ $course->name }}
                        </li>
                        <li>
                          <form action="{{ route('front.course_tests_visual_correction', ['site' => request()->site, 'course' => request()->course ]) }}" method="get">
                            <input type="text" name="name" value="{{ request()->query('name') ?? '' }}">
                            <input type="submit" value="بحث">
                          </form>
                        </li>
                    </ul>
                </div>
            </div>




              <div class="col-lg-12" style="text-align: center;padding-top: 5px;">
                @foreach($studentsTests  as $test)
                <div class="row" style="text-align: center;border: 1px solid #d5d5d5;border-radius: 10px;margin: 20px 0px;">
                  @php
                    $rateBackColor = '#eaeaea';
                    if($test->rate == '1'){$rateBackColor = '#85dd85';}
                    if($test->rate == '2'){$rateBackColor = '#f49898';}
                  @endphp
                  <div class="col-12" style="padding: 15px;border-bottom: 1px solid #e1dddd;text-align: right;border-radius: 10px;background-color: {{$rateBackColor}};">
                    <div class="row">
                      <div class="col-4">
                        الطالب : <span style="font-weight: bold;">{{ $test->member->name }}</span>
                      </div>
                      <div class="col-1">
                        <span style="font-weight: bold;">
                          @if ($test->rate == '1') {{ __('words.success') }} @endif
                          @if ($test->rate == '2') {{ __('words.faild') }} @endif
                        </span>
                      </div>
                      <div class="col-1">
                        <div class="d-flex justify-content-center" padding-bottom="25px;">
                          <div id="loading" class="" role="status">
                            <span class="sr-only">Loading...</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-6">
                        <div id="err_div_{{$test->id}}"></div>
                        <form class="container" action="{{ route('front.course_tests_visual_correct') }}" onsubmit="submitForm(event,this,'err_div_{{$test->id}}')" method="post" style="display: flex;">
                            @csrf
                            <select id="degree" name="rate">
                              <option value="" {{ !$test->rate ? 'selected' : '' }}>اختر</option>
                              <option value="1" {{ $test->rate == '1'  ? 'selected' : '' }}>{{ __('words.success') }}</option>
                              <option value="2" {{ $test->rate == '2'  ? 'selected' : '' }}>{{ __('words.faild') }}</option>
                            </select>
                            <select id="type_id" name="type_id">
                              @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ $test->type_id == $type->id  ? 'selected' : '' }}>{{ $type->title }}</option>
                              @endforeach
                            </select>

                            <textarea name="comment" maxlength="500" style="width: 100%" placeholder="اكتب تعليق للطالب">{{$test->comment}}</textarea>

                            <input type="hidden" name="user_id" value="{{ $test->user_id }}">
                            <input type="hidden" name="site_id" value="{{ $test->site_id }}">
                            <input type="hidden" name="course_id" value="{{ $test->course_id }}">

                            <button class="btn" style="margin: 0px;color: white;background-color: #2566ac;">حفظ</button>
                        </form>
                      </div>
                    </div>
                  </div>
                    @foreach($test->members_tests_visual_uploads ?? [] as $testUpload)
                      <div class="col-lg-4" style="padding:5px 0px;border-bottom: 1px solid #dfdfdf;">{{ $testUpload->title }}</div>
                      <div class="col-lg-4" style="border-bottom: 1px solid #dfdfdf;">

                        @if(
                              $testUpload->type == 'audio/mpeg' ||
                              $testUpload->type == 'audio/wav' ||
                              $testUpload->type == 'application/octet-stream' ||
                              $testUpload->type == 'audio/x-m4a'  ||
                              $testUpload->type == 'audio/ogg' ||
                              $testUpload->type == 'audio/mp3'
                            )
                          @include('components.media.sound', ['value' => $testUpload->file ])
                        @endif
                        @if(
                              $testUpload->type == 'video/mp4' ||
                              $testUpload->type == 'video/mov' ||
                              $testUpload->type == 'video/ogg' ||
                              $testUpload->type == 'video/ogx' ||
                              $testUpload->type == 'video/oga' ||
                              $testUpload->type == 'video/ogv' ||
                              $testUpload->type == 'video/webm'
                            )

                          @include('components.media.video', ['item' => $testUpload ])
                          <!-- , 'auto_play' => 0 -->
                        @endif

                      </div>
                      <div class="col-lg-2" style="padding:5px 0px;border-bottom: 1px solid #dfdfdf;">{{ $testUpload->created_at }}</div>
                    @endforeach
                </div>
                @endforeach
              </div>




        </div>
    </div>
    {{ $studentsTests->Links() }}
  </div>
</section>




@endsection


@section('script')

<script type="text/javascript">

function submitForm(e,me,err_div)
{

  e.preventDefault();
  $("#loading").addClass('spinner-border');


  $.ajax({
    url: "{{ route('front.course_tests_visual_correct') }}",
    type:"POST",
    data:$(me).serialize(),
      success:function(response){
        // $('#successMsg').show();
        // console.log(response);
        $('#'+err_div).text('تم');
        $("#loading").removeClass('spinner-border');
      },
      error: function(response) {
        // console.log(response.responseJSON.errors.rate[0]);
        $('#'+err_div).text('برجاء التاكد من اختيار الدرجة');
        // $('#'+err_div).text(response.responseJSON.errors.rate[0]);
        $("#loading").removeClass('spinner-border');
      },
    });
}



{{--
// $('#form_correct').on('submit',function(e){
//     e.preventDefault();
//
//     $.ajax({
//       url: "{{ route('front.course_tests_visual_correct') }}",
//       type:"POST",
//       data:$(this).serialize(),
//         success:function(response){
//           // $('#successMsg').show();
//           console.log(response);
//         },
//         error: function(response) {
//           console.log(response.responseJSON);
//           // $('#nameErrorMsg').text(response.responseJSON.errors.name);
//         },
//       });
// });
--}}

  </script>
@endsection
