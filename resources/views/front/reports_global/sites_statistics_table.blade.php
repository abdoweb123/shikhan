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
 <style>
     .header-area {
        position: absolute !important;
     }
     .section-padding-100-0 {
        padding-top: 145px;
        padding-bottom: 0;
     }
     .register-now .register-contact-form .forms .form-control {
         color: rgb(0 0 0) !important;
     }
     select#country {
        display: block !important;
     }
    .nice-select.form-control {
        display: none !important;
    }
    ul.pagination {
        display: none;
    }
    input#code_country {
        direction: ltr;
        padding: 5px;
    }
    .ul-taps{
      width: 100%;
      display: inline-flex;
    }
    .ul-taps li{

      text-align: center;
      width: 50%;
    }
    .ul-taps li h4{
      margin-bottom: 15px !important;
        margin-top: -18px !important;
        border: 2px solid #e8bb8f;
        box-shadow: 1px 2px 12px 0px #eabe92;
        padding: 10px;
        border-radius: 5px;
        color: #00c1e8;
    }
    h4.unactive {
        background-color: #d89a5f;
        color: #fff !important;
    }
    h4.unactive:hover {
      background-color: #fff;
      color: #d89a5f !important;
    }
    button.btn.clever-btn.w-100 {
    color: #fff;
    background: #2266ae;
    }
    ul.ul-taps {
    margin-bottom: 25px;
    }
    @media only screen and (max-width: 767px){
        /* .register-now .register-now-countdown {
          display: none !important;
        } */
        .register-now .register-contact-form {
          margin-top: 45px !important;
          padding: 6px !important;
        }
        .ul-taps li h4 {
          font-size: 18px !important;
          padding: 4px;
          margin: 7px 2px !important;
          font-weight: 900;
        }
        section.register-now.section-padding-100-0.d-flex.justify-content-between.align-items-center.row {
          padding: 5px 50px 0 0;
        }
    }
    .section-padding-100-0 {
      padding-top: 105px;
      }
    body {
      background-color: #d0dafb;
    }
    .color-title {
        color: rgb(107 75 41);
    }
    .color-content {
        color: #d89a5f;
    }
    select#site_id ,select#course_id {
        display: block !important;
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
    body{
      overflow-x: hidden;
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
    span.is_not_active {
        background-color: #ca000042;
        padding: 5px;
        border-radius: 5px;
        font-weight: 800;
        color: #820000;
    }
    span.is_active {
        background-color: #0ea9005c;
        padding: 5px 10px;
        color: #025a29;
        font-weight: 600;
        border-radius: 5px;
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
 </style>

@endsection
@section('content')


<section class="register-now d-flex justify-content-between align-items-center row" style="background-color: #d4ddfb;padding: 40px;">

  @include('front.reports_global.nav_statistics')



  <div class="col-lg-12" style="overflow: auto;text-align: right;font-size: 30px;">إحصائيات الدبلومات</div>

  <div class="col-lg-12" style="overflow: auto;">

      <table style="width: 100%;">
        <thead>
          <th>  </th>
          <th> الدبلوم </th>
          <th> عدد الدورات </th>
          <th> عدد الدورات الفعالة</th>
          <th> عدد الإشتراكات </th>
          <th> عدد المشتركين </th>
          <th> عدد الاختبارات </th>
          <th>  عدد المختبرين </th>
          <th> عدد الشهادات </th>
          <th> عدد الناجحين </th>
        </thead>
        <tbody>
          @foreach( $sites as $site)
            <tr>
                <td>{{ $site->site_id }}</td>
                <td>{{ $site->title }}</td>
                <td>{{ $site->courses_count }}</td>
                <td>{{ $site->active_courses_count }}</td>
                <td>{{ $site->subs_count }}</td>
                <td>{{ $site->subs_users_count }}</td>
                <td>{{ $site->tests_count }}</td>
                <td>{{ $site->tested_users_count }}</td>
                <td>{{ $site->cirts_count }}</td>
                <td>{{ $site->successed_users_count }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

  </div>





  <!-- تقارير قترات جدبد -->
  @isset($search)
  <div class="col-md-12" style="text-align: right;padding-top: 20px;">
      <div style="font-size: 30px;font-weight: bold;padding: 15px 0px 15px 0px;">تقرير الدورات / الفترات</div>
      <div class="row">
        <table style="width: 100%;" id="kt_table_2">
          <tbody>
            <!-- <tr>
              <td class="th-f">  عدد المسجلين فى الأكاديمية </td>
              <td>{{$countOfMembers}}  مشارك </td>
              <td class="th-f">عدد المسجلين بالدورات</td>
              <td>{{$countOfSubsUsers}}</td>
            </tr> -->
            <!-- <tr>
              <td class="th-f"> عدد المسجلين بالدورات بالتكرار </td>
              <td>{{ $countOfSubs }} </td>
              <td class="th-f"> عدد المختبرين فى كامل الموقع </td>
              <td>{{ $countOfTestedUsers }}</td>
            </tr> -->
            @isset($countOfSubsUsersSearch)
              <tr>
                <td class="th-fs">المسجلين بالموقع :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                <td>{{ $countOfMembersSearch }} </td>
                <td class="th-fs">المسجلين بالدورات  :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                <td>{{ $countOfSubsUsersSearch }}</td>
              </tr>
              <tr>
                <td class="th-fs">المسجلين بالدورات بالتكرار  من :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                <td>{{ $old_or_new ? '' : $countOfSubsSearch }} </td>
                <td class="th-fs">المختبرين  من :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                <td>{{  $old_or_new ? '' : $countOfTestedUsersSearch}}</td>
              </tr>
            @endisset
          </tbody>
        </table>
      </div>
  </div>
  @endisset

















  <div class="col-md-12" style="text-align: right;overflow: auto;">

      <!-- تقارير الدبلومات -->
      @isset($sites_subscriptions_count)
        <div style="font-size: 30px;font-weight: bold;padding: 15px 0px 15px 0px;">تقرير الدبلومات</div>

        <table style="width: 100%;" id="kt_table_1">
          <thead >
            <tr>
              <th> الاســــم</th>
              <th>عدد الدورات فى الدبلوم</th>
              <th>عدد الدورات الفعالة فى الدبلوم</th>
              <th>النسبة</th>
              <th>الحالة</th>
              <th>مجموع الاختبار</th>
              <th>مجموع الاشتراكات</th>
              <th> النسبة </th>
            </tr>
          </thead>
          <tbody>
              @foreach($sites_subscriptions_count as $site_subscription_count)
                <tr>
                  <td>{{ $site_subscription_count['title'] }} </td>
                  <td>{{ $site_subscription_count['courses_count'] }}</td>
                  <td>{{ $site_subscription_count['courses_ative_count'] }}</td>
                  <td>{{ round(    $site_subscription_count['count_test'] / ( $site_subscription_count['count_test'] * $site_subscription_count['courses_ative_count'])    , 2) }}</td>
                  <td>{{$site_subscription_count['status']}}</td>
                  <td>{{ $site_subscription_count['count_test'] }}</td>
                  <td>{{ $site_subscription_count['count_subscriptions'] }}</td>
                  <td>@php $ev= $site_subscription_count['count_subscriptions'] > 0 ? ($site_subscription_count['count_test'] / $site_subscription_count['count_subscriptions']) *100 : 0;@endphp {{round($ev, 2) }} %</td>
                </tr>
              @endforeach
          </tbody>
        </table>

      @endisset











    <!-- سيتم نقل هذا التقرير الى دروب ليست التقارير الجديدة -->
    {{--
    <div class="col-md-7">
      <table class="col-md-12 w-100" id="kt_table_1">
        <thead >
          <tr>
            <th> الاســــم</th>
            @isset($results)
              <th>اسم الدبلوم</th>
            @endisset
            <th>الحالة</th>
            <th>مجموع الاختبار</th>
            <th>مجموع الاشتراكات</th>
            <th> النسبة </th>
          </tr>
        </thead>
        <tbody>
          @isset($results)
            @foreach($results as  $result)
              <tr>
                <?php $alias=$result->alias; ?>
                <td>{{ $result->translation->first() != null ? $result->translation->first()->name : $result->title}}  </td>
                <td>@foreach($result->sites as $site ) @if(! $loop->first) -  @endif {{ $site->translation->first() != null ? $site->translation->first()->name : $site->title}} @endforeach</td>
                <td>{!! $result->exam_at <= date('Y-m-d') && $result->exam_at !=  Null ? '<span  class="is_active" > نشط </span>' : '<span class="is_not_active" > غير نشط </span>' !!}</td>
                <td><a href="{{ route('front.report_courses.users',['lang' => 'ar','course' => $alias]) }}">{{$result->test_results_count}}</a></td>
                <td>{{$result->subscribers_count}}</td>
                <td>@php $ev= $result->subscribers_count > 0 ? ($result->test_results_count / $result->subscribers_count) *100 : 0;@endphp {{round($ev, 2) }} %</td>
              </tr>
            @endforeach
          @endisset
        </tbody>
      </table>
    </div>
    --}}




















    {{--
    <!-- دورات سيتم نقله شاشاة منفصلة - التقرير الاصلى تقارير فترات -->
    <div class="col-md-12" style="text-align: right;padding-top: 20px;">
      <div style="font-size: 30px;font-weight: bold;padding: 15px 0px 15px 0px;">تقرير الدورات / الفترات</div>

      @isset($results)
          <div class="row">
            <table style="width: 100%;" id="kt_table_2">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td class="th-f">  عدد المسجلين فى الأكاديمية </td>
                  <td>{{$countOfMembers}}  مشارك </td>
                  <td class="th-f">عدد المسجلين بالدورات</td>
                  <td>{{$countOfSubsUsersSearch}}</td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td class="th-f"> عدد المسجلين بالدورات بالتكرار </td>
                  <td>{{ $countOfSubsSearch }} </td>
                  <td class="th-f"> عدد المختبرين فى كامل الموقع </td>
                  <td>{{ $countOfTestedUsers }}</td>
                  <td></td>
                  <td></td>
                </tr>
                @isset($countOfSubsUsersSearch)
                  <tr>
                    <td class="th-fs">المسجلين بالموقع :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                    <td>{{$countOfMembersSearch}} جديد </td>
                    <td class="th-fs">المسجلين بالدورات  :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                    <td>{{ $countOfSubsUsersSearch }}</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td class="th-fs">المسجلين بالدورات بالتكرار  من :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                    <td>{{$countOfSubsSearch}} </td>
                    <td class="th-fs">المختبرين  من :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                    <td>{{$countOfTestedUsersSearch}}</td>
                    <td></td>
                    <td></td>
                  </tr>
                @endisset

                <tr>
                  <th>اسم الدورة</th>
                  <th>اسم الدبلوم</th>
                  <th>تاريخ الدورة</th>
                  <th>بدون التكرار</th>
                  <th>بالتكرار</th>
                  <th>المشتركين</th>
                </tr>
                @php $countTests = 0;$countTests_withR = 0; @endphp
                @foreach($results as $result)


                    @if ( $result->exam_at <= date('Y-m-d') && $result->exam_at !=  Null )
                        <tr id="site_{{ $result->site_id }}">
                          <td>{{ $result->translation->first() != null ? $result->translation->first()->name : $result->title }}</td>
                          @php $bgc = '#fff'; @endphp

                          <td style="background-color: {{ $bgc }}">@foreach( $result->sites as $site ) @if(! $loop->first) - @endif {{ $site->translation->first() != null ? $site->translation->first()->name : $site->title}} @endforeach</td>
                          <td>{{ $result->translation->first() != null ? $result->translation->first()->date_at : $result->date_at }}</td>
                          <td>{{ $result->test_results_count }}</td>
                          @php
                           $resultwithR = $dataWithCount->where('id',$result->id )->first();
                           $countTests = $countTests + $result->test_results_count;
                           $countTests_withR = $countTests_withR + $resultwithR->test_results_count;
                          @endphp
                          <td>{{ $resultwithR->test_results_count }}</td>
                          <td>{{ $result->subscribers_count }}</td>
                        </tr>
                    @endif
                @endforeach
              <tr>
                <td> مجموع المختبرين</td>
                <td></td>
                <td></td>
                <td>{{ $countTests }}</td>
                <td>{{ $countTests_withR  }}</td>
                <td></td>
              </tr>
              </tbody>
            </table>
        </div>
      @endisset
    </div>
    --}}


  </div>



    <!-- Register Now Countdown -->
</section>
<!-- ##### Register Now End ##### -->
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

<x-admin.datatable.footer-js/>

<script>
$(document).ready( function () {
    var table = $('#kt_table_2').DataTable({
                    dom: 'fBptipr', // pBfrtip    Blfrtip
                    // buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print' ]
                    'ordering': false,
                    "pageLength": 100,


                    scrollX: true,
                    language: {
                      paginate: {
                        next: "التالى",
                        previous: "السابق"
                      }
                    },
                    columnDefs: [ { // scheckbox -----
                        orderable: true,
                        className: 'select-checkbox',
                        targets:   0
                    } ],
                    select: {
                        style:    'multi',
                        selector: 'td:first-child'
                    },

                    // order: [[ 1, 'asc' ]], // end check box ------
                    buttons: [
                      {extend:'pageLength'},
                       { extend: 'copy' },
                       { extend: 'excel' },
                       { extend: 'csv' },
                       { extend: 'print' },
                       { text: 'pdf' , action: function () {

                                           // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                                           data = document.getElementById("kt_table_2").innerHTML;
                                           // Done but error 414 request url is too larg solved by changing get to post

                                           $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
                                           // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                                           $.ajax({
                                           url: "/pdf",
                                           type: 'post',
                                           // dataType: "json",
                                           data: { 'data':data },
                                           xhrFields: { responseType: 'blob' },
                                           success: function(response, status, xhr) {
                                               // https://github.com/barryvdh/laravel-dompdf/issues/404

                                               // console.log(response);
                                               // var filename = "" ;
                                               // var disposition = xhr.getResponseHeader('Content-Disposition');
                                               // if (disposition) {
                                               //     var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                               //     var matches = filenameRegex.exec(disposition);
                                               //     if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                                               // }
                                               // var blob = new Blob([response], { type: 'application/octet-stream' });
                                               // var URL = window.URL || window.webkitURL;
                                               // var downloadUrl = URL.createObjectURL(blob);
                                               // var a = document.createElement("a");
                                               // a.href = downloadUrl;
                                               // // a.setAttribute('href', );
                                               // a.download = filename;
                                               // document.body.appendChild(a);
                                               // a.target = "_blank";
                                               // a.click();


                                               var filename = "";
                                               var disposition = xhr.getResponseHeader('Content-Disposition');

                                                if (disposition) {
                                                   var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                                   var matches = filenameRegex.exec(disposition);
                                                   if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                                               }
                                               var linkelem = document.createElement('a');
                                               try {
                                                   var blob = new Blob([response], { type: 'application/octet-stream' });

                                                   if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                                       //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                                       window.navigator.msSaveBlob(blob, filename);
                                                   } else {
                                                       var URL = window.URL || window.webkitURL;
                                                       var downloadUrl = URL.createObjectURL(blob);

                                                       if (filename) {
                                                           // use HTML5 a[download] attribute to specify filename
                                                           var a = document.createElement("a");

                                                           // safari doesn't support this yet
                                                           if (typeof a.download === 'undefined') {
                                                               window.location = downloadUrl;
                                                           } else {
                                                               a.href = downloadUrl;
                                                               a.download = filename;
                                                               document.body.appendChild(a);
                                                               a.target = "_blank";
                                                               a.click();
                                                           }
                                                       } else {
                                                           window.location = downloadUrl;
                                                       }
                                                   }

                                               } catch (ex) {
                                                   console.log(ex);
                                               }

                                           },error: function (xhr, status, error)
                                              { console.log(xhr.responseText); },
                                           });
                                      }
                       }
                   ]
                });


                // select all  -------------------------------------------------
                $("#select_all").on( "click", function(e) {
                    if ($(this).is( ":checked" )) {
                        table.rows().select();
                        $('#delete').removeClass('btn btn-outline-danger');
                        $('#delete').addClass('btn btn-danger btn-elevate');
                        $('#delete').text( deleteWord + ' : ' + table.rows('.selected').data().length );
                    } else {
                        table.rows().deselect();
                        $('#delete').removeClass('btn btn-danger btn-elevate');
                        $('#delete').addClass('btn btn-outline-danger');
                        $('#delete').text( deleteWord );
                    }
                });


                // select row  -------------------------------------------------
                deleteWord = "{{ __('words.delete') }}";
                $('#kt_table_2 tbody').on( 'click', 'tr', function () {
                    $(this).toggleClass('selected');

                    if (table.rows('.selected').data().length > 0 ) {
                        $('#delete').removeClass('btn btn-outline-danger');
                        $('#delete').addClass('btn btn-danger btn-elevate');
                        $('#delete').text( deleteWord + ' : ' + table.rows('.selected').data().length );
                    } else {
                      $('#delete').removeClass('btn btn-danger btn-elevate');
                      $('#delete').addClass('btn btn-outline-danger');
                      $('#delete').text( deleteWord );
                    }
                });


                // delete button -----------------------------------------------
              $( '#frm_delete' ).on('submit', function(e) {

                  e.preventDefault();

                  var dataList=[];
                  $("#kt_table_2 .selected").each(function(index) {
                      dataList.push($(this).find('td:first').attr('value'))
                  })

                  if(dataList.length == 0){
                    Swal.fire({
                        title: "{{ __('admin/dashboard.please_select_record') }}",
                        text: "{{ __('admin/dashboard.please_select_record') }}",
                        type:"info" ,
                        timer: 3000,
                        showConfirmButton: true,
                        confirmButtonText: '{{ __("admin/dashboard.ok") }}'
                    });
                    return;
                  };

                  var type = $(this).attr('method');
                  var url = $(this).attr('action');
                  var data = $(this).serialize();
                  data = data + '&' + 'ids=' + dataList;

                  Swal.fire({
                    title: '{{ __("messages.confirm_delete_title") }}', text: '{{ __("messages.confirm_delete_text") }}', type: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: '{{ __("messages.yes_delete") }}' , cancelButtonText: '{{ __("messages.cancel") }}'
                  }).then((result) => {
                    if (result.value) {
                              $.ajax({
                              url : url ,
                              type : type ,
                              data : data , // {'ids':dataList},
                              dataType:"JSON",
                              success: function (data) {
                                  // console.log(data);
                                  // return;

                                  if(data['success']) {
                                    location.reload();
                                  }

                                  if(data['error']) {
                                      Swal.fire("{{trans('messages.deleted_faild')}}", data['error'], "error");
                                  }
                              },
                              error: function (xhr, status, error)
                              {
                                if (xhr.status == 419) // httpexeption login expired or user loged out from another tab
                                {window.location.replace( '{{ route("index") }}' );}
                                Swal.fire("", "{{ __('messages.deleted_faild') }}", "error");
                                console.log(xhr.responseText);

                              }
                          });
                    }
                  })

              });
              //  --------------------------------------------------------------


});





</script>

<x-buttons.but_delete_inline_js/>
@endsection
