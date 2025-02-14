@extends('front.layouts.report')
@section('head')
    <!-- Styles -->
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
    <link rel="stylesheet" href="{{ asset('assets/front/style_rtl.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('assets/front/style.css') }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
 <style>
    body {
      background-color: #d0dafb;
      overflow-x: hidden;
    }

    th, td {
        text-align: center;
        padding: 8px;
        background: #fff;
        border: 2px solid #cacaca;
    }
    th {
        background: #c7c7c7;
        border: 2px solid #808080;
    }
    table{
      margin: 5px 0 ;
    }

    div#kt_table_1_wrapper {
        width: 100% !important;
    }
    table.col-12.dataTable.no-footer {
        width: 100% !important;
    }
    .dt-buttons.btn-group.flex-wrap {
        direction: ltr;
    }

    table a {
        text-decoration: underline;
        font-weight: bold;
        font-size: medium;
        color: #017480;
    }
    .col-md-8 {
        overflow-x: scroll;
    }
    .td-f {
        text-align: center;
        border: 1px solid #abaaaa;
        padding: 6px !important;
        background-color: #fff;
    }
    .th-f {
        text-align: center;
        border: 2px solid #808080;
        padding: 6px !important;
        background-color: #abaaaa;

    }
    .total {
        background-color: #8ff1a2a8;
    }
    td.th-fs {
        background-color: #8affa4bf;
        font-weight: 900;
    }
    tr.for_search td {
        background-color: #b3f3cb !important;
    }
    .total-td{
      font-weight: bold;
      background-color: #a7e5fb;
    }
    .result-tr{

    }
    .result-tr-search{
      background-color: #c5efa8;
    }
 </style>

@endsection
@section('content')


