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

    <!--begin::Form-->
    <!--end::Form-->
  </div>

  <div class="kt-portlet kt-portlet--mobile">



    <div class="kt-portlet__body">

      <style>
        .dataTables_wrapper div.dataTables_filter { display: contents; }
      </style>

      <!--begin: Datatable -->
      <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
        <thead>
          <tr>
            <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
            <td>type</td>
            <td>title</td>
            <td>total</td>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $item)
            @if($item->type == 'site')
              <tr>
                    <td></td>
                    <td>{{$item->groupBy}}</td>
                    <td>{{@$item->site->title}}</td>
                    <td>{{$item->total}}</td>


                </tr>
            @elseif($item->type == 'course')
              <tr>
                  <td></td>
                  <td>{{$item->groupBy}}</td>
                  <td>{{@$item->course->title}}</td>
                  <td>{{$item->total}}</td>

              </tr>

            @else
            <tr>
                <td></td>
                <td>{{$item->groupBy}}</td>
                <td>--</td>
                <td>{{$item->total}}</td>

            </tr>
            @endif
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
