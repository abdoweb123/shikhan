@extends('back/layouts.app')
@section('back_css')
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
  <x-admin.datatable.header-css/>
@endsection
@section('content')

<div class="row">

    <div class="col-12">
      <div class="kt-portlet kt-portlet--mobile">


        <div class="kt-portlet__body">

        </div>
      </div>
    </div>



</div>

@endsection
@section('js_pagelevel')
<x-admin.datatable.footer-js/>
<x-buttons.but_delete_inline_js/>
@endsection
