@extends('front.layouts.landing')
@section('head')
    <!-- Styles -->
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
    <link rel="stylesheet" href="{{ asset('assets/front/style_rtl.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('assets/front/style.css') }}">
    @endif
     <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <x-admin.datatable.header-css/>

   <style>
   .dataTables_scrollHead {overflow: inherit !important;}
   </style>

@endsection
@section('content')



<div class="container-fluid" style="background-color: #daeee9;text-align: right;">
  <div class="container" style="padding: 20px 0px;">

    <div class="row">
        @include('front.reports_global.index')
    </div>

    <div style="text-align: right;">
      <br>
    اشتراكات الطلاب داخل كل دبلوم<br><br>
    </div>

    <div class="row">
      <div class="col-md-12">


                    <div class="row">
                      <style> .dataTables_wrapper div.dataTables_filter { display: contents; } </style>
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                        <thead>
                          <tr>
                            <th></th>
                            <th>الطالب</th>
                            <th>البريد</th>
                            <th>الواتس</th>
                            <th>التليفون</th>
                            <th>الدبلوم</th>
                            <th>عدد اختبارات الطالب فى الدبلوم</th>
                            <th>عدد الدورات فى الدبلوم</th>
                            <th>عدد الدورات داخل الدبلوم التى لم يشترك بها الطالب</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($report as $item)
                            <tr>
                              <td></td>
                              <td>{{ $item->user_name }}</td>
                              <td>{{ $item->email }}</td>
                              <td>{{ $item->whats_app }}</td>
                              <td>{{ $item->phone }}</td>
                              <td>{{ $item->site_title }}</td>
                              <td>{{ $item->tests_subscriptions_in_site }}</td>
                              <td>{{ $countCoursesInEachSite }}</td>
                              <td>{{ $countCoursesInEachSite - $item->tests_subscriptions_in_site }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>

                  </div>

      </div>



    </div>

</div>
</div>

@endsection
@section('script')

<script>
  // datatable settings
  dt1_display_search_input_columns_values = [];
  dt1_display_search_droplist_columns_values = [];
</script>

<x-admin.datatable.footer-js-full/>
<x-buttons.but_delete_inline_js/>
@endsection
