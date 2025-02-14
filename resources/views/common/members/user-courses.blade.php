@if ($detailsType == 'USER_COURSES')
  <div style="direction: rtl;font-size: 18px;font-weight: bold;"> عدد الدورات {{ $data ? count($data) : 0 }} </div><br>
  <div style="direction: rtl;font-size: 18px;font-weight: bold;"> الدرجة الإجمالية {{ $overAllDegree ? number_format((float) $overAllDegree , 2) : 0 }} </div><br>


  @foreach($data as $item)
    <div style="direction: rtl;font-size: 18px;font-weight: bold;">
      {{--<a href="{{ route('courses.show', [ 'site' => , 'course' => $item->course_alias ] ) }}" >{{ $item->site_alias . ' / ' . $item->course_alias }}</a>--}}
      <a href="{{ request()->getSchemeAndHttpHost . '/ar/' . $item->site_alias . '/' . $item->course_alias }}" >{{ $item->site_alias . ' / ' . $item->course_alias }}</a>
    </div>
    <div style="direction: rtl;font-size: 16px;">تاريخ الاختبار : {{ $item->created_at }}</div>
    <div style="direction: rtl;font-size: 16px;">التقييم : {{ $item->rate }}</div>
    <div style="direction: rtl;font-size: 16px;">الدرجة : {{ $item->max_degree }}</div>
  <hr>
  @endforeach
@endif



@if ($detailsType == 'USER_COMPARE_COURSES')
  <div style="direction: rtl;font-size: 18px;font-weight: bold;"> عدد الدورات {{ $dynamicDetails ? count($dynamicDetails) : 0 }} </div><br>
  <table class="table">
    <tr>
      <th>Site</th>
      <th>Course</th>
      <!-- <th>tests_count</th> -->
      <th>test_degree</th>
      <th>test_rate</th>
      <!-- <th>test_id</th> -->
    </tr>
    @foreach($dynamicDetails['results'] as $item)

      @php $staticRecord = $staticDetails->where('course_id', $item['course_id'])->first(); @endphp
          <tr>
            <td>{{ $item['site_id'] }} - {{ $staticRecord->site_id ?? 'error' }}</td>
            <td>{{ $item['course_id'] }} - {{ $staticRecord->course_id ?? 'error' }}</td>
            <!-- <td>{{ $item['tests_count'] }} - {{ $staticRecord->tests_count ?? 'error' }}</td> -->
            <td>{{ $item['test_degree'] }} - {{ $staticRecord->test_degree ?? 'error' }}</td>
            <td>{{ $item['test_rate'] }} - {{ $staticRecord->test_rate ?? 'error' }}</td>
            <!-- <td>{{ $item['test_id'] }} - {{ $staticRecord->test_id ?? 'error' }}</td> -->
          </tr>
    @endforeach
  </table>
@endif



@if ($detailsType == 'USER_COURSES_DOESNT_SUBSCRIPE')
  <div style="direction: rtl;font-size: 18px;font-weight: bold;"> عدد الدورات {{ $data ? count($data) : 0 }} </div><br>
  @foreach($data as $item)
    <div style="direction: rtl;font-size: 18px;font-weight: bold;">
      {{--<a href="{{ route('courses.show', [ 'site' => , 'course' => $item->course_alias ] ) }}" >{{ $item->site_alias . ' / ' . $item->course_alias }}</a>--}}
      <a href="{{ request()->getSchemeAndHttpHost . '/ar/' . $item->site_alias . '/' . $item->course_alias }}" >{{ $item->site_alias . ' / ' . $item->course_alias }}</a>
    </div>
  <hr>
  @endforeach
@endif


@if ($detailsType == 'USER_COURSES_ACTIVE_NOT_TESTED')
  <div style="direction: rtl;font-size: 18px;font-weight: bold;"> عدد الدورات {{ $data ? count($data) : 0 }} </div><br>
  @foreach($data as $item)
    <div style="direction: rtl;font-size: 18px;font-weight: bold;">
      {{--<a href="{{ route('courses.show', [ 'site' => , 'course' => $item->course_alias ] ) }}" >{{ $item->site_alias . ' / ' . $item->course_alias }}</a>--}}
      <a href="{{ request()->getSchemeAndHttpHost . '/ar/' . $item->site_alias . '/' . $item->course_alias }}" >{{ $item->site_alias . ' / ' . $item->course_alias }}</a>
    </div>
  <hr>
  @endforeach
@endif

@if ($detailsType == 'USER_SUBSCRIPTIONS')
  <div style="direction: rtl;font-size: 18px;font-weight: bold;"> عدد الدورات {{ $data ? count($data) : 0 }} </div><br>
  @foreach($data as $item)
    <div style="direction: rtl;font-size: 18px;font-weight: bold;">
      {{--<a href="{{ route('courses.show', [ 'site' => , 'course' => $item->course_alias ] ) }}" >{{ $item->site_alias . ' / ' . $item->course_alias }}</a>--}}
      <a href="{{ request()->getSchemeAndHttpHost . '/ar/' . $item->site_alias . '/' . $item->course_alias }}" >{{ $item->site_alias . ' / ' . $item->course_alias }}</a>
    </div>
  <hr>
  @endforeach
@endif



@if ($detailsType == 'USER_TEST_RESULT_ANSWERS')
  <div style="direction: rtl;font-size: 18px;font-weight: bold;"> اجابات الطالب :  {{ $data->isnotEmpty() ? $data->first()->first()->course_name : '' }}</div><br>

  @foreach($data as $group)
  @php
    $correctAnswer = \Illuminate\Support\Str::between( $group->first()->correct_answer , ':', '}');
    $correctAnswer = str_replace('"', "", $correctAnswer);
  @endphp

    <div style="direction: rtl;font-size: 18px;font-weight: bold; color: {{ ($correctAnswer == $group->first()->user_answer_id) ? 'green' : 'red' }}">{{ $group->first()->question_title }}</div>
        @foreach($group as $item)
          <div style="direction: rtl;font-size: 18px;font-weight: normal; color: {{ ($correctAnswer == $item->course_answer_id) ? 'green' : '' }}">
              {{ $item->course_answer_id }} - {{ $item->answers_title }}
          </div>
        @endforeach
        <br>
    <div style="direction: rtl;font-size: 18px;font-weight: bold;">الاجابة الصحيحة : {{ $group->first()->correct_answer }}</div>
    <div style="direction: rtl;font-size: 18px;font-weight: bold;">اجابة المستخدم : {{ $group->first()->user_answer_id }}</div>

  <hr>
  @endforeach
@endif
