<?php
$counter =1;
?>
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
      .dataTables_filter {
      width: 50%;
      float: left !important;
      margin: 0 5px;
      width: auto !important;
      text-align: right;
      }
    </style>

    <style media="screen">
        .label{
            margin: 0 1px;
            display: inline-block;
            min-width: 10px;
            padding: 3px 7px;
            font-size: 12px;
            white-space: nowrap;
            vertical-align: middle;
            border-radius: 10px;
        }
    </style>

@endsection
@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">


          <div class="x_title">
              <h2>
                {{ request()->translation ?? '' }}
                @foreach ($files as $file)
                  @if (request()->query('file') == $file)
                    - {{ $file }}
                  @endif
                @endforeach
              </h2>
              {{--<!-- <a class="btn btn-success pull-right" href="{{ route('dashboard.courses.create',$site->id) }}"> @lang('core.add') </a> -->--}}
              <div class="clearfix"></div>
          </div>


          <div class="x_content">

              @include('back.includes.page-alert')
              {{--
                @if(session()->has('success'))
                    <div class="alert alert-success text-center">
                        {{ session()->get('success') }}
                    </div>
                @elseif(session()->has('MasterErorr'))
                    <div class="alert alert-danger text-center">
                        <strong> Failed!  </strong> {{ session()->get('MasterErorr') }}
                    </div>
                @endif
              --}}

              {{--
              @include('back.includes.breadcrumb',['routes' => [
                  ['name' => $site->name],
              ]])
              --}}
              <hr>

              <span style="padding: 0px 5px 0px 0px;">Languages : </span>
              @foreach (getActiveLanguages() as $language)
                <a href="{{ route('dashboard.translations.edit', ['translation' => $language->alies] )}}"
                  class="{{ (request()->translation == $language->alies) ? 'btn btn-info' : 'btn btn-sm btn-warning' }}">
                  {{ $language->name }}
                </a>
              @endforeach

              <span style="padding: 0px 5px 0px 54px;">Files : </span>
              @foreach ($files as $file)
                <a href="{{ route('dashboard.translations.edit', ['translation' => request()->translation, 'file' => $file] )}}"
                  class="{{ (request()->file == $file) ? 'btn btn-info' : 'btn btn-sm btn-warning' }}">
                  {{ $file }}
                </a>
              @endforeach


              @if (isset($data))
              <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">

                  <style> .dataTables_wrapper div.dataTables_filter { display: contents; }</style>

                  <form action="{{ route('dashboard.translations.update',['translation' => request()->translation, 'file' => request()->query('file') ]) }}" method="post">
                      {{ method_field('PUT') }}
                      {{ csrf_field() }}

                      <table class="table table-striped- table-bordered table-hover table-checkable"><!-- id="kt_table_1" -->
                        <thead>
                          <tr>
                            <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                            <th>{{-- __('words.name') --}}</th>
                            <th>{{-- __('words.language') --}}</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($data as $key => $value)
                            <tr id="{{ $key }}">
                                <td></td>
                                <td>{{ $key }}</td>
                                <td>
                                  @if (is_array($value))
                                    @foreach ($value as $keySub => $valueSub)
                                      <input name="data[{{$key}}][{{$keySub}}]" value='{{ old("trans[$key][$keySub]", $valueSub) }}' style="width: 600px;">
                                    @endforeach
                                  @else
                                    <input name="data[{{$key}}]" value='{{ old("trans[$key]", $value) }}' style="width: 600px;">
                                  @endif
                                </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>

                      <x-buttons.but_submit/>
                      <br><br><br>

                  </form>

                </div>
              </div>
              @endif



          </div>
      </div>
    </div>
  </div>

@stop
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
