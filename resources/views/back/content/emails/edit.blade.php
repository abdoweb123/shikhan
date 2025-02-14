@extends('back/layouts.app')

@section('content')

<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h2> ارسال بريد الكترونى </h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @if(session()->has('success'))
                        <div class="alert alert-success text-center">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    @include('back.includes.breadcrumb',['routes' => [
                        ['slug' => route('dashboard.members.index'),'name' => __('meta.title.members')],
                        ['name' => __('core.add')]]
                    ])
                    <hr>

                    <div class="clearfix"></div>
                    <div class="row">
                      Count : {{ $data->total() }}
                      <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                        <thead>
                          <tr>
                                <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                                <th>Title</th>
                                <th>Sent Count</th>
                                <th>Data</th>
                                <th>Active</th>
                                <th>Edit</th>
                                <th>Done</th>
                            </tr>
                        </thead>
                        <tbody>
                          @foreach($data as $row)

                            <tr id="{{ $row->id }}">
                                <td value="{{ $row->id }}">{{ $row->id }}</td>
                                <td> {{ $row->title }} </td>
                                <td> {{ $row->sent_count }} </td>
                                <td> {{ $row->site_title ?? '' ? $row->site_title : '' }} {{ $row->data }}</td>
                                <td>
                                  {{ $row->is_active ? "active" : 'In Active' }}
                                  @if($row->status == 1)
                                  <form method="post" action="{{ route('dashboard.send_emails.update.status') }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                    <input type="hidden" name="value" value="{{ $row->is_active ? 0 : 1 }}">
                                    <input type="submit" class="btn {{ $row->is_active ? 'btn-success' : 'btn-primary' }}" value="{{ $row->is_active ? 'stop' : 'start' }}">
                                  </form>
                                  @endif
                                </td>
                                <td> <a href="{{ route('dashboard.send_emails.edit.details', [ 'id' => $row->id ]) }}" class="btn btn-info">Edit</a> </td>
                                <td> {{ $row->status == 0 ? 'Done' : 'Not Done'}} </td>
                            </tr>

                          @endforeach
                      </table>

                      {{ $data->appends(Request::except(['message','_token']))->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>





</div>

@stop
