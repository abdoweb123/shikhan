@extends('back/layouts.app')

@section('content')


<div class="">
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12">
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
            <h2> {{ $course->name.' | '.__('meta.title.questions_old') }} </h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @if(session()->has('success'))
                <div class="alert alert-success text-center">
                    {{ session()->get('success') }}
                </div>
            @endif
            @include('back.includes.breadcrumb',['routes' => [
                ['slug' => route('dashboard.courses.index',$site->alias),'name' => $site->name],
                ['name' => $course->name.' | '.__('meta.title.questions_old')]]
            ])

            <div class="col-md-12 row">


                    <a href="{{ route('dashboard.courses.questions_old.create',['site' => $site->alias,'course' => $course->id]) }}" class="btn btn-success" >اضافة سؤال</a>



                <div class="col-md-3">
                    <div class="input-group">
                        <select id="element_types" class="form-control">
                            <option value="">{{ __('field.select_property_type') }}</option>
                            <!-- <option value="range">{{ __('field.range') }}</option> -->
                            <option value="true_false">{{ __('field.true_false') }}</option>
                            <option value="drop_list">{{ __('field.drop_list') }}</option>
                        </select>
                        <span class="input-group-btn">
                            <button id="add_element" onclick="create_element();" class="btn btn-success" type="button"> @lang('core.add') </button>
                        </span>
                    </div>
                </div>


              <div class="col-md-3">
                  <form action="{{ route('dashboard.courses.questions_old.import',['site' =>$site->id,'course' => $course_id]) }}" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="input-group">
                          <input type="file" name="import_file" class="form-control">
                          <div class="input-group-btn">
                              <button class="btn btn-default" type="submit">
                                  <i class="glyphicon glyphicon-cloud-upload"></i>
                              </button>
                          </div>
                      </div>
                          <!-- <p class="text-muted"> Headings for file upload is ("id","type","status","required","degree","name_ar","answers","options","correct_answer") </p> -->
                  </form>
              </div>

              <div class="col-md-2">
                <form action="{{ route('dashboard.courses.questions_old.delete',['site' =>$site->alias,'course' => $course_id]) }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="delete" />
                    <button class="btn btn-sm btn-danger edit" type="submit">حذف كل الاسئلة</button>
                </form>
              </div>


              <div class="col-md-2">
                <a href="{{ route('dashboard.courses.questions_old.export',['site' => $site->alias,'course' => $course->id]) }}" class="btn btn-success" >Export Xml</a>
              </div>



            </div>
            <hr>


            {!! Form::model([],['method' => 'PUT','route' => ['dashboard.courses.questions_old.update',$site->alias,$course->id],'class'=>'form-horizontal form-label-left']) !!}
                <div class="col-md-12">
                    <table class="table table-dark table-striped table-bordered">
                        <tbody id="questions_container"></tbody>
                    </table>
                </div>
                <div class="col-sm-9 col-xs-12 col-md-offset-3">
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                    <!-- <input type="hidden" name="_method" value="PUT"> -->
                    <button type="submit" class="btn btn-primary"> @lang('core.save') </button>
                </div>

            </form>

          </div>

      </div>
    </div>
  </div>
</div>
@stop
