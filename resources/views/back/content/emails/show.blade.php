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








        <form method="post" action="{{ route('dashboard.send_emails.index') }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
              {{ csrf_field() }}

              @foreach ( $queries as $query )
              <div class="form-group row" style="{{ ($query['alias'] == $currentQueryAlias) ? 'color: black;font-weight: bold;' : '' }}">
                <div class="col-md-12">
                  <div class="col-lg-12 col-md-12 col-sm-12">

                    <div class="col-lg-2">
                      {{ $query['title'] }}
                      @if($query['alias'] == $currentQueryAlias)
                        {{ $dataCount ? $dataCount->total() : '' }}
                      @endif
                    </div>

                    @if( $query['alias'] == 'UsersSubscribedButNotTestedEver')
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="from_date_not_tested" value="{{ old('from_date_not_tested', $defaultDateFrom) }}">
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="to_date_not_tested" value="{{ old('to_date_not_tested', $defaultDateTo) }}">
                          </div>
                          <div class="col-lg-3">
                            <select name="site_id_not_tested[]" class="form-control select_2" multiple="multiple">
                              @foreach ($sites as $siteRow)
                                <option {{ (  in_array($siteRow->id, old('site_id_not_tested',[]))  )  ? 'selected' : '' }}
                                  value="{{ $siteRow->id }}" >{{ $siteRow->title }}</option>
                              @endforeach
                            </select>
                          </div>
                    @endif


                    @if( $query['alias'] == 'UsersHasXCoursesToFinishDeiplom')
                          <div class="col-lg-2">
                            <select name="site_id_finish" class="form-control">
                              @foreach ($sites as $siteRow)
                                <option @if( old('site_id_finish') == $siteRow->id ) 'selected' @endif value="{{ $siteRow->id }}" >{{ $siteRow->title }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="number" name="more_than_x_courses_finish" value="{{ old('more_than_x_courses_finish') }}" placeholder="عدد الدورات الباقية">
                          </div>
                    @endif


                    @if( $query['alias'] == 'UsersRegisterdFromTo')
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="from_date_register" value="{{ old('from_date_register', $defaultDateFrom) }}">
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="to_date_register" value="{{ old('to_date_register', $defaultDateTo) }}">
                          </div>
                    @endif


                    @if( $query['alias'] == 'UsersDidntTestedEver')

                    @endif

                    @if( $query['alias'] == 'UsersDidntTestedFromTo')
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="from_date_didnt_tested" value="{{ old('from_date_didnt_tested', $defaultDateFrom) }}">
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="to_date_didnt_tested" value="{{ old('to_date_didnt_tested', $defaultDateTo) }}">
                          </div>

                          <div class="col-lg-3">
                            <select name="site_id_didnt_tested[]" class="form-control select_2" multiple="multiple">
                              @foreach ($sites as $siteRow)
                                <option
                                {{ (  in_array($siteRow->id, old('site_id_didnt_tested',[]))  )  ? 'selected' : '' }}
                                 value="{{ $siteRow->id }}" >{{ $siteRow->title }}</option>
                              @endforeach
                            </select>
                          </div>
                    @endif


                    @if( $query['alias'] == 'UsersTestedXCoursesAndSuccessed')
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="from_date_courses_successedd" value="{{ old('from_date_courses_successedd', $defaultDateFrom) }}">
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="to_date_courses_successedd" value="{{ old('to_date_courses_successedd', $defaultDateTo) }}">
                          </div>

                          <div class="col-lg-2">
                            <select name="site_id_successedd[]" class="form-control select_2" multiple="multiple">
                              @foreach ($sites as $siteRow)
                                <option
                                {{ (  in_array($siteRow->id, old('site_id_successedd',[]))  )  ? 'selected' : '' }}
                                value="{{ $siteRow->id }}" >{{ $siteRow->title }}</option>
                              @endforeach
                            </select>
                          </div>

                          <div class="col-lg-1">
                            <input class="form-control" type="number" name="more_than_x_courses_successedd" value="{{ old('more_than_x_courses_successedd') }}" placeholder="عدد الدورات">
                          </div>

                          <div class="col-lg-1" style="padding-top: 6px;">
                            <input type="checkbox" name="chk_success_courses_successedd" value="1" {{ old('chk_success_courses_successedd') == 1 ? 'checked' : '' }} ><label style="padding: 0px 6px;">ونجح</label>
                          </div>
                    @endif


                    @if( $query['alias'] == 'UsersTestedXCoursesAndNotTestedForPeriod')
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="from_date_period" value="{{ old('from_date_period', $defaultDateFrom) }}">
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="to_date_period" value="{{ old('to_date_period', $defaultDateTo) }}">
                          </div>
                          <div class="col-lg-2">
                            <select name="site_id_period" class="form-control">
                              @foreach ($sites as $siteRow)
                                <option @if( old('site_id_period') == $siteRow->id ) 'selected' @endif value="{{ $siteRow->id }}" >{{ $siteRow->title }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="number" name="more_than_x_courses_period" value="{{ old('more_than_x_courses_period') }}" placeholder="عدد الدورات المتبقية او اقل">
                          </div>
                    @endif


                    @if( $query['alias'] == 'UsersTestedOrSubscribedSitesAndNotTestedOrSubscribedOthers')
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="from_date_ts_in_not_in" value="{{ old('from_date_ts_in_not_in', $defaultDateFrom) }}">
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="to_date_ts_in_not_in" value="{{ old('to_date_ts_in_not_in', $defaultDateTo) }}">
                          </div>
                          <div class="col-lg-2">
                            <select name="test_or_subscribe_ts_in_not_in" class="form-control">
                              <option @if( old('test_or_subscribe_ts_in_not_in') == 'test' ) 'selected' @endif value="test" >اختبر</option>
                              <option @if( old('test_or_subscribe_ts_in_not_in') == 'subscribe' ) 'selected' @endif value="subscribe" >اشترك</option>
                              <option @if( old('test_or_subscribe_ts_in_not_in') == 'test_subscribe' ) 'selected' @endif value="test_subscribe" >اختبر واشترك</option>
                            </select>
                          </div>
                          <div class="col-lg-2">
                            <select name="site_id_ts_in" class="form-control">
                              @foreach ($sites as $siteRow)
                                <option @if( old('site_id_ts_in') == $siteRow->id ) 'selected' @endif value="{{ $siteRow->id }}" >{{ $siteRow->title }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-lg-2">
                            <select name="site_id_ts_not_in[]" class="form-control select_2" multiple="multiple">
                              @foreach ($sites as $siteRow)
                                <option
                                {{ (  in_array($siteRow->id, old('site_id_ts_not_in',[]))  )  ? 'selected' : '' }}
                                 value="{{ $siteRow->id }}" >{{ $siteRow->title }}</option>
                              @endforeach
                            </select>
                          </div>
                    @endif



                    @if( $query['alias'] == 'UsersSubscribedInSitesFromTo')
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="from_date_subscribed_sites" value="{{ old('from_date_subscribed_sites', $defaultDateFrom) }}">
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="to_date_subscribed_sites" value="{{ old('to_date_subscribed_sites', $defaultDateTo) }}">
                          </div>
                          <div class="col-lg-3">
                            <select name="site_id_subscribed_sites[]" class="form-control select_2" multiple="multiple">
                              @foreach ($sites as $siteRow)
                                <option {{ (  in_array($siteRow->id, old('site_id_not_tested',[]))  )  ? 'selected' : '' }}
                                  value="{{ $siteRow->id }}" >{{ $siteRow->title }}</option>
                              @endforeach
                            </select>
                          </div>
                    @endif


                    @if( $query['alias'] == 'UsersSuccessedInSitesFromTo')
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="from_date_successed_sites" value="{{ old('from_date_successed_sites', $defaultDateFrom) }}">
                          </div>
                          <div class="col-lg-2">
                            <input class="form-control" type="date" name="to_date_successed_sites" value="{{ old('to_date_successed_sites', $defaultDateTo) }}">
                          </div>
                          <div class="col-lg-3">
                            <select name="site_id_successed_sites[]" class="form-control select_2" multiple="multiple">
                              @foreach ($sites as $siteRow)
                                <option {{ (  in_array($siteRow->id, old('site_id_not_tested',[]))  )  ? 'selected' : '' }}
                                  value="{{ $siteRow->id }}" >{{ $siteRow->title }}</option>
                              @endforeach
                            </select>
                          </div>
                    @endif


                    @if($query['alias'] == 'SendEmail')
                          <div class="col-lg-6">
                            <textarea class="form-control" name="emails" rows="8" cols="50">{{ old('emails') }}</textarea>
                          </div>
                    @endif





                    <div class="col-lg-1">
                      <button type="submit" name="send_email_query" value="{{$query['alias']}}" class="btn btn-success">ارسال</button>
                    </div>

                    <!-- in send email : dont show count, csv and show   just send button -->
                    @if($query['alias'] != 'SendEmail')
                      <div class="col-lg-1">
                        <button type="submit" name="count_data_query" value="{{$query['alias']}}" class="btn btn-success">العدد</button>
                      </div>

                      <div class="col-lg-1">
                        <button type="submit" name="export_csv_data_query" value="{{$query['alias']}}" class="btn btn-success">CSV</button>
                      </div>

                      {{--
                      <div class="col-lg-1">
                        <button type="submit" name="show_data_query" value="{{$query['alias']}}" class="btn btn-success">عرض</button>
                      </div>
                      --}}
                    @endif


                  </div>
                </div>

              </div>
              @endforeach

              <div class="form-group row">
                <!-- <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.html') }}</label> -->
                <div class=" col-lg-12 col-md-9 col-sm-12">
                  <x-inputs.ckeditor name="message" data="" />
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('message'))<span class="invalid-feedback">{{ $errors->first('message') }}</span>@endif
                </div>
              </div>
        </form>



                </div>
            </div>
        </div>
    </div>





    @isset($data)
      @if(! empty($data))
        <div class="clearfix"></div>
        <div class="row">
        Count : {{ $data->total() }}
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
          <thead>
            <tr>
                <!-- <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th> -->
                <th class="text-center">#</th>
                <th>Name</th>
                <th>email</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $row)
            <tr id="{{ $row->id }}">
                <td value="{{ $row->id }}">{{ $row->id }}</td>
                <td> {{ $row->name }} </td>
                <td> {{ $row->email }} </td>
            </tr>
            @endforeach
        </table>

        {{ $data->appends(Request::except(['message','_token']))->links() }}
      </div>
      @endif
    @endisset

</div>

@stop


@section('js_pagelevel')
<!-- select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
      // $('.select_2').select2({
      //   placeholder: 'اختر'
      // });
      //
      // $('.select_2').select2({
      //   placeholder: 'اختر'
      // });
  });
</script>


<script>
  // datatable settings
  dt1_display_search_input_columns_values = [];
  dt1_display_search_droplist_columns_values = [];
</script>


<x-admin.datatable.footer-js-full/>
<x-buttons.but_delete_inline_js/>


@endsection
