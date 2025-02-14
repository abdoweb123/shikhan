@extends('back/layouts.app')


@section('content')


    @component('components.admin.page-header', [
    'title' => __('actions.edit') . ' ' . $data->title,
    'routes' => [
          ['header_route' => route('dashboard.lessons.index'), 'header_name' => __('domain.lessons')],
          ['header_name' => __('actions.edit')]
        ]
    ])
    @endcomponent


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <!-- <div class="card-header">
                          <h3 class="card-title">Quick Example</h3>
                        </div> -->


                        <x-admin.datatable.page-alert />



                        <form method="post" action="{{ route('dashboard.lessons.update', ['lesson' => $data->id]) }}" id="form" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">

                                <input name="language" type="hidden" value="{{ $locale }}">


                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('general.title') }} <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="title" maxlength="150" value="{{ old('title', $translation?->title) }}" required class="form-control {{ $errors->has('title') ? ' is-invalid ' : '' }}" placeholder="">
                                        <x-admin.datatable.label-input-error field='title' />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('general.alias') }} <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="alias" maxlength="150" value="{{ old('alias', $translation?->alias) }}" required class="form-control {{ $errors->has('alias') ? ' is-invalid ' : '' }}" placeholder="">
                                        <x-admin.datatable.label-input-error field='alias' />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('domain.courses') }} <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <x-admin.dd-courses :courses='$courses' dataValue="{{ $data->course_id }}"/>
                                        <x-admin.datatable.label-input-error field='course_id' />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('domain.teachers') }} <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <x-admin.datatable.teachers-dd :teachers='$teachers' dataValue="{{ $data->teacher_id }}"/>
                                        <x-admin.datatable.label-input-error field='teacher_id' />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('domain.lesson_study_type') }} <span class="required">*</span></label>
                                    <div class="col-sm-10">
                                        <x-admin.lesson-study-types-dd :lesson_study_types='$lessonStudyTypes' :dataValue="$data->lesson_study_type_id" />
                                        <x-admin.datatable.label-input-error field='lesson_study_type_id' />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('domain.zoom_link') }}</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="link_zoom" maxlength="250" value="{{ old('link_zoom', $translation? $translation->link_zoom : '') }}" class="form-control {{ $errors->has('zoom_link') ? ' is-invalid ' : '' }}" placeholder="">
                                        <x-admin.datatable.label-input-error field='link_zoom' />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2 col-sm-2">{{ __('trans.start_at') }}</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" class="form-control {{ $errors->has('started_at') ? ' is-invalid' : '' }}" maxlength="500"
                                               value="{{ old('started_at', $data->started_at) }}" name="started_at" placeholder="">
                                        <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                                        @if ($errors->has('started_at'))<span class="invalid-feedback">{{ $errors->first('started_at') }}</span>@endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2">{{ __('words.brief') }}</label>
                                    <div class="col-sm-10">
                                    <textarea  class="form-control {{ $errors->has('brief') ? ' is-invalid' : '' }}" maxlength="300"
                                             name="brief" placeholder="">{{ old('brief',  $translation? $translation->brief : '') }}</textarea>
                                        <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                                        @if ($errors->has('brief'))<span class="invalid-feedback">{{ $errors->first('brief') }}</span>@endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('domain.duration') }} {{ __('general.minutes') }} 00:00</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="video_duration" maxlength="6" value="{{ old('video_duration', $translation?->video_duration) }}" class="form-control {{ $errors->has('video_duration') ? ' is-invalid ' : '' }}" placeholder="">
                                        <x-admin.datatable.label-input-error field='video_duration' />
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('general.status') }} <span class="required">*</span></label>
                                    <div class="col-sm-10">
{{--                                        <x-admin.dd-statuses :statuses='$statuses' dataValue="{{ $data->status }}"/>--}}
                                        <select class="form-control {{ $errors->has('is_active') ? ' is-invalid' : '' }}" name="is_active">
                                            <option value="1" {{ old('is_active', $data->is_active) == 1 ? "selected" : '' }}>
                                                {{ __('words.active') }}
                                            </option>
                                            <option value="0" {{ old('is_active', $data->is_active) == 0 ? "selected" : '' }}>
                                                {{ __('words.not_active') }}
                                            </option>
                                        </select>
                                        <x-admin.datatable.label-input-error field='status' />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <x-admin.input-sort dataValue="{{ $data->sort }}"/>
                                    <x-admin.datatable.label-input-error field='sort' />
                                </div>

                                <div class="form-group row">
                                    <x-admin.input-meta-header dataValue="{{ $translation?->header }}"/>
                                    <x-admin.datatable.label-input-error field='header' />
                                </div>

                                <div class="form-group row">
                                    <x-admin.input-meta-keywords dataValue="{{ $translation?->meta_keywords }}"/>
                                    <x-admin.datatable.label-input-error field='meta_keywords' />
                                </div>

                                <div class="form-group row">
                                    <x-admin.input-meta-description dataValue="{{ $translation?->meta_description }}"/>
                                    <x-admin.datatable.label-input-error field='meta_description' />
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2">{{ __('words.html') }}</label>
                                        <div class="col-sm-10">
                                            <x-inputs.ckeditor name="html" data="{{$data->html}}" />
                                            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                                            @if ($errors->has('html'))<span class="invalid-feedback">{{ $errors->first('html') }}</span>@endif
                                        </div>
                                    </div>
                                </div>

