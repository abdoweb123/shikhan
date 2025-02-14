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
    div#kt_table_1_filter {
    float: left;
    width: auto !important;
    margin: 0 5px !important;
}
    </style>
@endsection
@section('content')
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
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">

                <div class="x_title">
                    <h2> {{ $course->name.' | '.__('meta.title.subscribers') }} </h2>
                    <a class="btn btn-success pull-right" href="{{route('dashboard.subscribers.create',['site' =>$site->alias,'course' => $course_id])}}"> @lang('core.add') </a>
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
                    @include('back.includes.breadcrumb',['routes' => [
                        ['slug' => route('dashboard.courses.index',$site->alias),'name' => $site->name],
                        ['name' => $course->name.' | '.__('meta.title.subscribers')]]
                    ])
                    <hr>
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                      <thead>
                            <tr>
                              <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                                <td class="text-center">#</td>
                                <td>Name</td>
                                <td>complete quiz</td>
                                <td>Email</td>
                                <td>Phone</td>
                                <td>Date</td>
                                <td class="text-center">Actions</td>
                            </tr>
                      </thead>
                      <tbody>
                        @foreach($result as $row)
                        <tr id="{{ $row->id }}">
                            <td value="{{ $row->id }}"></td>
                                <td class="text-center"> {{ $row->id }} </td>
                                <td> {{ $row->name }} </td>
                                <td> {{$row->tests()->where('course_id',$course_id)->first()!=null ? 'yes' :'No'}} </td>
                                <td> <a href="mailto:{{ $row->email }}"> {{ $row->email }} </a> </td>
                                <td> <a href="tel:{{ $row->phone }}"> {{ $row->phone }} </a> </td>
                                <td> {{ $row->created_at }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete{{$row->id}}"> @lang('core.delete') </a>

                                    <!-- modal for deleting only -->
                                    <div class="modal fade" id="delete{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="delete{{$row->id}}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                {{ Form::open(['method' => 'DELETE', 'route' => ['dashboard.subscribers.destroy',$site->alias, $course_id,$row->id]]) }}
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="delete{{$row->id}}">Confirm Message!</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <h4 class="text-danger">Do you sure to delete <span class="text-info">{{$row->name}}</span> data?</h4>
                                                        <input hidden="" name="name" value="{{$row->id}}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-default">Yes</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                                </div>
                                                {{ Form::close() }}
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end modal for deleting only -->
                                </td>
                            </tr>

                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
            {!! $result->links()!!}
        </div>
    </div>

@stop
@section('js_pagelevel')

<script>
  // datatable settings
  dt1_display_search_input_columns_values = [];
  dt1_display_search_droplist_columns_values = [];
</script>

<x-admin.datatable.footer-js-full/>
<x-buttons.but_delete_inline_js/>
<script>
function submitForm(me)
{
$(me).closest("form").submit();
}
</script>

@endsection
