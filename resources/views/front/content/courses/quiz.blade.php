@extends('front.layouts.the-index')
@section('content')

<style >
  .form-group {
    display: flex;
  }
  input.form-check-input {
      margin: 4px -10px 0 0;
  }
  .form-group label {
      width: 89% !important;
      padding: 10px 0;
      font-size: revert;
      color: #a97f51;
      font-weight: 600;
  }
  i.fa.fa-whatsapp {
      border-radius: 50%;
  }
  .clock-sticky {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    z-index: 999999;
  }
  .clock-div{
    background-color: white;
    border-radius: 10px;
    box-shadow: 0px 3px 10px #0000002e;padding: 13px 0px;
  }
  .clock-text {
    padding: 16px 7px;
    font-size: 16px;
    font-weight: bold;
    color: gray;
  }
  .clock-total {
    border-radius: 8%;
    text-align: center;
    padding: 10px;
    width: 60px;
    margin: 0px 7px;
    height: 60px;
    font-weight: bold;
    border: 1px solid #f7a3a3;
    box-shadow: 1px 2px 8px #0511402b;
    color: #e66565;
  }
  .clock-unit {
    border-radius: 8%;
    text-align: center;
    padding: 10px;
    width: 60px;
    margin: 0px 7px;
    height: 60px;
    font-weight: bold;
    border: 1px solid #41cc64;
    box-shadow: 1px 2px 8px #0511402b;
    color: #2f9344;;
  }
  .colck-seprate{
    padding: 8px 4px;
    font-size: 23px;
    font-weight: bold;
    color: #34954d;
  }
</style>

<section class="bg-img bg-overlay-2by5 inner_banner" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <!-- Hero Content -->
                <div class="hero-content text-center row">
                    <div class="col-4" style="padding-top: 25px;">
                        <img src="{{ url($course->logo_path) }}" alt="{{ $course->name }}" class="bg-light img-raised img-fluid" style="width: 200px;border-radius: 18px;"> <!-- class="p-3 bg-light img-raised rounded-circle img-fluid" -->
                    </div>

                    {{--
                    <div class="col-8" style="padding: 16px;">
                        <h1 class="sec-color" style="font-size: 18px;font-weight: bold;">{{ $course->name }}</h1>
                        <h4 style="text-decoration: underline;">أتعهد بأنى</h4>
                        <h6 style="font-weight: bold;">{{__('words.q_alert1')}}</h6>
                        <h6 style="font-weight: bold;">{{__('words.q_alert2')}}</h6>
                        <h6 style="font-weight: bold;">{{__('words.q_alert3')}}</h6>
                    </div>
                    --}}

                    @if(isset($userTrays))
                      <div class="col-8" style="padding: 16px;">
                          @if($userTrays == 0)
                            <div id="course_swear_text" class="inner_page_title" style="font-size: 16px;text-align: center;">
                              <h4 style="text-decoration: underline;">{{ __('trans.i_swear') }}</h4>
                              @include('front.include.course_swear_text_01')
                            </div>
                          @else
                            <div id="course_swear_text" class="inner_page_title" style="font-size: 16px;text-align: center;">
                              <h4 style="text-decoration: underline;">{{ __('trans.i_swear') }}</h4>
                              @include('front.include.course_swear_text_02')
                            </div>
                          @endif
                      </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>




@include('front.include.global_alert')

@include('front.include.page_alert')


@if(! $userHasTrays)
  <div class="alert alert-danger text-center p-4">
    {{ __('core.invalid_quiz_count') }}
  </div>
@elseif( $course->exam_at == null)
  <div class="alert alert-danger text-center p-4">
    {{__('core.question_notfound1')}}
  </div>
@elseif( $course->exam_approved != 1)
  <div class="alert alert-danger text-center p-4">
    {{__('core.question_notfound')}}<br/>
    {{date('Y/m/d',strtotime($course->exam_at))}}  {{ isset($course) ? $course->getExamAtDay() : '' }} {{ isset($course) ? $course->getExamAtHijri() : ''}} هـ
  </div>
@elseif(!ourAuth() && ! $course->isExamOpened())
    <div class="alert alert-danger text-center p-4">
      {{__('core.question_notfound')}}<br/>
      {{date('Y/m/d',strtotime($course->exam_at))}}  {{ isset($course) ? $course->getExamAtDay() : '' }} {{ isset($course) ? $course->getExamAtHijri() : ''}} هـ
    </div>
@elseif(! $quiz['userHasRemainTime'])
  <div class="text-left col-md-12">
    <div class="alert alert-danger text-center" role="alert">
      {{ __('trans.you_entered_quiz_before')}}
      @if(isset($enteredQuize)) {{ $enteredQuize->start_time }} @endif  <br>
      {{ __('trans.time_over')}}<br>
      {{ __('trans.so_its_failed')}}<br>
      {{ __('trans.wait')}} {{ $minutesToOpenTest }} {{ __('trans.minute')}}<br>
    </div>
  </div>
@elseif( $questions->count() == 0)
  <div class="alert alert-danger text-center p-4">
    {{__('core.question_notfound1')}}
  </div>
