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
        <h3 class="kt-portlet__head-title">
          <div class="row" style="display: inline-flex;">
             <x-buttons.but_new link="{{ route( 'dashboard.settings.create' ) }}"/>
            <x-buttons.but_delete link='{{ route("dashboard.settings.destroy") }}'/>
          </div>
        </h3>

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
      <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
        <thead>
          <tr>
            <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
            <th>ID</th>
            <th>{{ __('words.name') }}</th>
            <th>{{ __('words.link') }}</th>
            <th>{{ __('words.icon') }}</th>
            <th>{{ __('words.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $item)
              <tr id="{{ $item->id }}">
                  <td value="{{ $item->id }}"></td>
                  <td>{{ $item->id }}</td>
                  <td>
                    <a href="{{ route('dashboard.settings.edit' , [ 'id' => $item->id ] )}}" class="td_clickable">
                      {{ $item->title }}
                    </a>
                    &nbsp;&nbsp;

                  </td>

                  <td>
                    {{ $item->link }}
                  </td>

                  <td>
                    {!! $item->icon !!}
                  </td>

                  <td>
                    <div id="action_div" class="dt_action_div">
                      <div><x-buttons.but_edit link="{{ route('dashboard.settings.edit' , [ 'id' => $item->id ] ) }}" icon='true'/></div>
                      <div><x-buttons.but_delete_inline link="{{ route('dashboard.settings.destroy' ) }}" ids="{{ $item->id }}" icon='true'/></div>
                    </div>
                  </td>
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
