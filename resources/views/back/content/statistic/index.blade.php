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
        {{--
        <h3 class="kt-portlet__head-title">
          <div class="row" style="display: inline-flex;">
             <x-buttons.but_new link="{{ route( 'dashboard.teachers.create' ) }}"/>
            <x-buttons.but_delete link='{{ route("dashboard.teachers.destroy") }}'/>
          </div>
        </h3>
        --}}

      </div>
    </div>


    <!--begin::Form-->
    <!--end::Form-->
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
            <th>{{ __('words.date') }}</th>
            <th>{{ __('words.date') }}</th>
            <th>{{ __('words.count') }} , {{ $dailyRegisterdSum }}</th>
            <th>عدد الاختبارات {{ $dailyTestedSum?->tested_members_sum }}</th>
            <th>المختبرين بدون تكرار {{ $dailyTestedNoDublicateSum?->tested_members_sum }}</th>
            <th>عدد الشهادات {{ $certificatesCount[0]->total_count }}</th>
            <th>الغير مختبرين  {{ $notTestedCount[0]->total_count }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($dailyRegisterd as $item)
            <tr>
              <td></td>
              <td>{{ $item->date }}</td>
              <td>{{ $item->member_counts }}</td>
              <td>{{ $dailyTested->where('date', $item->date)->first()?->tested_members_counts }}</td>
              <td>{{ $dailyTestedNoDublicate->where('date', $item->date)->first()?->tested_members_counts }}</td>
              <td>{{ $dailyCertificatesCount->where('date', $item->date)->first()?->certificates_counts }}</td>
              <td></td>
            </tr>
          @endforeach
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