@else
  <!-- run timer -->
  <div class="container clock-sticky">
      <div class="row justify-content-center clock-div" id="clockdiv">

                {{--
                <div class="clock-text">إجمالى الوقت</div>
                <div class="clock-total">{{ $quizFullTime }} {{ $questionPeriodUnitTitle }}</div>
                --}}

                <!-- stop timer -->

                <div class="clock-text">{{ __('trans.remain_time') }}</div>
                <div style="visibility: collapse;">
                  <span class="days"></span>
                  <div class="smalltext">Days - </div>
                </div>
                <div class="clock-unit" style="visibility: collapse;"> <!--  -->
                  <span class="hours"></span>
                  <div class="smalltext">ساعة</div>
                </div>
                <div class="clock-unit">
                  <div class="minutes"></div>
                  <div class="smalltext">{{ __('trans.minute') }}</div>
                </div>
                <div class="colck-seprate">:</div>
                <div class=" clock-unit">
                  <div class="seconds"></div>
                  <div class="smalltext">{{ __('trans.second') }}</div>
                </div>



              <div class="progress" style="width: 70%;margin: 10px 0px;border: 1px solid #8ee38e;visibility: collapse;">
                <div class="progress-bar progress-bar-striped bg-success" id="prog_exam_time" role="progressbar" style="width: 50%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
          </div>
  </div>


  <!--  open quiz -->
  <div class="container" style="padding-top: 30px;">
    <div class="row">
        <div class="text-left col-md-12">
            @if (isset($current) && $current == 'term')
            <form id="frm_quiz" class="" action="{{ route('courses.quiz_term',['site' => $site->slug,'term' => $course->id]) }}" method="post">
            @else
            <!-- course -->
            <form id="frm_quiz" class="" action="{{ route('courses.quiz',['site' => $site->slug,'course' => $course->id]) }}" method="post">
            @endif
                            @csrf
                            <input type="hidden" id="v" name="v" value="v">
                            {{--<div class="tim-container">--}}
                            <div class="row">
                                @foreach ($questions as $question)

                                    <div class="col-md-6" style="padding-bottom: 25px;">
                                        <div class="card bg-light" style="border: 1px solid #ead9a8;box-shadow: 0 3px 20px rgba(0, 0, 0, 0.15);padding: 5px 5px;border-radius: 14px;height: 100%;">
                                            <div class="card-body" style="@if (LaravelLocalization::getCurrentLocaleDirection() =='rtl') text-align: right; @endif ">
                                                <h4 class="card-title text-dark" style="font-size: 17px;line-height: 2;"> {{$loop->iteration}} - {{ $question->name }} </h4>
                                                <div class="{{ $errors->has('answers.'.$question->id) ? 'inputs-has-error' : '' }}" style="margin-right: 25px;">
                                                    @if ($question->type == 'true_false')
                                                        <div class="form-check form-check-radio">
                                                            <label class="form-check-label">
                                                                <input style="margin-right: -20px;margin-left: -20px;" class="form-check-input {{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" type="radio" {{ $question->required ? 'required' : '' }} name="answers[{{ $question->id }}]" {{ old('answers.'.$question->id) == '1' ? 'checked' : '' }} value="1">
                                                                @lang('core.trueq')
                                                                <span class="circle">
                                                                    <span class="check"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-radio">
                                                            <label class="form-check-label">
                                                                <input style="margin-right: -20px;margin-left: -20px;" class="form-check-input {{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" type="radio" {{ $question->required ? 'required' : '' }} name="answers[{{ $question->id }}]" {{ old('answers.'.$question->id) == '0' ? 'checked' : '' }} value="0">
                                                                @lang('core.falseq')
                                                                <span class="circle">
                                                                    <span class="check"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @elseif ($question->type == 'range')
                                                        <input class="{{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" type="range" {{ $question->required ? 'required' : '' }} name="answers[{{ $question->id }}]" min="{{ $question->options['min'] }}" max="{{ $question->options['max'] }}" value="{{ old('answers.'.$question->id) ?? $question->options['min'] }}">
                                                    @elseif (count($question->correct_answer) > 1)
                                                        @foreach ($answers as $answer)
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    {{--<input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $answer->id }}" {{ old('answers.'.$question->id) == $answer->id || $loop->first ? 'checked': '' }}>--}}
                                                                    <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $answer->id }}" {{ old('answers.'.$question->id) == $answer->id ? 'checked': '' }}>
                                                                    <span style="padding: 21px;">{{ $answer->name }}</span>
                                                                    <span class="form-check-sign">
                                                                        <span class="check"></span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        @php $answers = $question->answers()->where('status',1)->orderBy('sequence','ASC')->select('id')->get(); @endphp
                                                        <select class="form-control selectpicker {{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" data-style="btn btn-link" name="answers[{{ $question->id }}]">
                                                              <option value=""   disabled {{ old('answers.'.$question->id) == null ? 'selected': '' }}> {{ __('words.p_select_option') }} </option>
                                                            @foreach ($answers as $answer)
                                                                <option value="{{ $answer->id }}"  {{ old('answers.'.$question->id) == $answer->id  ? 'selected': '' }}> {{ $answer->name }} </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                    {{--@if ($errors->has('answers.'.$question->id))
                                                        <span class="invalid-feedback" role="alert">
                                                            {{ $errors->first('answers.'.$question->id) }}
                                                        </span>
                                                    @endif--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group col-md-6">
                                    <label for="phone"> <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                                {{ __('trans.set_whatsup') }}</label>
                                    <input type="text" class="form-control"  value="{{old('phone')}}"id="phone" name="phone" aria-describedby="phone" placeholder="{{ __('trans.set_whatsup') }}">
                                </div>
                            </div>
                            <div class="row" style="padding: 50px 100px 70px 100px;">
                                <!-- <button type="submit" class="btn btn-primary btn-lg btn-block"> @lang('core.send') </button> -->
                                <button type="submit" class="btn btn-primary btn-lg btn-block" onclick="displayLeaveAlert = false;this.disabled=true;this.innerHTML='{{ __('trans.send_now')}}';this.form.submit();" > @lang('core.send') </button>
                            </div>
                        </form>
        </div>
    </div>
  </div>
@endif





@php
  $openQuiz = 0;
  if( $userHasTrays &&
      $course->isExamOpened() &&
      $quiz['userHasRemainTime'] &&
      $questions->count() > 0 &&
      $course->exam_approved == 1 &&
      $course->exam_at != null &&
      date('Y-m-d H:i:s',strtotime($course->exam_at)) <= date('Y-m-d H:i:s')
      ){ $openQuiz = 1;}
 @endphp


@if (ourAuth())
  @php $openQuiz = 1; @endphp
@endif




@endsection




@if( $openQuiz )

  <!-- forQuizeTime -->
    @section('script')
      <script>

          // if user click submite (onlcik) & on submit form from timer dont dispaly leav alert
          var displayLeaveAlert = true;
          window.addEventListener("beforeunload", function (e) {
              if (displayLeaveAlert){
                var confirmationMessage = 'It looks like you have been editing something. '
                                        + 'If you leave before saving, your changes will be lost.';
                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
              }
          });
          // -----------------------------------------------------------------------------------




          // force browser to reload page if user click back or forward but
          window.addEventListener( "pageshow", function ( event ) {
            var historyTraversal = event.persisted ||
                                   ( typeof window.performance != "undefined" &&
                                        window.performance.navigation.type === 2 );
            if ( historyTraversal ) {
              // Handle page restore.
              window.location.reload();
            }
          });
          // -------------------------------------------------------------------------------------





        var quizFullTime = "{{ (isset($quiz) && isset($quiz['quizFullTime'])) ? $quiz['quizFullTime'] : 0 }}";
        var quizRemainTime = "{{ (isset($quiz) && isset($quiz['quizRemainTime'])) ? $quiz['quizRemainTime'] : 0 }}";
        var quizElapsedTime = "{{ (isset($quiz) && isset($quiz['quizElapsedTime'])) ? $quiz['quizElapsedTime'] : 0 }}";

        var progress_bar = document.getElementById("prog_exam_time");
        var quizFullTimeInSeconds = quizFullTime * 60;

        var elapsedTimePerc = quizElapsedTime / quizFullTimeInSeconds;
        var remainTimePerc = 100 - elapsedTimePerc;
        var progress_bar_value =  parseInt(elapsedTimePerc * 100); // start progress bar from this point
        progress_bar.setAttribute("style",  "width: " + progress_bar_value  + "%" );


        function getTimeRemaining(endtime) {
          var t = Date.parse(endtime) - Date.parse(new Date());
          var seconds = Math.floor((t / 1000) % 60);
          var minutes = Math.floor((t / 1000 / 60) ); // (t / 1000 / 60) % 60
          var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
          var days = Math.floor(t / (1000 * 60 * 60 * 24));

          return {
            'total': t,
            'days': days,
            'hours': hours,
            'minutes': minutes,
            'seconds': seconds
          };
        }

        function initializeClock(id, endtime) {
          var clock = document.getElementById(id);
          var daysSpan = clock.querySelector('.days');
          var hoursSpan = clock.querySelector('.hours');
          var minutesSpan = clock.querySelector('.minutes');
          var secondsSpan = clock.querySelector('.seconds');



          function updateClock() {
            var t = getTimeRemaining(endtime);



            daysSpan.innerHTML = t.days;
            hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
            minutesSpan.innerHTML = ('0' + t.minutes).slice(-3);
            secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);


            progress_bar_value = progress_bar_value + parseFloat( remainTimePerc/quizRemainTime );
            progress_bar.setAttribute("style",  "width: " + progress_bar_value + "%" );


            if (t.total <= 0) {
              clearInterval(timeinterval);
              displayLeaveAlert = false;
              document.getElementById("v").value = "n";
              document.getElementById("frm_quiz").submit();
            }
          }

          updateClock();
          var timeinterval = setInterval(updateClock, 1000);
        }


        // var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
        var deadline = new Date(Date.parse(new Date()) + quizRemainTime * 1000);
        initializeClock('clockdiv', deadline);






      </script>
    @endsection


@endif