{{--                                <div class="form-group row">--}}
{{--                                    <x-admin.input-ckeditor />--}}
{{--                                    <x-admin.datatable.label-input-error field='description' />--}}
{{--                                </div>--}}

                                <div class="form-group row">

                                    @component('components.options.options', [
                                      'options' => $options,
                                      'dataValue' => $data->options
                                    ])
                                    @endcomponent
                                </div>

                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-info">{{ __('actions.save') }}</button>
                                <!-- <button type="submit" class="btn btn-default float-right">Cancel</button> -->
                            </div>
                        </form>
                    </div>



                </div>
            </div>
        </div>
    </section>



@endsection









{{--@extends('back/layouts.app')--}}

{{--@section('css_pagelevel')--}}
{{--<x-admin.datatable.header-css/>--}}
{{--@endsection--}}

{{--@section('content')--}}

{{--<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">--}}
{{--  <div class="row">--}}
{{--    <div class="col-lg-12">--}}
{{--      <div class="kt-portlet">--}}

{{--        <div class="kt-portlet__head">--}}
{{--          <div class="kt-portlet__head-label">--}}
{{--            <h3 class="kt-portlet__head-title">--}}
{{--              <div class="row">--}}
{{--                {{ __('words.edit') }} &nbsp;&nbsp; {{ request()->query('language') }} <x-buttons.but_back link="{{ route('dashboard.lessons.index') }}"/>--}}

{{--              </div>--}}
{{--            </h3>--}}
{{--            @include('back.includes.page-alert')--}}
{{--          </div>--}}
{{--        </div>--}}

{{--        <div class="kt-portlet__body">--}}
{{--          <div class="kt-section kt-section--first">--}}


{{--<form method="post" enctype="multipart/form-data" action="{{route('dashboard.lessons.update', ['id' => $data->id ])}}" id="form" >--}}

{{--    {{ csrf_field() }}--}}

{{--    <input name="language" type="hidden" value="{{ request()->query('language') }}">--}}

{{--    <div class="form-group row">--}}
{{--      <div class="col-md-6">--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.teachers') }} *</label>--}}
{{--              <div class=" col-lg-9 col-md-9 col-sm-12">--}}
{{--                <select class="form-control select_2 {{ $errors->has('teacher_id') ? ' is-invalid' : '' }}"  id="kt_select2_1" name="teacher_id">--}}
{{--                    <option {{ old('teacher_id' , $data->teacher_id ) == null ? 'selected' : '' }} value="">{{__('core.app_name')}}</option>--}}
{{--                  @foreach ( $teachers as $teacher )--}}
{{--                    <option {{ old('teacher_id', $data->teacher_id) == $teacher->id ? 'selected' : '' }} value="{{ $teacher->id }}">{{ $teacher->name }}</option>--}}
{{--                  @endforeach--}}
{{--                </select>--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('teacher_id'))--}}
{{--                    <span class="invalid-feedback">{{ $errors->first('teacher_id') }}</span>--}}
{{--                @endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="course_id">  {{__('core.courses') }}<span class="required"> *</span> </label>--}}
{{--              <div class=" col-md-9 col-sm-9 col-xs-12">--}}
{{--                  <select name="course_id" class="select_2 form-control  {{ $errors->has('teacher_id') ? ' is-invalid' : '' }} col-md-6 col-sm-6 col-xs-12">--}}
{{--                      @isset($courses)--}}
{{--                              @foreach ($courses as $course)--}}
{{--                                  <option {{ Request::old('course_id', $data->course_id ) == $course->id ? 'selected' : '' }} value="{{ @$course->id }}"> {{ $course->name }}  -- {{ $course->site != null ? $course->site->title : ''}} </option>--}}
{{--                              @endforeach--}}
{{--                      @endisset--}}
{{--                  </select>--}}
{{--                  @if ($errors->has('course_id'))--}}
{{--                      <span class="help-block">{{ $errors->first('course_id') }}</span>--}}
{{--                  @endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>--}}
{{--              <div class=" col-lg-9 col-md-9 col-sm-12">--}}
{{--                  <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" required maxlength="150"--}}
{{--                  value="{{ old('title', $translation->title ?? '') }}" name="title" placeholder="">--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('title'))<span class="invalid-feedback">{{ $errors->first('title') }}</span>@endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.alias') }} *</label>--}}
{{--              <div class=" col-lg-9 col-md-9 col-sm-12">--}}
{{--                  <input type="text" class="form-control {{ $errors->has('alias') ? ' is-invalid' : '' }}" required maxlength="alias"--}}
{{--                  value="{{ old('alias', $translation->alias ?? '') }}" name="alias" placeholder="">--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('alias'))<span class="invalid-feedback">{{ $errors->first('alias') }}</span>@endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.image') }}</label>--}}
{{--              <div class="col-lg-9 col-md-9 col-sm-12">--}}
{{--                <input type="file" name="image" id="image" class="dropify img_edit" data-default-file="{{ $data->logo_path }}" />--}}
{{--                <input type="checkbox" name="image_remove" value="{{ old('image_remove') }}"> حذف الصورة--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.sort') }}</label>--}}
{{--              <div class=" col-lg-4 col-md-9 col-sm-12">--}}
{{--                <input class="form-control {{ $errors->has('sort') ? ' is-invalid' : '' }}" type="number" min="1"--}}
{{--                value="{{ old('sort', $data->sort) }}" maxlength="3" id="example-number-input" name="sort">--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('sort'))--}}
{{--                    <span class="invalid-feedback">{{ $errors->first('sort') }}</span>--}}
{{--                @endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <x-admin.is-active dataValue="{{ $translation->trans_status ?? null }}"/>--}}
{{--          </div>--}}
{{--      </div>--}}
{{--      <div class="col-md-6">--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.header') }}</label>--}}
{{--              <div class=" col-lg-8 col-md-9 col-sm-12">--}}
{{--                <textarea  class="form-control {{ $errors->has('header') ? ' is-invalid' : '' }}"--}}
{{--                name="header" placeholder="">{{ old('header', $translation->header ?? '') }}</textarea>--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('header'))<span class="invalid-feedback">{{ $errors->first('header') }}</span>@endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_description') }}</label>--}}
{{--              <div class=" col-lg-8 col-md-9 col-sm-12">--}}
{{--                <textarea  class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}"--}}
{{--                name="meta_description" placeholder="">{{ old('meta_description', $translation->meta_description ?? '') }}</textarea>--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('meta_description'))<span class="invalid-feedback">{{ $errors->first('meta_description') }}</span>@endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_keywords') }}</label>--}}
{{--              <div class=" col-lg-8 col-md-9 col-sm-12">--}}
{{--                <textarea  class="form-control {{ $errors->has('meta_keywords') ? ' is-invalid' : '' }}"--}}
{{--                name="meta_keywords" placeholder="">{{ old('meta_keywords', $translation->meta_keywords ?? '') }}</textarea>--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('meta_keywords'))<span class="invalid-feedback">{{ $errors->first('meta_keywords') }}</span>@endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.brief') }}</label>--}}
{{--              <div class=" col-lg-8 col-md-9 col-sm-12">--}}
{{--                <textarea  class="form-control {{ $errors->has('brief') ? ' is-invalid' : '' }}" maxlength="300"--}}
{{--                name="brief" placeholder="">{{ old('brief', $translation->brief ?? '') }}</textarea>--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('brief'))<span class="invalid-feedback">{{ $errors->first('brief') }}</span>@endif--}}
{{--              </div>--}}
{{--          </div>--}}

{{--          --}}{{----}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.pdf') }}</label>--}}
{{--              <div class="col-lg-9 col-md-9 col-sm-12">--}}
{{--                <input type="text" name="pdf"  class="form-control "  value="{{ old('pdf', $translation->pdf ?? '')}}"/>--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.sound') }}</label>--}}
{{--              <div class="col-lg-9 col-md-9 col-sm-12">--}}
{{--                <input type="text" value="{{ old('sound', $translation->sound ?? '') }}" name="sound" id="sound" class="form-control {{ $errors->has('sound') ? ' is-invalid' : '' }}" maxlength="500"/>--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.video') }}</label>--}}
{{--              <div class=" col-lg-9 col-md-9 col-sm-12">--}}
{{--                  <input type="text" class="form-control {{ $errors->has('video') ? ' is-invalid' : '' }}" maxlength="500"--}}
{{--                  value="{{ old('video', $translation->video ?? '') }}" name="video" placeholder="">--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('video'))<span class="invalid-feedback">{{ $errors->first('video') }}</span>@endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          --}}

{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.link_zoom') }}</label>--}}
{{--              <div class=" col-lg-9 col-md-9 col-sm-12">--}}
{{--                  <input type="text" class="form-control {{ $errors->has('link_zoom') ? ' is-invalid' : '' }}" maxlength="500"--}}
{{--                  value="{{ old('link_zoom', $translation->link_zoom ?? '') }}" name="link_zoom" placeholder="">--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('link_zoom'))<span class="invalid-feedback">{{ $errors->first('link_zoom') }}</span>@endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--          <div class="form-group row">--}}
{{--              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.started_at') }}</label>--}}
{{--              <div class=" col-lg-9 col-md-9 col-sm-12">--}}
{{--                  <input type="datetime-local" class="form-control {{ $errors->has('started_at') ? ' is-invalid' : '' }}" maxlength="500"--}}
{{--                  value="{{ old('started_at', $translation->started_at ?? '') }}" name="started_at" placeholder="">--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('started_at'))<span class="invalid-feedback">{{ $errors->first('started_at') }}</span>@endif--}}
{{--              </div>--}}
{{--          </div>--}}
{{--      </div>--}}

{{--      <div class="col-md-12">--}}
{{--          <label class="col-form-label col-lg-1 col-sm-12">{{ __('words.html') }}</label>--}}
{{--          <div class="col-lg-11 col-md-9 col-sm-12">--}}
{{--            <x-inputs.ckeditor name="html"--}}
{{--              data="{!!  file_exists('storage/app/public/'.($translation->html ?? 'no_file')) == true ? file_get_contents('storage/app/public/'.($translation->html ?? '')) : '' !!}" />--}}
{{--            @if ($errors->has('html'))<span class="invalid-feedback">{{ $errors->first('html') }}</span>@endif--}}
{{--          </div>--}}
{{--      </div>--}}

{{--      --}}{{----}}
{{--      <div class="col-md-12" style="padding: 15px 0px;">--}}
{{--          <label class="col-form-label col-lg-1 col-sm-12">Pdf title</label>--}}
{{--          <div class="col-lg-11 col-md-9 col-sm-12">--}}
{{--            <input type="text" class="form-control {{ $errors->has('pdf_title') ? ' is-invalid' : '' }}" name="pdf_title" placeholder=""--}}
{{--              value="{{ old('pdf_title', $LessonOptions->where('option_id',1)->first() != null ? $LessonOptions->where('option_id',1)->first()->value : null ) }}">--}}
{{--          </div>--}}
{{--      </div>--}}
{{--      --}}


{{--      <!-- options -->--}}
{{--      <div class="col-md-12" style="padding: 20px 0px;">--}}
{{--        <div class="row">--}}
{{--            <span id="optionsError" style="color:red;text-align: center;" ></span>--}}
{{--            <div class="col-lg-1"></div>--}}
{{--            <div class="col-md-3 form-group{{ $errors->has('options') ? ' has-error' : '' }}">--}}
{{--                    <label class="control-label col-md-8 col-sm-12 col-xs-12 text-center" for="name">{{ __('trans.attribute') }}<span class="required">*</span>--}}
{{--                    </label>--}}
{{--                    <div class="col-md-12 col-sm-12 col-xs-12">--}}
{{--                    <select class="form-control col-md-7 col-xs-12" id="option" onchange="setType(this)" required>--}}
{{--                            <option value="0" data-name="none" data-type="none">Select</option>--}}
{{--                            @foreach($options as $option)--}}
{{--                            @if ($option->type != 'file') <!-- if options is file will get it in section imgoption -->--}}
{{--                            <option value="{{$option->id}}" data-name="{{$option->titleGeneral}}" data-type="{{$option->type}}">{{$option->titleGeneral}}</option>--}}
{{--                            @endif--}}
{{--                            @endforeach--}}
{{--                    </select>--}}
{{--                    @if ($errors->has('options'))--}}
{{--                            <span class="help-block">{{ $errors->first('options') }}</span>--}}
{{--                    @endif--}}
{{--                    </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-3 form-group{{ $errors->has('values') ? ' has-error' : '' }}">--}}
{{--                <label class="control-label col-md-8 col-sm-12 col-xs-12 text-left" for="name">{{ __('trans.value') }}<span class="required">*</span></label>--}}
{{--                <div class="col-md-12 col-sm-12 col-xs-12">--}}
{{--                    <input type="text" value="" id="value" name="name" class="form-control col-md-7 col-xs-12" style="visibility : hidden">--}}
{{--                    <select name="valueSelect" id="valueSelect" style="visibility : hidden; top:-33px;" class="form-control col-md-7 col-xs-12">--}}
{{--                        <option value="0">Select</option>--}}
{{--                    </select>--}}
{{--                    @if ($errors->has('values'))--}}
{{--                            <span class="help-block">{{ $errors->first('values') }}</span>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-3 form-group{{ $errors->has('options') ? ' has-error' : '' }}">--}}
{{--                <label class="control-label col-md-8 col-sm-12 col-xs-12 text-left" for="name">{{ __('trans.title') }}<span class="required">*</span></label>--}}
{{--                  <input type="text" value="" id="option_title" name="option_title" class="form-control col-md-7 col-xs-12" style="visibility : hidden">--}}
{{--            </div>--}}
{{--            <div class="form-group col-md-2 col-sm-6 col-xs-12">--}}
{{--                <div class="col-md-12 col-sm-6 col-xs-12">--}}
{{--                <input type="hidden" name="_token" value="{{ Session::token() }}">--}}
{{--                <a class="btn btn-success" style="margin-top: 20%;border-radius: 50%" onClick="addElement()"><li class="fa fa-plus"></li> </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-12 col-sm-6 col-xs-12">--}}
{{--              <div class="col-lg-1"></div>--}}
{{--              <div class="col-lg-11">--}}
{{--                <table  id="datatable-buttons" class="table table-striped table-bordered">--}}
{{--                  <thead>--}}
{{--                  <tr>--}}
{{--                      <th width="20%">Option</th>--}}
{{--                      <th width="40%">Value</th>--}}
{{--                      <th width="20%">Title</th>--}}
{{--                      <th width="20%">Remove</th>--}}
{{--                  </tr>--}}
{{--                  </thead>--}}
{{--                  <tbody id="data">--}}
{{--                      <?php $a = 0; $b = 0; $c = 0; ?>--}}
{{--                      @if (Session::has('oldOptions'))--}}
{{--                          @foreach(Session::get('oldOptions') as $a=>$opt)--}}
{{--                            <tr class="{{$a}}">--}}
{{--                                <td class="col-md-2">{{ $opt[4] }}</td>--}}
{{--                                <td class="col-md-5">{{ $opt[1] }}</td>--}}
{{--                                <td id="{{$a}}" class="btn btn-danger fa fa-minus-circle" onclick="deleteElement(event)"></td>--}}
{{--                                <td class="col-md-1">{{ $opt[2] }}</td>--}}
{{--                                <td class="col-md-1">{{ $opt[3] }}</td>--}}
{{--                                <input type="hidden" name="options[{{$a}}]" class="{{$a}}" value="{{ $opt[0] }}">--}}
{{--                                <input type="hidden" name="values[{{$a}}]" class="{{$a}}" value="{{ $opt[1] }}" maxlength="1000">--}}
{{--                                <input type="hidden" name="types[{{$a}}]" class="{{$a}}" value="{{ $opt[2] }}">--}}
{{--                                <input type="hidden" name="SelValue[{{$a}}]" class="{{$a}}" value="{{ $opt[3] }}">--}}
{{--                                <input type="hidden" name="optionName[{{$a}}]" class="{{$a}}" value="{{ $opt[4] }}">--}}
{{--                                <input type="hidden" name="titles[{{$a}}]" class="{{$a}}" value="{{ $opt[5] }}">--}}
{{--                            </tr>--}}
{{--                          @endforeach--}}
{{--                      @else--}}
{{--                        @foreach($data->options as $b=>$option)--}}
{{--                            <tr class="{{$b}}">--}}
{{--                                @if($option->options)--}}
{{--                                  @if(count($option->options->option_info))--}}
{{--                                      <td  class="col-md-2">{{$option->options->option_info[0]->title}}</td>--}}
{{--                                  @endif--}}
{{--                                @endif--}}

{{--                                <td class="col-md-5">{{$option->options->type}}</td>--}}
{{--                                <td class="col-md-1">{{$option->value}}</td>--}}
{{--                                <a> <td id="{{$b}}" class="btn btn-danger fa fa-times" onclick="deleteElement(event)"></td></a>--}}

{{--                                <td class="col-md-1"></td>--}}
{{--                                <input class="{{$b}}" hidden name="options[{{$b}}]" value="{{$option->option_id}}"/>--}}
{{--                                <input class="{{$b}}" hidden name="values[{{$b}}]" value="{{$option->value}}" maxlength="1000"/>--}}
{{--                                <input class="{{$b}}" hidden name="types[{{$b}}]" value="{{$option->options->type}}"/>--}}
{{--                                <input class="{{$b}}" hidden name="SelValue[{{$b}}]"  value="">--}}
{{--                                <input class="{{$b}}" hidden name="titles[{{$b}}]"  value="{{$option->title}}" maxlength="1000">--}}
{{--                                @if($option->options)--}}
{{--                                  @if(count($option->options->option_info))--}}
{{--                                      <input class="{{$b}}" hidden name="optionName[{{+$b}}]"  value="{{$option->options->option_info[0]->title}}" >--}}
{{--                                  @endif--}}
{{--                                @endif--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}

{{--                        @foreach($data->option_values as $c=>$option_value)--}}
{{--                            <tr class="{{$b+$c+1}}">--}}

{{--                                @if($option->options)--}}
{{--                                @if(count($option->options->option_info))--}}
{{--                                    <td  class="col-md-2">{{$option_value->options->option_info[0]->title}}</td>--}}
{{--                                @endif--}}
{{--                                @endif--}}
{{--                                <td class="col-md-5">{{$option_value->option_value_info[0]->title}}</td>--}}
{{--                                <a> <td id="{{$b+$c+1}}" class="btn btn-danger fa fa-times" onclick="deleteElement(event)"></td></a>--}}
{{--                                <td class="col-md-1">{{$option_value->options->type}}</td>--}}
{{--                                <td class="col-md-1">{{$option_value->pivot->option_value_id}}</td>--}}
{{--                                <input class="{{$b+$c+1}}" hidden name="options[{{$b+$c+1}}]" value="{{$option_value->option_id}}"/>--}}
{{--                                @if($option->options)--}}
{{--                                @if($option->options->option_info)--}}
{{--                                    <input class="{{$b+$c+1}}" hidden name="values[{{$b+$c+1}}]" value="{{$option_value->option_value_info[0]->title}}" maxlength="1000"/>--}}
{{--                                @endif--}}
{{--                                @endif--}}
{{--                                <input class="{{$b+$c+1}} "hidden name="types[{{$b+$c+1}}]" value="{{$option_value->options->type}}"/>--}}
{{--                                <input class="{{$b+$c+1}}" hidden name="SelValue[{{$b+$c+1}}]"  value="{{$option_value->pivot->option_value_id}}">--}}
{{--                                <input class="{{$b+$c+1}}" hidden name="optionName[{{$b+$c+1}}]"  value="{{$option_value->options->option_info[0]->title}}">--}}
{{--                                <input class="{{$b+$c+1}}" hidden name="titles[{{$b+$c+1}}]"  value="{{ $option_value->titles[app()->getlocale()] ?? ''}}">--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                      @endif--}}
{{--                  </tbody>--}}
{{--              </table>--}}
{{--              </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--      </div>--}}


{{--      <div class="col-md-12">--}}
{{--        <div class="form-group row">--}}
{{--          <div class="col-lg-1"></div>--}}
{{--          <div class="col-lg-11">--}}
{{--            <x-buttons.but_submit/>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}

{{--    </div>--}}



{{--</form>--}}
{{--          </div>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--</div>--}}



{{--@section('js_pagelevel')--}}
{{--<x-admin.dropify-js/>--}}
{{--<x-admin.datatable.footer-single-report-js/>--}}

{{--<script>--}}
{{--$(document).ready(function() {--}}
{{--    $('.select_2').select2();--}}
{{--});--}}
{{--</script>--}}

{{--<script>--}}

{{--function setType(e){--}}

{{--  var option = document.getElementById('option');--}}
{{--  // var type = e.target.children[e.target.value].getAttribute('data-type');--}}
{{--  var type = e.options[e.selectedIndex].getAttribute('data-type');--}}
{{--  var value = document.getElementById('value');  // input for input,file--}}
{{--  var valueSelect = document.getElementById('valueSelect');  // dropdownList--}}
{{--  var optionTitle = document.getElementById('option_title');--}}

{{--  //alert(option.value);--}}
{{--  if (type =="none")               // Hide Input and DropdownList if Firest Option is selected--}}
{{--  {--}}
{{--    valueSelect.style.visibility = 'hidden';--}}
{{--    value.style.visibility = 'hidden';--}}
{{--    optionTitle.style.visibility = 'hidden';--}}
{{--    return;--}}
{{--  }--}}

{{--  if (type == "select")           // get Data and Fill DropdownList--}}
{{--  {--}}
{{--    value.style.visibility = 'hidden';--}}
{{--    valueSelect.style.visibility = 'visible';--}}
{{--    optionTitle.style.visibility = 'visible';--}}
{{--    var fill=autoCompelete(option.value,'valueSelect'); // get data and fill drobdownlist--}}
{{--  }--}}
{{--  else                             // if type text,file--}}
{{--  {--}}
{{--    valueSelect.style.visibility = 'hidden';--}}
{{--    value.style.visibility = 'visible';--}}
{{--    optionTitle.style.visibility = 'visible';--}}
{{--    value.setAttribute('type', type); // input for input,file--}}
{{--  }--}}




{{--}--}}

{{--function createElement(type, attributes)--}}
{{--{--}}
{{--    var element = document.createElement(type);--}}
{{--    for(attribute in attributes){--}}
{{--      element.setAttribute(attribute, attributes[attribute]);--}}
{{--    }--}}

{{--    return element;--}}
{{--}--}}

{{--function addElement()--}}
{{--{--}}
{{--    var form = document.getElementById('form');--}}
{{--    var option = document.getElementById('option');--}}
{{--    var value = document.getElementById('value');--}}
{{--    var valueSelect = document.getElementById('valueSelect');--}}
{{--    var dataTable = document.getElementById('data');--}}
{{--    var optionTitle = document.getElementById('option_title');--}}

{{--    //Get Current Option Type--}}
{{--    // var optionType = option.children[option.value].getAttribute('data-type');--}}
{{--    var optionType = option[option.selectedIndex].getAttribute('data-type')--}}

{{--    // if no option select then dont insert and give error message--}}
{{--    if (option.value == 0)--}}
{{--    {--}}
{{--      optionsError.innerHTML = "Select Attribute and Value ";--}}
{{--      return;--}}
{{--    }--}}

{{--    // if Cuurent option is (select) Check to not select the firest one--}}
{{--    if (optionType == "select")--}}
{{--    {--}}
{{--      if( valueSelect.value == 0 )--}}
{{--      {--}}
{{--        optionsError.innerHTML = "Select Item from Attribute Values";--}}
{{--        return;--}}
{{--      }--}}
{{--    }--}}
{{--    // if Cuurent option is (text) Check to have value--}}
{{--    if (optionType == "text")--}}
{{--    {--}}
{{--      if(value.value == "" || value.value == null || value.value.length > 1000)--}}
{{--      {--}}
{{--        optionsError.innerHTML = "Insert Value Not More Than 1000 Charactrs";--}}
{{--        return;--}}
{{--      }--}}
{{--    }--}}



{{--    var flg=false;--}}
{{--    var chkid=dataTable.childElementCount;--}}
{{--    while (flg == false) {--}}
{{--    var newid = dataTable.getElementsByClassName(chkid).length;--}}
{{--    if (newid == 0 )--}}
{{--    {flg = true;}--}}
{{--    else--}}
{{--    { chkid = chkid +1 ;}--}}
{{--    }--}}


{{--    //var tr = createElement('tr', {class: dataTable.childElementCount});--}}
{{--    var tr = createElement('tr', {class: chkid});--}}
{{--    var td1 = createElement('td');--}}
{{--    var td2 = createElement('td');--}}
{{--    var td3 = createElement('td', {id: chkid, class: 'btn btn-danger'});--}}
{{--    var td4 = createElement('td');--}}
{{--    var td5 = createElement('td');--}}
{{--    var td6 = createElement('t6');--}}


{{--    // td1.innerHTML = option.children[option.value].getAttribute('data-name');--}}
{{--    td1.innerHTML =  option[option.selectedIndex].getAttribute('data-name')--}}

{{--    if (optionType == "select")--}}
{{--    {--}}
{{--      td5.innerHTML = valueSelect.value;--}}
{{--      td2.innerHTML = valueSelect.options[valueSelect.selectedIndex].text;  //valueSelect.value + valueSelect.innerHTML;--}}
{{--    }--}}
{{--    if (optionType == "text" )--}}
{{--    {--}}
{{--      td2.innerHTML = value.value;--}}
{{--      td6.innerHTML = optionTitle.value;--}}
{{--    }--}}

{{--    td3.addEventListener('click', deleteElement, false);--}}
{{--    td4.innerHTML = optionType;--}}

{{--    tr.appendChild(td1);--}}
{{--    tr.appendChild(td2);--}}
{{--    tr.appendChild(td3);--}}
{{--    tr.appendChild(td4);--}}
{{--    tr.appendChild(td5);--}}
{{--    // tr.appendChild(td6);--}}
{{--    dataTable.appendChild(tr);--}}


{{--    var hidden1 = createElement('input', {type:'hidden', name: 'options['+(td3.id)+']', class: td3.id, value: option.value});--}}
{{--    if (optionType == "text") {--}}
{{--      var hidden2 = createElement('input', {type:'hidden', name: 'values['+(td3.id)+']', class: td3.id, value: value.value});--}}
{{--    }--}}
{{--    if (optionType == "select") {--}}
{{--      var hidden2 = createElement('input', {type:'hidden', name: 'values['+(td3.id)+']', class: td3.id, value: valueSelect.options[valueSelect.selectedIndex].text});--}}
{{--    }--}}
{{--    var hidden3 = createElement('input', {type:'hidden', name: 'types['+(td3.id)+']', class: td3.id, value: optionType});--}}
{{--    var hidden4 = createElement('input', {type:'hidden', name: 'SelValue['+(td3.id)+']', class: td3.id, value: valueSelect.value});--}}
{{--    var hidden5 = createElement('input', {type:'hidden', name: 'optionName['+(td3.id)+']', class: td3.id, value: option.children[option.selectedIndex].getAttribute('data-name')});--}}
{{--    var hidden6 = createElement('input', {type:'hidden', name: 'titles['+(td3.id)+']', class: td3.id, value: optionTitle.value});--}}

{{--    form.appendChild(hidden1);--}}
{{--    form.appendChild(hidden2);--}}
{{--    form.appendChild(hidden3);--}}
{{--    form.appendChild(hidden4);--}}
{{--    form.appendChild(hidden5);--}}
{{--    form.appendChild(hidden6);--}}

{{--    if(dataTable.childElementCount >= 1){--}}
{{--      dataTable.style.display = "table-row-group";--}}
{{--    }--}}

{{--    value.value = null;--}}
{{--    optionTitle.value = null;--}}

{{--}--}}

{{--function deleteElement(event)--}}
{{--{--}}
{{--    var id = event.target.id;--}}
{{--    var dataTable = document.getElementById('data');--}}
{{--    var saveButton = document.getElementById('save');--}}
{{--    var paras = document.getElementsByClassName(id);--}}

{{--    while(paras[0])--}}
{{--    paras[0].parentNode.removeChild(paras[0]);--}}

{{--    if(dataTable.childElementCount < 0){--}}
{{--    // saveButton.style.display = "none";--}}
{{--    dataTable.style.display = "none";--}}
{{--    }--}}
{{--}--}}

{{--function autoCompelete(crit,controlname)--}}
{{--{--}}
{{--    if(crit)--}}
{{--    {--}}
{{--        $.ajax({--}}
{{--            //url: "Search_option_values",--}}
{{--            url: "{{ route('dashboard.item.search_option_values') }}",--}}
{{--            type: "GET",--}}
{{--            dataType: "json",--}}
{{--            data : { crit: crit },--}}
{{--            success:function(data) {--}}
{{--              var valueSelect = document.getElementById(controlname);  // dropdownList--}}
{{--              $(valueSelect).empty();--}}
{{--              $(valueSelect).append('<option value="0">Select</option>');--}}

{{--              for (var key in data) {--}}
{{--                var obj = data[key];--}}
{{--                $(valueSelect).append('<option value="'+ obj['id'] +'">'+ obj['value'] +'</option>');--}}
{{--              }--}}
{{--            },--}}
{{--            error: function(response){--}}
{{--            console.log(response);--}}
{{--            alert('Error'+response);--}}
{{--            }--}}
{{--        });--}}
{{--      }  else  {--}}
{{--        alert('no data');--}}
{{--      }--}}
{{--}--}}

{{--</script>--}}

{{--@endsection--}}

{{--@endsection--}}
