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
                    <h2> {{ $site->name }} </h2>
{{--                    <a class="btn btn-success pull-right" href="{{ route('dashboard.courses.create',$site->id) }}"> @lang('core.add') </a>--}}
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">

                    @include('back.includes.breadcrumb',['routes' => [
                        ['name' => $site->name],
                    ]])
                    <hr>


                    <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                      <thead>
                        <tr>
                          <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                              <th class="text-center">#</th>
                              <th>Name</th>
                              <td>{{ __('trans.semester') }}</td>
                              <th>link</th>
                              <th>{{ __('words.language') }}</th>
                              <th>Status</th>
                              <th style="text-align: center">Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                      @foreach($result as $item)

                         <tr id="{{ $item->id }}">
                            <td value="{{ $item->id }}"></td>
                            <td class="text-center"> {{ $item->id }} </td>
                            <td><img src="{{ url($item->ImageDetailsPath) }}" class="img-thumbnail img-responsive" width="50" alt=""><p>{{ $item->name }}</span></td>
                            <td>{{ $item->getTirm()?->name }}</td>
                            <td>
                                @if($item->link != null )
                                  https://www.baldatayiba.com/ar/prizes_subscribe_from_outside/{{ $item->link }}/zoom
                                  <br>{{ date($item->link_ended) }}<br>
                                  <span style="color: green;">short:</span> {{ route('courses.short_link', ['lang' => 'ar','alias' => $item->pivot->short_link]) }}
                                @endif
                            </td>
                            <td style="display: flex;">
                              @foreach (getActiveLanguages() as $language)
                                <a href="{{ route('dashboard.courses.edit', ['site' => $site->id, 'course' => $item->id, 'language' => $language->alies ] )}}" class="btn btn-sm btn-warning">
                                  {{ $language->alies }}
                                </a>
                              @endforeach
                            </td>

                            <td class="text-center {{ $item->status == 0 ? 'text-warning' : 'text-success' }}"> {{ $item->status == 0 ? 'Disabled' : 'Enabled' }} </td>
                            <td style="text-align: center">
                                @if ($item->status == 0)
                                    <form action="{{ route('dashboard.courses.status', ['site' => $site->id, 'course' => $item->id]) }}" method="post" class='form-horizontal'>
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="status" value="{{ !$item->status }}">
                                        <button type="submit" class="btn btn-sm btn-success"> @lang('core.enable') </button>
                                        <a class="btn btn-sm btn-info" href="{{ route('dashboard.courses.questions.edit',['site' => $site->id,'course' => $item->id]) }}"> @lang('meta.title.questions') </a>
                                    </form>
                                    <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete{{$item->id}}"> @lang('core.delete') </a>
                                @elseif($item->status == 1)
                                    <form action="{{ route('dashboard.courses.status', ['site' => $site->id, 'course' => $item->id]) }}" method="post">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="status" value="{{ !$item->status }}">
                                        <button type="submit" class="btn btn-sm btn-warning"> @lang('core.disable') </button>
                                        <a class="btn btn-sm btn-success" href="{{ route('dashboard.sender.index',['site' => $site->id,'course' => $item->id]) }}"> @lang('meta.title.sender') </a>
                                        <a class="btn btn-sm btn-info" href="{{ route('dashboard.courses.questions.edit',['site' => $site->id,'course' => $item->id]) }}"> @lang('meta.title.questions') </a>

                                        <a class="btn btn-sm btn-primary" href="{{ route('dashboard.test_results.index',['site' => $site->id,'course' => $item->id]) }}"> @lang('meta.title.test_results') </a>

                                        <a class="btn btn-sm btn-info" href="{{ route('dashboard.subscribers.index',['site' => $site->id,'course' => $item->id]) }}"> @lang('meta.title.subscribers') </a>
                                    </form>
                                @else

                                @endif

                                <form action="{{ route('dashboard.courses.updatelink') }}" method="post">
                                  @csrf
                                  <input type="hidden" name="site" value="{{ $site->id }}">
                                  <input type="hidden" name="course" value="{{ $item->id }}">
                                  <input class="form-control" type="datetime-local" name="link_ended" value="{{ date($item->link_ended) }}">
                                  <button class="btn btn-sm btn-info">@if(!$item->link) انشاء رابط @else تعديل التاريخ @endif</button>
                                </form>

                                <!--  courses.updatelink -->


                                <!-- modal for deleting only -->
                                <div class="modal fade" id="delete{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="delete{{$item->id}}">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="delete{{$item->id}}">Confirm Message!</h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('dashboard.courses.destroy', ['site' => $site->id, 'course' => $item->id] ) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="form-group">
                                                        <h4 class="text-danger">Do you sure to delete <span class="text-info"> {{ $item->title }} </span> data?</h4>
                                                        <input hidden="" name="name" value="{{$item->id}}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-default">Yes</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                                </div>
                                              </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end modal for deleting only -->
                            </td>
                          </tr>

                        @endforeach
                    </table>



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
