@extends('back/layouts.app')

@section('content')



    <div class="">
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-xs-12">
                <div class="x_panel">
                    <div class="x_title">

                        {{--
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        --}}

                        <h2> {{ __('core.edit') }} | {{ $translation ? $translation->name : $fields->title }}  &nbsp; {{ request()->query('language') }}</h2>
                        <a class="btn btn-success pull-right" href="{{route('dashboard.courses.create', $site->id)}}">@lang('core.add') </a>
                        <div class="clearfix"></div>
                        @include('back.includes.page-alert')
                    </div>

                    <div class="x_content">
                        @include('back.includes.breadcrumb',['routes' => [
                            ['slug' => route('dashboard.courses.index', $site->id),'name' => $site->name],
                            ['name' => ($translation->name ?? '' ).' | '.__('core.edit')]]
                        ])
                        <hr>

                        <form method="post" action="{{ route('dashboard.courses.update', ['site' => $site->id, 'course' => $fields->id]) }}"
                            class='form-horizontal form-label-left' enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @method('PUT')


                        <input name="language" type="hidden" value="{{ request()->query('language') }}">

                        <div class="row">
                           <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"> Title <span class="required">*</span></label>
                                    <div class=" col-md-9">
                                        <input type="text" value="{{ old('title', $translation->name ?? '')}}" id="title" name="title" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('title'))
                                            <span class="help-block">{{ $errors->first('title') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('alias') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias"> alias <span class="required">*</span>
                                    </label>
                                    <div class=" col-md-9">
                                        <input type="text" value="{{ old('alias', $translation->alias ?? '')}}" id="alias" name="alias" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('alias'))
                                            <span class="help-block">{{ $errors->first('alias') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date"> Date <span class="required">*</span>
                                    </label>
                                    <div class=" col-md-9">
                                        <input type="date" value="{{ old('date', $translation->date_at ?? '')}}" id="date" name="date" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('date'))
                                            <span class="help-block">{{ $errors->first('date') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('duration') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date"> {{__('words.duration')}} <span class="required">*</span>
                                    </label>
                                    <div class=" col-md-9">
                                        <input type="number" value="{{ old('duration', $translation->duration ?? '')}}" id="duration" name="duration" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('duration'))
                                            <span class="help-block">{{ $errors->first('duration') }}</span>
                                        @endif
                                    </div>
                                </div>
                               {{-- <div class="form-group{{ $errors->has('limit') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="limit"> Limit <span class="required">*</span>
                                    </label>
                                    <div class=" col-md-9">
                                        <input type="number" value="{{ old('limit', $fields->limit) }}" id="limit" name="limit" min="1" class="form-control col-md-7 col-xs-12">
                                        @if ($errors->has('limit'))
                                            <span class="help-block">{{ $errors->first('limit') }}</span>
                                        @endif
                                    </div>
                                </div>--}}
                                <div class="form-group{{ $errors->has('format') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="format"> Format <span class="required"> *</span>
                                    </label>
                                    <div class=" col-md-9">
                                        <select name="format" class=" form-control col-md-12">
                                            @foreach(['A3','A4','A5','Legal','Letter','Tabloid'] as $row)
                                                <option {{ old('format', $fields->format)  == $row ? 'selected' : '' }} value="{{ $row }}"> {{ $row }} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('format'))
                                            <span class="help-block">{{ $errors->first('format') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('orientation') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="orientation"> Orientation <span class="required"> *</span>
                                    </label>
                                    <div class=" col-md-9">
                                        <select name="orientation" class=" form-control col-md-12">
                                            @foreach(['portrait', 'landscape'] as $row)
                                                <option {{ old('orientation', $fields->orientation)  == $row ? 'selected' : '' }} value="{{ $row }}"> {{ $row }} </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('orientation'))
                                            <span class="help-block">{{ $errors->first('orientation') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image_details"> Logo </label>
                                    <div class=" col-md-9 col-sm-6 col-xs-12">
                                        {{--<input type="file" value="{{ old('image_details', $fields->logo) ?: '' }}" id="image_details" name="image_details" class="form-control col-md-7 col-xs-12" accept="image/*" >--}}
                                        <input type="file" name="image" id="image" class="dropify img_edit" data-default-file="{{ $translation->ImagePath ?? '' }}"/>
                                        <input type="checkbox" name="image_remove" value="{{ old('image_remove') }}"> حذف الصورة
                                        @if ($errors->has('image_details'))
                                            <span class="help-block">{{ $errors->first('image_details') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('video_duration') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="logo"> مدة الفديو </label>
                                    <div class=" col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" value="{{ old('video_duration', $translation->video_duration ?? '') }}" id="video_duration" name="video_duration" class="form-control col-md-7 col-xs-12"  >00:00:00
                                        @if ($errors->has('video_duration'))
                                            <span class="help-block">{{ $errors->first('video_duration') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.header') }}</label>
                                    <div class=" col-lg-8 col-md-9 col-sm-12">
                                      <textarea  class="form-control {{ $errors->has('header') ? ' is-invalid' : '' }}"
                                      name="header" placeholder="">{{ old('header', $translation->header ?? '') }}</textarea>
                                      <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                                      @if ($errors->has('header'))<span class="invalid-feedback">{{ $errors->first('header') }}</span>@endif
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_description') }}</label>
                                        <div class=" col-lg-8 col-md-9 col-sm-12">
                                          <textarea  class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}"
                                          name="meta_description" placeholder="">{{ old('meta_description', $translation->meta_description ?? '') }}</textarea>
                                          <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                                          @if ($errors->has('meta_description'))<span class="invalid-feedback">{{ $errors->first('meta_description') }}</span>@endif
                                        </div>
                                </div>

                                <div class="row form-group">
                                    <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_keywords') }}</label>
                                    <div class=" col-lg-8 col-md-9 col-sm-12">
                                      <textarea  class="form-control {{ $errors->has('meta_keywords') ? ' is-invalid' : '' }}"
                                      name="meta_keywords" placeholder="">{{ old('meta_keywords', $translation->meta_keywords ?? '') }}</textarea>
                                      <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                                      @if ($errors->has('meta_keywords'))<span class="invalid-feedback">{{ $errors->first('meta_keywords') }}</span>@endif
                                    </div>
                                </div>


                                <div class="row form-group">
                                    <x-admin.is-active dataValue="{{ $translation->trans_status ?? null }}"/>
                                </div>
                                <div class="row form-group">
                                  <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.sort') }} </label>
                                  <div class=" col-lg-4 col-md-9 col-sm-12">
                                      <input type="number" class="form-control {{ $errors->has('sort') ? ' is-invalid' : '' }}" required
                                      value="{{ old('sort', $fields->sort) }}" name="sort" placeholder="{{ __('words.sort') }}">
                                    <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                                    @if ($errors->has('sort'))<span class="invalid-feedback">{{ $errors->first('sort') }}</span>@endif
                                  </div>
                                </div>

                                <div class="row form-group">
                                  <label class="col-form-label col-lg-3 col-sm-12">عدد المحاولات</label>
                                  <div class=" col-lg-4 col-md-9 col-sm-12">
                                      <input type="number" class="form-control {{ $errors->has('max_trys') ? ' is-invalid' : '' }}" required
                                        value="{{ old('max_trys', $fields->max_trys) }}" name="max_trys" placeholder="عدد المحاولات">
                                      @if ($errors->has('max_trys'))<span class="invalid-feedback">{{ $errors->first('max_trys') }}</span>@endif
                                  </div>
                                </div>

                            </div>
                        </div>

                          <div class="row">

                              <div class="col-md-6">
                                <div class="form-group{{ $errors->has('exam_approved') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_approved"> {{ __('words.exam_approved') }} <span class="required"> *</span> </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name="exam_approved" class="form-control col-md-6 col-sm-6 col-xs-12">
                                            <option {{ old('exam_approved', $fields->exam_approved) == 1 ? 'selected' : '' }} value="1">{{ __('words.active') }}</option>
                                            <option {{ old('exam_approved', $fields->exam_approved) == 0 ? 'selected' : '' }} value="0">{{ __('words.not_active') }}</option>
                                        </select>
                                        @if ($errors->has('exam_approved'))
                                            <span class="help-block">{{ $errors->first('exam_approved') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('exam_at') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exam_at"> {{ __('words.exam_at') }}  <span class="required"> *</span> </label>
                                    <div class=" col-md-6 col-sm-6 col-xs-12">
                                        <input class="form-control" type="datetime-local" name="exam_at" value="{{old('exam_at', $fields->exam_at)}}">
                                        @if ($errors->has('exam_at'))
                                            <span class="help-block">{{ $errors->first('exam_at') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 alert alert-danger text-center" style="margin: 3px 127px;">
                                      يتم احتساب الوقت باستخدام UTC <br>
                                      اي ان الساعة الان {{date('H:i:s')}}
                                    </div>
                                </div>
                              </div>

                          </div>

                          <div class="form-group row">
                              <label class="col-form-label col-lg-1 col-sm-12">{{ __('words.html') }}</label>
                              <div class=" col-lg-11 col-md-9 col-sm-12">
                                <x-inputs.ckeditor name="html" data="{!!  file_exists('storage/app/public/'.($translation->description ?? 'nofile')) == true ? file_get_contents('storage/app/public/'.($translation->description ?? '')) : '' !!}" />
                                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                                @if ($errors->has('html'))<span class="invalid-feedback">{{ $errors->first('html') }}</span>@endif
                              </div>
                          </div>
                        </div>


                      <div class="col-md-12">
                        <div class="form-group row">
                          <div class="col-lg-1"></div>
                          <div class="col-lg-11">

                            <button type="submit" class="btn btn-success"> @lang('core.edit') </button>
                            <br><br><br>
                          </div>
                        </div>
                      </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
