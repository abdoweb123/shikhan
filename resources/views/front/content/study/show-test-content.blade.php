<style >
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


<div class="container-fluid">
  <div class="row" style="margin: 10px;">

    <div class="col-lg-12">
      <div class="section-heading">
        <h6></h6>
        <h2>{{ $test->title }}</h2>
        <h3>{{ $test->teacher?->title}}<h3>
      </div>
    </div>
  </div>
</div>






  <!-- run timer -->
  <div class="container clock-sticky">
    <div class="row justify-content-center clock-div" style="display: flex;" id="clockdiv">

        <div class="col-md-3 clock-text">{{ __('domain.remain_time') }}</div>
        <div style="display: none;">
          <span class="days"></span>
          <div class="smalltext">Days - </div>
        </div>
        <div class="clock-unit" style="display: none;"> <!--  -->
          <span class="hours"></span>
          <div class="smalltext">ساعة</div>
        </div>

        <div class="col-md-3 clock-unit">
          <div class="minutes"></div>
          <div class="smalltext">{{ __('domain.minute') }}</div>
        </div>
        <div class="col-md-1 colck-seprate">:</div>
        <div class="col-md-3 clock-unit">
            <div class="seconds"></div>
            <div class="smalltext">{{ __('domain.second') }}</div>
          </div>


        <div class="progress" style="width: 70%;margin: 10px 0px;border: 1px solid #8ee38e;visibility: collapse;">
          <div class="progress-bar progress-bar-striped bg-success" id="prog_exam_time" role="progressbar" style="width: 50%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

    </div>
  </div>


  <!--  test -->
  <div class="container" style="padding-top: 30px;">
    <div class="row">
        <div class="text-left col-md-12">

              <form id="frm_test"
                  method="post"
                  action="{{ route('courses.tests.testResult', [
                    'course' => $course->id,
                    'test' => $test->id
                  ]) }}"
              >

                @csrf
                <input type="hidden" id="v" name="v" value="v">

                <div class="row">
                    @foreach ($test->questions as $question)
                        <input type='hidden' name="questions[]" value="{{$question->id}}">
                        <div class="col-md-6" style="padding-bottom: 25px;">
                            <div class="card bg-light" style="border: 1px solid #ead9a8;box-shadow: 0 3px 20px rgba(0, 0, 0, 0.15);padding: 5px 5px;border-radius: 14px;height: 100%;">
                                <div class="card-body">
                                    <h4 class="card-title text-dark" style="font-size: 17px;line-height: 2;"> {{$loop->iteration}} - {{ $question->title }} {!! $question->isRequired() ? '<span style="color: red;">*</span>' : '' !!}</h4>
                                    <div class="{{ $errors->has('answers.'.$question->id) ? 'inputs-has-error' : '' }}" style="margin-right: 25px;">
                                        @if ($question->isDropList())

                                            <select class="form-control selectpicker {{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" data-style="btn btn-link" name="answers[{{ $question->id }}]">
                                                  <option value="" disabled {{ old('answers.'.$question->id) == null ? 'selected': '' }}> {{ __('general.select') }} </option>
                                                @foreach($question->answers as $answer)
                                                    <option value="{{ $answer->id }}"  {{ old('answers.'.$question->id) == $answer->id  ? 'selected': '' }}> {{ $answer->title }} </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
                <div class="row" style="padding: 50px 100px 70px 100px;">
                  <!-- <button type="submit" class="btn btn-primary btn-lg btn-block"
                  onclick="displayLeaveAlert = false;this.disabled=true;this.innerHTML='جار الإرسال';" > @lang('general.send') </button> -->
                  <!-- this.form.submit(); -->
                  <button type="submit" class="btn btn-primary btn-lg btn-block" > @lang('general.send') </button>
                </div>

            </form>

        </div>
    </div>
  </div>





<script src="{{ asset('/assets/front/vendor/jquery/jquery.min.js') }}"></script>


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





  var quizFullTime = "{{ (isset($test->time_details) && isset($test->time_details['testDuration'])) ? $test->time_details['testDuration'] : 0 }}";
  var quizRemainTime = "{{ (isset($test->time_details) && isset($test->time_details['testRemainTime'])) ? $test->time_details['testRemainTime'] : 0 }}";
  var quizElapsedTime = "{{ (isset($test->time_details) && isset($test->time_details['testElapsedTime'])) ? $test->time_details['testElapsedTime'] : 0 }}";

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
        sendTest();
        // document.getElementById("frm_test").submit(); // submit here will not fire onsubmit event
        // $("frm_test").children('input[type="submit"]').click();
        // $("frm_test").find(':submit').click();
      }
    }

    updateClock();
    var timeinterval = setInterval(updateClock, 1000);
  }


  // var deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
  var deadline = new Date(Date.parse(new Date()) + quizRemainTime * 1000);
  initializeClock('clockdiv', deadline);


  // send test after time finished
  function sendTest()
  {
    var type=$('#frm_test').attr('method');
    var url=$('#frm_test').attr('action');
    var data=$('#frm_test').serialize();

      gatAnyData('div_lesson_content','div_lesson_content_error',type,url,data);
  }


  frm_test.addEventListener("submit", (e) => {
    e.preventDefault();



    var type=$('#frm_test').attr('method');
    var url=$('#frm_test').attr('action');
    var data=$('#frm_test').serialize();

      gatAnyData('div_lesson_content','div_lesson_content_error',type,url,data);
  });










</script>