<section class="register-now d-flex justify-content-between align-items-center row" style="background-color: #d4ddfb;padding: 40px;">

  @include('front.reports_global.nav_statistics')

  <div class="col-lg-12" style="overflow: auto;text-align: right;font-size: 30px;">
    إحصائيات الدبلومات
    <form method="POST" class="row" action="{{ route('front.sites_statistics','ar') }}">
        @csrf
            <div class="col-lg-2">
                <div class="form-group">
                  <label for="from">بداية من يوم</label>
                    <input type="date" class="form-control @error('from') is-invalid @enderror" name="from" id="from" value="{{ old('from', $from ?? '') }}"  autocomplete="from" style="color: black" autofocus placeholder="{{ __('field.from') }}">
                    @error('from')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                  <label for="to">حتي نهاية يوم </label>
                    <input type="date" class="form-control @error('to') is-invalid @enderror" name="to" id="to" value="{{ old('to', $to ?? '') }}"  autocomplete="to" style="color: black" autofocus placeholder="{{ __('field.to') }}">
                    @error('to')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-2" style="text-align: center;">
              <div class="form-group">
                <label for="course_id">.</label>
                <button type="submit" class="btn btn-primary w-100">
                  عرض التقرير
                </button>
              </div>
            </div>
    </form>
  </div>


  <div class="col-lg-12" style="overflow: auto;">
      <table style="width: 100%;">
        <thead>
          <th> الدبلوم </th>
          <th class="result-tr"> عدد الإشتراكات </th>
          <th class="result-tr-search"> الفترة المحددة </th>
          <th class="result-tr"> عدد المشتركين </th>
          <th class="result-tr-search"> الفترة المحددة </th>
          <th class="result-tr"> عدد الاختبارات </th>
          <th class="result-tr-search"> الفترة المحددة </th>
          <th class="result-tr">  عدد المختبرين </th>
          <th class="result-tr-search"> الفترة المحددة </th>
          <th class="result-tr"> عدد الشهادات </th>
          <th class="result-tr-search"> الفترة المحددة </th>
          <th class="result-tr"> عدد الناجحين </th>
          <th class="result-tr-search"> الفترة المحددة </th>
        </thead>
        <tbody>
        @php $displayTotal = true; @endphp
        @foreach( $sites as $site )
          <tr>
                    <!-- اجمالى المرحلة الاولى -->
                    @if($site->new_flag == 1 && $displayTotal == true)
                    <tr>
                        @php $displayTotal = false; @endphp
                        <th> اجمالى </th>
                        <td class="total-td">{{ number_format( $counts[0]->total_courses_subs ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_courses_subs_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_subs ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_subs_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_tests_with_repeated ) }} <br>بدون تكرار <br> {{ number_format( $counts[0]->total_stage_tests ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_tests_with_repeated_period ) }} <br>بدون تكرار <br> {{ number_format( $counts[0]->total_stage_tests_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_tested_users_with_repeated ) }}<br>بدون تكرار <br> {{ number_format( $counts[0]->total_stage_tested_users ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_tested_users_period_with_repeated ) }}<br>بدون تكرار <br> {{ number_format( $counts[0]->total_stage_tested_users_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_cirts_count_with_repeated ) }} <br>بدون تكرار <br> {{ number_format( $counts[0]->total_stage_cirts_count ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_cirts_count_period_with_repeated ) }} <br>بدون تكرار <br> {{ number_format( $counts[0]->total_stage_cirts_count_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_successed_users_with_repeated ) }}  <br>بدون تكرار <br> {{ number_format( $counts[0]->total_stage_successed_users ) }}</td>
                        <td class="total-td">{{ number_format( $counts[0]->total_stage_successed_users_period_with_repeated ) }}  <br>بدون تكرار <br> {{ number_format( $counts[0]->total_stage_successed_users_period ) }}</td>
                    </tr>
                    @endif
              <td class="result-tr">{{ $site->title }}</td>
              <td class="result-tr">{{ $site->subs_count * $site->courses_count}}</td>
              <td class="result-tr-search">{{ $site->subs_count_period * $site->courses_count}}</td>
              <td class="result-tr">{{ $site->subs_count }}</td>
              <td class="result-tr-search">{{ $site->subs_count_period }}</td>
              <td class="result-tr">{{ $site->tests_count_with_repeated }}</td>
              <td class="result-tr-search">{{ $site->tests_count_period_with_repeated }}</td>
              <td class="result-tr">{{ $site->tested_users_count }}</td>
              <td class="result-tr-search">{{ $site->tested_users_count_period }}</td>
              <td class="result-tr">{{ $site->cirts_count }}</td>
              <td class="result-tr-search">{{ $site->cirts_count_period }}</td>
              <td class="result-tr">{{ $site->successed_users_count }}</td>
              <td class="result-tr-search">{{ $site->successed_users_count_period }}</td>
          </tr>
        @endforeach
                    <!-- اجمالى المرحلة الثانية -->
                    <tr>
                        <th> اجمالى </th>
                        <td class="total-td">{{ number_format( $counts[1]->total_courses_subs ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_courses_subs_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_subs ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_subs_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_tests_with_repeated ) }} <br>بدون تكرار <br> {{ number_format( $counts[1]->total_stage_tests ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_tests_with_repeated_period ) }} <br>بدون تكرار <br> {{ number_format( $counts[1]->total_stage_tests_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_tested_users_with_repeated ) }}<br>بدون تكرار <br> {{ number_format( $counts[1]->total_stage_tested_users ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_tested_users_period_with_repeated ) }}<br>بدون تكرار <br> {{ number_format( $counts[1]->total_stage_tested_users_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_cirts_count_with_repeated ) }}<br>بدون تكرار <br> {{ number_format( $counts[1]->total_stage_cirts_count ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_cirts_count_period_with_repeated ) }}<br>بدون تكرار <br> {{ number_format( $counts[1]->total_stage_cirts_count_period ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_successed_users_with_repeated ) }}<br>بدون تكرار <br> {{ number_format( $counts[1]->total_stage_successed_users ) }}</td>
                        <td class="total-td">{{ number_format( $counts[1]->total_stage_successed_users_period_with_repeated ) }}<br>بدون تكرار <br> {{ number_format( $counts[1]->total_stage_successed_users_period ) }}</td>
                    </tr>
        </tbody>
      </table>
  </div>

</section>



@endsection

@section('script')

@endsection
