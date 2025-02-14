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

    .btn-delete{
        background-color: #c11515;
        border-color: #c11515;
    }

    .btn-delete i{
        color: white;
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
             <x-buttons.but_new link="{{ route( 'dashboard.lessons.create' ) }}"/>
            <x-buttons.but_delete link='{{ route("dashboard.lessons.destroy") }}'/>
          </div>
        </h3>

      </div>
    </div>

      <x-admin.datatable.page-alert />

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
            <th>{{ __('words.image') }}</th>
            <th>{{ __('words.language') }}</th>
            <th>{{ __('words.active_status') }}</th>
            <th>deleted</th>
            <th>{{ __('words.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $item)

                <tr id="{{ $item->id }}">
                    <td value="{{ $item->id }}"></td>
                    <td>{{ $item->id }}</td>
                    <td>
                      <a href="{{ route('dashboard.lessons.edit' , [ 'id' => $item->id ] )}}" class="td_clickable">
                        {{ $item->title != null  ? $item->title : $item->title_general.' - '. __('words.not_translated') }}
                      </a>&nbsp;&nbsp;
                    </td>
                    <td><a class="kt-userpic kt-userpic--circle kt-margin-r-5 kt-margin-t-5" data-toggle="kt-tooltip" data-placement="right"><img src="{{ $item->logo_path }}"></a></td>
                    <td style="display: flex;">
                      @foreach (getActiveLanguages() as $language)
                        <a href="{{ route('dashboard.lessons.edit', ['id' => $item->id, 'language' => $language->alies ] )}}" class="btn btn-sm btn-warning">
                          {{ $language->alies }}
                        </a>
                      @endforeach
                    </td>
                    <td>
                      <form action="{{ route('dashboard.lessons.status',['id' => $item->id ]) }}" onsubmit="ajaxForm(event,this,'dt_dv','er_dv','');" method="post">
                          {{ csrf_field() }}
                          <input type="hidden" id="_method" name="_method" value="PUT">
                          <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                            <label><input type="checkbox"  {{ $item->is_active ? 'checked' : '' }}  onclick="submitForm(this);"><span></span></label>
                          </span>
                      </form>
                    </td>
                    <td>{{ $item->deleted_at }}</td>
                    <td>
                  <div id="action_div" class="dt_action_div">
                    <!-- <div><x-buttons.but_edit link="{{ route('dashboard.lessons.edit' , [ 'id' => $item->id ] ) }}" icon='true'/></div> -->
{{--                    <div><x-buttons.but_delete_inline link="{{ route('dashboard.lessons.destroy') }}" ids="{{ $item->id }}" icon='true'/></div>--}}
                        <button class="btn btn-delete">
                            <a href="{{ route('dashboard.lessons.delete_item', $item->id ) }}"><i class="fa fa-trash"></i></a>
                        </button>
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
