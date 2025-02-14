<?php
$counter =1;
?>
@extends('back/layouts.app')

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
                    <h2> {{ $course->name.' | '.__('meta.title.test_results') }} </h2>
                    <a class="btn btn-success pull-right" href="{{route('dashboard.test_results.create',['site' =>$site->slug,'course' => $course_id])}}"> @lang('core.add') </a>
                    <a class="btn btn-success pull-right" href="{{ route('dashboard.test_results.export',['site' =>$site->slug,'course' => $course_id,'type' => 'xls']) }}"> <i class="glyphicon glyphicon-cloud-download"></i> xls</a>
                    <a class="btn btn-success pull-right" href="{{ route('dashboard.test_results.export',['site' =>$site->slug,'course' => $course_id,'type' => 'xlsx']) }}"><i class="glyphicon glyphicon-cloud-download"></i> xlsx</a>
                    <a class="btn btn-success pull-right" href="{{ route('dashboard.test_results.export',['site' =>$site->slug,'course' => $course_id,'type' => 'csv']) }}"><i class="glyphicon glyphicon-cloud-download"></i> CSV</a>
                    <a class="btn btn-success pull-right" href="{{ route('dashboard.test_results.export',['site' =>$site->slug,'course' => $course_id,'type' => 'html']) }}"><i class="glyphicon glyphicon-cloud-download"></i> HTML</a>
                    <a class="btn btn-success pull-right" href="{{ route('dashboard.test_results.export',['site' =>$site->slug,'course' => $course_id,'type' => 'xml']) }}"><i class="glyphicon glyphicon-cloud-download"></i> XML</a>
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
                        ['slug' => route('dashboard.courses.index',$site->slug),'name' => $site->name],
                        ['name' => $course->name.' | '.__('meta.title.test_results')]]
                    ])
                    <div class="col-md-12 row">
                        <div class="col-md-4">
                            <form action="{{ route('dashboard.test_results.index',['site' =>$site->slug,'course' => $course_id]) }}" method="get">
                                <div class="input-group">
                                    <input type="text" name="term" value="{{ @$get['term'] }}" class="form-control" placeholder="{{ __('core.term_p') }}">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            <i class="glyphicon glyphicon-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-5">
                            <form action="{{ route('dashboard.test_results.import',['site' =>$site->slug,'course' => $course_id]) }}" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="input-group">
                                    <input type="file" name="import_file" class="form-control">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            <i class="glyphicon glyphicon-cloud-upload"></i>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-muted"> Headings for file upload is ("email","name","phone","degree","locale") </p>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <a href="{{ route('dashboard.test_results.index',['site' =>$site->slug,'course' => $course_id]) }}" class="btn btn-info {{ url()->full() == route('dashboard.test_results.index',['site' =>$site->slug,'course' => $course_id]) ? 'active' : '' }}"> total : {{ $counts['total'] }} </a>
                        |
                        <a href="{{ route('dashboard.test_results.index',['site' =>$site->slug,'course' => $course_id,'flag' => 0]) }}" class="btn btn-default {{ request('flag') == '0' ? 'active' : '' }}"> Not sent : {{ $counts['not_send'] }} </a>
                        <a href="{{ route('dashboard.test_results.index',['site' =>$site->slug,'course' => $course_id,'flag' => 1]) }}" class="btn btn-danger {{ request('flag') == '1' ? 'active' : '' }}"> a problem happened : {{ $counts['error_send'] }} </a>
                        <a href="{{ route('dashboard.test_results.index',['site' =>$site->slug,'course' => $course_id,'flag' => 2]) }}" class="btn btn-primary {{ request('flag') == '2' ? 'active' : '' }}"> has been sent : {{ $counts['sended'] }} </a>
                        |
                        <a href="{{ route('dashboard.test_results.index',['site' =>$site->slug,'course' => $course_id,'certificate' => 0]) }}" class="btn btn-warning {{ request('certificate') == '0' ? 'active' : '' }}"> Not lucky : {{ $counts['not_lucky'] }} </a>
                        <a href="{{ route('dashboard.test_results.index',['site' =>$site->slug,'course' => $course_id,'certificate' => 1]) }}" class="btn btn-success {{ request('certificate') == '1' ? 'active' : '' }}"> have a certificate : {{ $counts['lucky'] }} </a>
                        <br>
                        @foreach ($languages as $alias => $name)
                            <a href="{{ route('dashboard.test_results.index',['site' =>$site->slug,'course' => $course_id,'locale' => $alias]) }}" class="btn btn-default {{ request('locale') == $alias ? 'active' : '' }}"> {{ $name.' : '.$counts['locale'][$alias] }} </a>
                        @endforeach
                    </div>
                    <div class="clearfix"></div>
                    <hr>

                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <tr>
                            <td class="text-center">#</td>
                            <td>Name</td>
                            <td>Email</td>
                            <td class="text-center">Degree</td>
                            <td class="text-center">Lang</td>
                            <td class="text-center">Status</td>
                            <td>
                                <p class="pull-left">
                                    Actions
                                </p>
                                <a class="btn btn-sm btn-success pull-right m-0" href="{{ route('dashboard.test_results.send_all',$get + ['site' =>$site->slug,'course' => $course_id]) }}">Send All</a>
                            </td>
                        </tr>
                        @foreach($result as $row)
                            <tr>
                                <td class="text-center"> {{ $row->id }} </td>
                                <td> {{ $row->member->name }} </td>
                                <td> <a href="mailto:{{ $row->member->email }}"> {{ $row->member->email }} </a> </td>
                                <td class="text-center"> <span class="label label-{{ $row->rate == '4' ? 'primary' : ($row->rate == '3' ? 'info' : ($row->rate == '2' ? 'success' : ($row->rate == '1' ? 'warning' : 'danger')))  }}"> {{ $row->degree }} </span> </td>
                                <td class="text-center"> <span class="label label-default"> {{ $languages[$row->locale] }} </span> </td>
                                <td class="text-center"> <span class="label label-{{ $row->flag == '2' ? 'primary' : ($row->flag == '1' ? 'danger' : 'default' ) }}"> {{ $row->flag == '2' ? 'has been sent' : ($row->flag == '1' ? 'a problem happened' : 'Not sent' ) }} </span> </td>
                                <td class="text-center">
                                    {{-- @if ($row->flag != '2' && $row->rate != '0') --}}
                                    @if ($row->rate != '0')
                                        <a class="btn btn-sm btn-success" href="{{ route('dashboard.test_results.send',['site' => $site->slug,'course' => $course_id,'test_result' => $row->id]) }}"> @lang('core.send') </a>
                                    @endif
                                    <a class="btn btn-sm btn-primary" href="{{ route('dashboard.test_results.edit',['site' => $site->slug,'course' => $course_id,'test_result' => $row->id]) }}"> @lang('core.edit') </a>
                                    <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete{{$row->id}}"> @lang('core.delete') </a>
                                    <!--  -->

                                    <!-- modal for deleting only -->
                                    <div class="modal fade" id="delete{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="delete{{$row->id}}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                {{ Form::open(['method' => 'DELETE', 'route' => ['dashboard.test_results.destroy',$site->slug, $course_id,$row->id]]) }}
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
                    </table>
                    <hr>
                    <div class="text-center">
                        {{ $result->appends(@$get)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
