<?php
$counter =1;
?>
@extends('back/layouts.app')
@section('back_css')
  <x-admin.datatable.header-css/>
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
        .thumbnail{
            position: relative;
        }
        .card-img{
            /* max-width: 100%!important;
            height: 100%!important;
            width: auto!important; */

            position: absolute;
            top: 50%;
            left: 50%;
           margin-top: 10px;
            transform: translate(-50%, -50%);
        }
        .title-site {
            height: 100%;
            width: 100%;
            background: #f5f5f5;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            top: 0;
        }
    </style>
    <div class="row">
    <div class="col-12">
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
                <th>{{ __('words.Category') }}</th>
                <th>{{ __('words.name') }}</th>
                <th>{{ __('words.count') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($courses as $item)
                  <tr id="{{ $item->id }}">
                      <td value="{{ $item->id }}"></td>
                      <td> @foreach ($item->sites as $site)
                             {{$site->name}}
                            @if(!$loop->last)-  @endif
                            @endforeach
                        </td>
                        <td> {{$item->title}} </td>
                      <td> {{$item->subscribers_count}}  </td>
                  </tr>
              @endforeach
            </tbody>
          </table>

          <!--end: Datatable -->
        </div>
      </div>
    </div>


        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">

                <div class="x_title">
                    <h2> Diplomas </h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">

                    @if(session()->has('success'))
                        <div class="alert alert-success text-center">
                            {{ session()->get('success') }}
                        </div>
                        <hr>
                    @elseif(session()->has('MasterErorr'))
                        <div class="alert alert-danger text-center">
                            <strong> Failed!  </strong> {{ session()->get('MasterErorr') }}
                        </div>
                        <hr>
                    @endif
                    <div class="row">
                        @foreach ($result as $row)
                            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                                <a href="{{ route('dashboard.courses.index',$row->alias) }}" class="thumbnail" title="{{ $row->name }}">
                                    <img src="{{ url($row->logo_path) }}" class="card-img" alt="{{ $row->name }}" title="{{ $row->name }}">
                                    <div class="title-site">{{ $row->name }}</div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('js_pagelevel')
<x-admin.datatable.footer-js/>
<x-buttons.but_delete_inline_js/>
@endsection
