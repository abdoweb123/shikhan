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
                    <h2> {{ $course->name.' | '.__('meta.title.sender') }} </h2>
                    <a class="btn btn-success pull-right" href="{{route('dashboard.sender.create',['site' => $site->alias,'course' => $course_id])}}"> @lang('core.add') </a>
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
                        ['name' => $course->name.' | '.__('meta.title.sender')]]
                    ])
                    <hr>
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                        <tr>
                            <td class="text-center">#</td>
                            <td>Frequency</td>
                            <td class="text-center">Languages</td>
                            <td class="text-center">count</td>
                            <td class="text-center">Status</td>
                            <td style="text-align: center">Actions</td>
                        </tr>
                        @foreach($result as $row)
                            @php
                                $langs = '';
                                foreach ($row->languages as $lang) {
                                    $langs .= '<span class="label label label-info">'.$languages[$lang].'</span>';
                                }
                            @endphp
                            <tr>
                                <td class="text-center"> {{ $row->id }} </td>
                                <td> {{ $row->frequency }} </td>
                                <td class="text-center"> {!! $langs !!} </td>
                                <td class="text-center"> {{ $row->count }} </td>
                                <td  class="text-center {{ $row->status == 0 ? 'text-warning' : 'text-success' }}"> {{ $row->status == 0 ? 'Disabled' : 'Enabled' }} </td>
                                <td class="text-center">
                                    @if ($row->status == 0)
                                        {!! Form::model($row, ['method' => 'PUT','route' => ['dashboard.sender.status',$site->alias,$course_id,$row->id,'class'=>'form-horizontal']]) !!}
                                            <input type="hidden" name="status" value="{{ !$row->status }}">
                                            <button type="submit" class="btn btn-sm btn-success"> @lang('core.enable') </button>
                                            <a class="btn btn-sm btn-primary" href="{{ route('dashboard.sender.edit',['site' => $site->alias,'course' => $course_id,'sender' => $row->id]) }}"> @lang('core.edit') </a>
                                            <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete{{$row->id}}"> @lang('core.delete') </a>
                                        </form>
                                    @elseif($row->status == 1)
                                        {!! Form::model($row, ['method' => 'PUT','route' => ['dashboard.sender.status',$site->alias,$course_id,$row->id]]) !!}
                                            <input type="hidden" name="status" value="{{ !$row->status }}">
                                            <button type="submit" class="btn btn-sm btn-warning"> @lang('core.disable') </button>
                                        </form>
                                    @else
                                    @endif
                                    <!--  -->

                                    <!-- modal for deleting only -->
                                    <div class="modal fade" id="delete{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="delete{{$row->id}}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                {{ Form::open(['method' => 'DELETE', 'route' => ['dashboard.sender.destroy',$site->alias,$course_id,$row->id]]) }}
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
                        {{ $result->appends([])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
