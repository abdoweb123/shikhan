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
    </style>
@endsection

@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="kt-portlet">

    <div class="kt-portlet__head">
      <div class="kt-portlet__head-label">
        <h3 class="kt-portlet__head-title">
          <div class="row" style="display: inline-flex;">
             <x-buttons.but_new link="{{ route( 'dashboard.lessons.create' ) }}"/>
            <x-buttons.but_delete link='{{ route("dashboard.lessons.destroy") }}'/>
          </div>
        </h3>

      </div>
    </div>


    <!--begin::Form-->
    <!--end::Form-->
  </div>

  <div class="kt-portlet kt-portlet--mobile">
    <div class="kt-portlet__body">

      <style> .dataTables_wrapper div.dataTables_filter { display: contents; } </style>
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
          ['name' => __('meta.title.members')],
      ]])

      <div class="col-md-12 row">

          <div class="col-md-4">
              <form action="{{ route('dashboard.members.index') }}" method="get">
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

          {{--
          <div class="col-md-3">
              <!-- create randome user password -->
              <form action="create_users_passwords" method="post">
                  @csrf
                  <button type="submit" class="btn btn-default">Create passwords for all users</button>
              </form>
          </div>

          <div class="col-md-3">
             <!-- create randome user password -->
              <form action="send_users_passwords" method="post">
                  @csrf
                  <button type="submit" class="btn btn-default">Send passwords to all users</button>
              </form>
          </div>
          --}}

      </div>
      <hr>






      <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
        <thead>
            <tr>
                  <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                  <th class="text-center">#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Courses</th>
                  <th>not Subscriptions</th>
                  <th>count Courses</th>
                  <th>count not Subscriptions</th>
                  <th>count tested</th>
                  <th>count not tested</th>
                  <th>join at</th>
                  <th>Status</th>
                  <th style="text-align: center"> @lang('core.actions') </th>
              </tr>
            </thead>
            <tbody>
              @php $langs = ''; @endphp
              @foreach($result as $row)
                @php
                    $notSubscriptions=  DB::table('courses')->whereNULL('deleted_at')->whereNotIn('id',$row->courses->pluck('id'))->select('courses.title')->get();
                @endphp
                <tr id="{{ $row->id }}">
                      <td value="{{ $row->id }}"></td>
                      <td class="text-center"> {{ $row->id }} </td>
                      <td>
                          <img src="{{ url($row->avatar_path) }}" class="img-circle" width="30" height="30" alt="">
                          {{ $row->name }}
                      </td>
                      <td> <a href="mailto:{{ $row->email }}"> {{ $row->email }} </a> </td>
                      <td> <a href="tel:{{ $row->phone }}"> {{ $row->phone }} </a> </td>
                      <td>@foreach($row->courses as $memberCourse) {{ $memberCourse->title }} --  @endforeach</td>
                      <td>@foreach($notSubscriptions as $Course) {{ $Course->title }} --  @endforeach</td>
                      <td>{{$row->courses->count()}}</td>
                      <td>{{$notSubscriptions->count()}}</td>
                      <td>{{$row->test_results->unique('course_id')->count()}}</td>
                      <td>@php echo $row->courses->count() - $row->test_results->unique('course_id')->count() ;@endphp</td>
                      <td> {{$row->created_at}}</td>
                      <td class="text-center {{ $row->status == 0 ? 'text-warning' : 'text-success' }}"> {{ $row->status == 0 ? 'Disabled' : 'Enabled' }} </td>
                      <td style="text-align: center;display: flex;">
                          @if ($row->status == 0)
                              {!! Form::model($row, ['method' => 'PUT','route' => ['dashboard.members.status', $row->id,'class'=>'form-horizontal']]) !!}
                                  <input type="hidden" name="status" value="{{ !$row->status }}">
                                  <button type="submit" class="btn btn-sm btn-success"> @lang('core.enable') </button>
                                  <a class="btn btn-sm btn-primary" href="{{ route('dashboard.members.edit',$row->id) }}"> @lang('core.edit') </a>
                                  <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete{{$row->id}}"> @lang('core.delete') </a>
                              </form>
                          @elseif($row->status == 1)
                              {!! Form::model($row, ['method' => 'PUT','route' => ['dashboard.members.status', $row->id]]) !!}
                                  <input type="hidden" name="status" value="{{ !$row->status }}">
                                  <button type="submit" class="btn btn-sm btn-warning"> @lang('core.disable') </button>
                              </form>
                          @else
                          @endif

                          <!-- modal for deleting only -->
                          <div class="modal fade" id="delete{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="delete{{$row->id}}">
                              <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                          <h4 class="modal-title" id="delete{{$row->id}}">Confirm Message!</h4>
                                      </div>
                                      <div class="modal-body">
                                          {{ Form::open(['method' => 'DELETE', 'route' => ['dashboard.members.destroy', $row->id]]) }}
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
                          <!-- create randome user password -->
                          <a href="{{ route('dashboard.members.edit',['member'=>1]) }}" class="btn btn-sm btn-warning">edit</a>
                      </td>
                  </tr>
              @endforeach
            </tbody>
      </table>
      {!! $result->links()!!}






    </div>
  </div>
</div>





<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body" id="modal-body-div">
        <p> </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


@endsection








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
