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
    </style>
@endsection

@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="kt-portlet">


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
              <form action="{{ route('dashboard.users.info') }}" method="get">
                  <div class="input-group">
                      <input type="text" name="term" value="{{ @$get['term'] }}" class="form-control" placeholder="الاسم او البريد او الهاتف">
                      <div class="input-group-btn">
                          <button class="btn btn-default" type="submit">
                              <i class="glyphicon glyphicon-search"></i>
                          </button>

                      </div>
                  </div>
              </form>
          </div>

      </div>
      <hr>





      <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
        <thead>
            <tr>
                <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                <th class="text-center">#</th>
                <th>الاسم</th>
                <th>البريد</th>
                <th>الهاتف</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>نتائج الاختبارات</th>
                <th>الشهادات</th>
                <th>Reset Password</th>
                <th>created_at</th>
            </tr>
        </thead>
        <tbody>
              @php $langs = ''; @endphp
              @foreach($result as $row)
                <tr id="{{ $row->id }}">
                      <td value="{{ $row->id }}"></td>
                      <td class="text-center">{{ $row->id }}</td>
                      <td>
                          <img src="{{ $row->avatar_path }}" class="img-circle" width="30" height="30" alt="">{{ $row->name }}
                          <form action="{{ route('dashboard.login_user') }}" method="post" target="_blank">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $row->id }}">
                            <input type="submit" value="دخول حساب الطالب">
                          </form>
                          <a href="{{ route('dashboard.members.edit',['member' => $row->id]) }}" class="btn btn-sm btn-warning">edit</a>
                          <a href="{{ route('dashboard.user.show_extra_trays',['member' => $row->id]) }}" class="btn btn-sm btn-warning">محاولات جديدة</a>
                      </td>
                      <td><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
                      <td><a href="tel:{{ $row->phone }}">{{ $row->phone }}</a></td>

                      <td class="text-center">
                        @php $params = ['userId' => $row->id, 'detailsType' => 'USER_COURSES']; @endphp
                        <button onclick='getUserDetails( @json($params) );' type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">الاختبارات</button>
                      </td>

                      <td class="text-center">
                        @php $params = ['userId' => $row->id, 'detailsType' => 'USER_COMPARE_COURSES']; @endphp
                        <button onclick='getUserDetails( @json($params) );' type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">مقارنة النتائج</button>
                      </td>

                      <td class="text-center">
                        @php $params = ['userId' => $row->id, 'detailsType' => 'USER_SUBSCRIPTIONS']; @endphp
                        <button onclick='getUserDetails( @json($params) );' type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">الاشتراكات</button>
                      </td>

                      <td class="text-center">
                        @php $params = ['userId' => $row->id, 'detailsType' => 'USER_COURSES_DOESNT_SUBSCRIPE']; @endphp
                        <button onclick='getUserDetails( @json($params) );' type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">دورات لم يشترك فيها</button>
                      </td>

                      <td class="text-center">
                        @php $params = ['userId' => $row->id, 'detailsType' => 'USER_COURSES_ACTIVE_NOT_TESTED']; @endphp
                        <button onclick='getUserDetails( @json($params) );' type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">دورات لم يختبرها</button>
                      </td>


                      <td class="text-center">
                        @foreach ($row->test_results as $key => $test_result)
                          @php $params = ['userId' => $row->id, 'course_test_result_id' => $test_result->id, 'detailsType' => 'USER_TEST_RESULT_ANSWERS']; @endphp
                          @if ($test_result->course)
                            <button onclick='getUserDetails( @json($params) );' type="button" title="{{ $test_result->course->name }}"
                                style="padding: 3px;" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">{{ $key }}</button>
                          @endif
                        @endforeach
                      </td>



                      <td class="text-center">
                        @foreach ($row->test_results as $key => $test_result)
                        @if ($test_result->course)
                          @foreach ($test_result->course->sites as $site)

                            &nbsp;تحميل&nbsp;
                            <a data-href="{{ route('dashboard.certificates-show', ['id' => $test_result->id.'-'.$site->id, 'type' => 'jpg', 'user_id' => $test_result->user_id]) }}"
                              class="download_image btn btn-outline-success">
                              <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;صورة&nbsp;</i>
                            </a>
                            <a href="{{ route('dashboard.certificates-show', ['id' => $test_result->id.'-'.$site->id, 'type' => 'pdf', 'user_id' => $test_result->user_id]) }}"
                              class="btn btn-outline-success" style="">
                              <i class="fa fa-file-pdf" style="font-size: 13px;padding-left: 1px;padding-right: 1px;color: black;">&nbsp;بى دى اف&nbsp;</i>
                            </a>

                          @endforeach
                        @endif
                        @endforeach
                      </td>
                      <td>
                        <form action="{{ route('dashboard.user.reset.password') }}" method="post">
                          @csrf
                          <input type="hidden" name="user_id" value="{{ $row->id }}">
                          <input type="submit" value="Reset Password">
                        </form>
                      </td>
                      <td> {{$row->created_at}}</td>
                      <td class="text-center {{ $row->status == 0 ? 'text-warning' : 'text-success' }}">
                         <form action="{{ route('dashboard.change_user_status') }}" method="post">
                           @csrf
                           <input type="hidden" name="user_id" value="{{ $row->id }}">
                           <input type="submit" value="{{ $row->deleted_at ? 'Enable' : 'Disable' }} ">
                         </form>
                       </td>
                  </tr>
              @endforeach
        </tbody>
      </table>



    </div>
  </div>
</div>

{{ $result->withQueryString()->links() }}





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
<script>
  // datatable settings
  dt1_display_search_input_columns_values = [];
  dt1_display_search_droplist_columns_values = [];
</script>

<x-admin.datatable.footer-js-full/>
<x-buttons.but_delete_inline_js/>

<x-get-user-courses-details/>

<script>
function submitForm(me)
{
$(me).closest("form").submit();
}
</script>

@endsection
