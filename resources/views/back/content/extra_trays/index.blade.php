@extends('back/layouts.app')
@section('back_css')
  <x-admin.datatable.header-css/>
  <style>
    a.kt-userpic.kt-userpic--circle.kt-margin-r-5.kt-margin-t-5 img
    {
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
              <h2> {{ $member->name }} , {{ $member->email }} </h2>
              {{--<a class="btn btn-success pull-right" href="{{ route('dashboard.courses.create',$site->alias) }}"> @lang('core.add') </a>--}}
              <div class="clearfix"></div>
          </div>

          <div class="x_content">
            @if(session()->has('success'))
                <div class="alert alert-success text-center">
                    {{ session()->get('success') }}
                </div>
            @elseif(session()->has('MasterErorr'))
                <div class="alert alert-danger text-center">
                    <strong> Failed!  </strong> {{ session()->get('MasterErorr') }}
                </div>
            @endif

            @include('back.includes.breadcrumb', [
                'routes' => [
                  ['name' => $member->name],
                ]
            ])

            <hr>


            <div style="padding-bottom: 32px;">
              <form class="form-inline" action="{{ route('dashboard.user.store_extra_trays') }}" method="post" >
                @csrf
                <input type="hidden" name="user_id" value="{{ $member->id }}">
                <div class="form-group mb-2">
                  <label for="staticEmail2" class="sr-only">الدورة</label>
                  <select class="form-control select2_1"  name="course_id">
                    @foreach ($courses as $course)
                      <option {{ old('course_id') == $course->id ? 'selected' : '' }} value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                  <label for="inputPassword2" class="sr-only">عدد المحاولات</label>
                  <input type="number" name="trays" class="form-control" value="{{ old('trays') }}" >
                </div>
                <button class="btn btn-md btn-primary">اضافة فرصة</button>
              </form>

            </div>


            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                  <tr>
                      <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                      <th class="text-center">#</th>
                      <th>Name</th>
                      <th>Traies</th>
                      <th style="text-align: center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($extraTrays as $row)
                    <tr id="{{ $row->id }}">
                        <td value="{{ $row->id }}"></td>
                        <td class="text-center"> {{ $row->id }} </td>
                        <td>{{ $row->course ? $row->course->title : 'deleted' }}</td>
                        <td>{{ $row->trays }}</td>
                        <td style="text-align: center">
                          <form action="{{ route('dashboard.user.update_extra_trays') }}" method="post" style="display: flex;">
                            @csrf
                            <input type="hidden" name="extra_tray_id" value="{{ $row->id }}">
                            <input type="number" name="trays" value="{{ $row->trays }}" >
                            <button class="btn btn-sm btn-info">تعديل</button>
                          </form>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
            </table>

          </div>

      </div>
    </div>
  </div>

@stop
@section('js_pagelevel')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<x-admin.datatable.footer-js/>
<x-buttons.but_delete_inline_js/>
<script>
function submitForm(me)
{
  $(me).closest("form").submit();
}


$(document).ready(function() {
    $('.select2_1').select2();
});

</script>

@endsection
