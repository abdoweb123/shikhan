
@if (Auth::guard('web')->user())
  @if (Auth::guard('web')->id() == 5972)

  @php
    if(! $finished_courses_count){
      $pers = 0;
    } else {
      $pers = floor( ($finished_courses_count / $courses_count) * 100  );
    }
   @endphp

  <span style="color: #b75461;"> نسبة التقدم : {{ $pers }} %</span>
  <div class="progress" style="height: 5px;">
    <div class="progress-bar progress-bar-striped" role="progressbar" aria-label="Success striped example"
        style="width: {{$pers}}%; background-color: #73ca6b;" aria-valuenow="{{$pers}}" aria-valuemin="0" aria-valuemax="100"></div>
  </div>

  @endif
@endif
