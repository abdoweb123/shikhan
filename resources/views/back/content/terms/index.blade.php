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
             <x-buttons.but_new link="{{ route('dashboard.terms.create') }}"/>
          </div>
        </h3>
      </div>
    </div>
  </div>

  @include('back.includes.page-alert')

  <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">

      <style>
        .dataTables_wrapper div.dataTables_filter { display: contents; }
      </style>


      <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
        <thead>
          <tr>
            <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
            <th>ID</th>
            <th>{{ __('words.name') }}</th>
            <th>{{ __('words.language') }}</th>
            <th>{{ __('words.active_status') }}</th>
            <th>{{ __('words.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($terms as $item)

              <?php
              $termTranslation = App\Translations\TermTranslation::query()->where('locale',app()->getLocale())->where('term_id',$item->id)->first();
              ?>

            <tr id="{{ $item->id }}">
                <td value="{{ $item->id }}"></td>
                <td>{{ $item->id }}</td>
                <td>{{ isset($termTranslation) ? $termTranslation->name : $item->title }}</td>
                <td style="display: flex;">
                  @foreach (getActiveLanguages() as $language)
                    <a href="{{ route('dashboard.terms.edit', ['id' => $item->id, 'language' => $language->alies ] )}}" class="btn btn-sm btn-warning">
                      {{ $language->alies }}
                    </a>
                  @endforeach
                </td>
                <td>
                  <form action="{{ route('dashboard.terms.status',['id' => $item->id ]) }}" onsubmit="ajaxForm(event,this,'dt_dv','er_dv','');" method="post">
                      {{ csrf_field() }}
                      <input type="hidden" id="_method" name="_method" value="PUT">
                      <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                        <label><input type="checkbox"  {{ $item->status ? 'checked' : '' }}  onclick="submitForm(this);"><span></span></label>
                      </span>
                  </form>
                </td>
                <td>
                  <div id="action_div" class="dt_action_div">
                    <div><x-buttons.but_delete_inline link="{{ route('dashboard.terms.destroy' ) }}" ids="{{ $item->id }}" icon='true'/></div>
                  </div>
                </td>
            </tr>
          @endforeach
        </tbody>
      </table>


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
