@extends('back/layouts.app')
@section('back_css')
    <x-admin.datatable.header-css/>
    <style>

        a.kt-userpic.kt-userpic--circle.kt-margin-r-5.kt-margin-t-5 img {
        width: 50px;
    }
        div#action_div {
        display: inline-flex;
    }
    a.btn.btn-brand.btn-icon-sm {
        color: white;
        background: #0a7a18;
    }
    </style>
@endsection

@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="kt-portlet">
    <div class="kt-portlet__head">
      <div class="kt-portlet__head-label">

      </div>
    </div>
  </div>

  <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">

      <style>
        .dataTables_wrapper div.dataTables_filter { display: contents; }
      </style>


      <!--begin: Datatable -->
      <table class="table table-striped- table-bordered table-hover table-checkable" style="width: 500px;" id="kt_table_1">
        <thead>
          <tr>
            <th>المحتبرين بدون تكرار</th>
            <th>المختبرين</th>
            <th>المسجلين</th>
            <th>غير المختبرين</th>
            <th>الشهادات</th>
          </tr>
        </thead>
        <tbody>
            <tr>
              <td><a href="{{ route('dashboard.statistics.export', ['statistic' => 'tested_without_dublicate_count']) }}">{{ $testedWithoutDublicateCount[0]->total_count }}</a></td>
              <td><a href="{{ route('dashboard.statistics.export', ['statistic' => 'tested_count']) }}">{{ $testedCount[0]->total_count }}</a></td>
              <td><a href="{{ route('dashboard.statistics.export', ['statistic' => 'registered_count']) }}">{{ $registeredCount[0]->total_count }}</a></td>              
              <td><a href="{{ route('dashboard.statistics.export', ['statistic' => 'not_tested_count']) }}">{{ $notTestedCount[0]->total_count }}</a></td>
              <td>{{ $certificatesCount[0]->total_count }}</td>
            </tr>
        </tbody>
      </table>
      <!--end: Datatable -->

    </div>
  </div>
</div>


@endsection




@section('js_pagelevel')
<x-admin.datatable.footer-js/>
<x-buttons.but_delete_inline_js/>
<script>
function submitForm(me)
{
  $(me).closest("form").submit();
}
</script>

@endsection
