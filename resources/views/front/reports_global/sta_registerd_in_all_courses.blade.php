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
    <div class="col-12">
      @include('front.reports_global.index')
    </div>
  </div>

  <div style="text-align: right;">
     بحث عن الطلاب<br><br>
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
                            <th>عدد الاختبارات</th>
                            <th>عدد الدورات الفعالة المشترك فيه</th>
                            <th>متبقى له من المشترك فيه</th>
                            <th>عدد الدورات الفعالة على الموقع</th>
                            <th>متبقى له عامة</th>
                            <th>متوسط الدرجات</th>
                            <th>التليفون</th>
                            <th>البريد</th>
                            <th>الواتس</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($report as $item)
                            <tr>
                              <td></td>
                              <td>
                                @php $params = ['userId' => $item->user_id, 'detailsType' => 'USER_COURSES']; @endphp
                                <button onclick='getUserDetails( @json($params) );' type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">{{ $item->user_name }}</button>
                              </td>
                              <td>{{ $item->tests_count }}</td>
                              <td>{{ $item->subscribtions_count_in_active_courses }}</td>
                              <td>{{ $item->subscribtions_count_in_active_courses - $item->tests_count }}</td>
                              <td>{{ $countActiveCourses }}</td>
                              <td>{{ $countActiveCourses - $item->tests_count  }}</td>
                              <td >{{ round($item->over_all_degree) }}</td>
                              <td>{{ $item->phone }}</td>
                              <td>{{ $item->email }}</td>
                              <td>{{ $item->whats_app }}</td>
                              <td class="text-center">
                                  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" onclick="getcourses({{ $item->user_id }})">عرض الدورات</button>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>

                  </div>

      </div>



    </div>

</div>
</div>



<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body" id="modal-body-div">
        <p> </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



@endsection
@section('script')
<script>
  jQuery(document).ready(function($){
    $('#site_id').change(function(){
          $.get("{{ route('front.ajax_courses','ar')}}",
              { option: $(this).val() },
              function(data) {
                  var model = $('#course_id');
                  model.empty();
                  model.append("<option value=''> </option>");
                  $.each(data, function(index, element) {
                          model.append("<option value='"+ element.id +"'>" + element.title + "</option>");


                  });
              });
      });
  });
</script>

<x-get-user-less-courses-js/>

<x-get-user-courses-details/>

<script>
  // datatable settings
  dt1_display_search_input_columns_values = [1,7];
  dt1_display_search_droplist_columns_values = [];
</script>

<x-admin.datatable.footer-js-full/>


@endsection
