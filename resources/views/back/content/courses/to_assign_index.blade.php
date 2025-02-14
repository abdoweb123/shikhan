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



        <hr>
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
          <thead>
            <tr>
              <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                <th class="text-center">#</th>
                <th>Name</th>
                <th style="text-align: center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($result as $row)
            <tr id="{{ $row->id }}">
              <td value="{{ $row->id }}"></td>
              <td class="text-center"> {{ $row->id }} </td>
              <td>{{ $row->name }}</td>
              <td style="display:flex;">

                <!-- category modal -->
                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#category_{{$row->id}}">الفصول الدراسية</button>
                <div id="category_{{$row->id}}" class="modal fade" role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">الفصول الدراسية</h4>
                          <input type="text" id="Search_{{$row->id}}" onkeyup="myFunction({{$row->id}})" placeholder="Please enter a search term.." title="Type in a name">
                      </div>
                      <div class="modal-body" style="overflow-y: auto;max-height: 394px;text-align: right;font-size: 18px;direction: rtl;">
                        <form class="" action="{{route('dashboard.courses.to_assign_index.post')}}" method="post">
                            @csrf
                            <input type="hidden" name="course_id" value="{{$row->id}}">
                            @foreach($sitesTree as $site)
                                <input type="hidden" name="site_id" value="{{$site->id}}">
                              <div>{{ str_repeat("...", $site->depth) }} {{$site->name}}</div>
                                @foreach($site->terms as $term)
                                  <div class="target_{{$row->id}}" style="padding: 0px 17px;">
{{--                                    <input type="checkbox" name="site_id[{{$site->id}}][]" value={{$term->id}}--}}
{{--                                    <input type="checkbox" name="term_id[]" value={{$term->id}}--}}
{{--                                        @foreach ($row->terms as $course_term)--}}
{{--                                          @if ($course_term->id == $term->id) checked @endif--}}
{{--                                        @endforeach--}}
{{--                                    >--}}
                                      <input type="radio" name="term_id[]" value={{$term->id}}
                                      @foreach ($row->terms as $course_term)
                                      @if ($course_term->id == $term->id) checked @endif
                                          @endforeach
                                      >
                                    {{ $term->name }}
                                    <br>
                                  </div>
                                @endforeach
                            @endforeach
                            <input type="submit"  class="btn btn-success" value="حفظ">
                        </form>

                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                  <a href="#" class="btn btn-info btn-lg">إضافة اختبار</a>
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
<script>

function myFunction(id) {
  var input = document.getElementById("Search_"+id);
  var filter = input.value.toLowerCase();
  var nodes = document.getElementsByClassName('target_'+id);

  for (i = 0; i < nodes.length; i++) {
    if (nodes[i].innerText.toLowerCase().includes(filter)) {
      nodes[i].style.display = "block";
    } else {
      nodes[i].style.display = "none";
    }
  }
}

</script>
<x-admin.datatable.footer-js/>
<x-buttons.but_delete_inline_js/>
<script>
function submitForm(me)
{
  $(me).closest("form").submit();
}
</script>

@endsection
