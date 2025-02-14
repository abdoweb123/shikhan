@extends('back/layouts.app')

@section('css_pagelevel')
<x-admin.datatable.header-css/>
@endsection

@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="kt-portlet">

        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
              <div class="row">
                {{ __('words.edit') }} &nbsp; {{ request()->query('language') }}
                <x-buttons.but_back link="{{ route('dashboard.teachers.index') }}"/>
              </div>
            </h3>

          </div>
        </div>


        <div class="kt-portlet__body">
          <div class="kt-section kt-section--first">
            {{--<div class="col kt-align-right">@include('admin.p_admin_data.development_programs.export' , [ 'data' => $data ])</div>--}}

<!-- form -->
<form class="kt_form_1" enctype="multipart/form-data" action="{{route('dashboard.teachers.update', ['id' => $data->id ])}}" method="post">

  {{ csrf_field() }}
  <input name="_method" type="hidden" value="PUT">
  <input name="language" type="hidden" value="{{ request()->query('language') }}">


  <div class="row">
    <div class="col-md-6">
      <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>
          <div class=" col-lg-9 col-md-9 col-sm-12">
              <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required maxlength="150"
              value="{{ old('name', $translation->title ?? '') }}" name="name" placeholder="">
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('name'))<span class="invalid-feedback">{{ $errors->first('name') }}</span>@endif
          </div>
      </div>
      <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.alias') }} *</label>
          <div class=" col-lg-9 col-md-9 col-sm-12">
              <input type="text" class="form-control {{ $errors->has('alias') ? ' is-invalid' : '' }}" required maxlength="alias"
              value="{{ old('alias', $translation->alias ?? '') }}" name="alias" placeholder="">
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('alias'))<span class="invalid-feedback">{{ $errors->first('alias') }}</span>@endif
          </div>
      </div>
      <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.image') }}</label>
          <div class="col-lg-9 col-md-9 col-sm-12">
            <input type="file" name="image" id="image" class="dropify img_edit" data-default-file="{{ $data->logo_path }}" />
            <input type="checkbox" name="image_remove" value="{{ old('image_remove') }}"> حذف الصورة
          </div>
      </div>
      <div class="form-group row">
          <x-admin.is-active dataValue="{{ $data->is_active }}"/>
      </div>
      <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.birthdate') }}</label>
          <div class="col-lg-9 col-md-9 col-sm-12">
            <input type="date" value="{{ Request::old('birthdate', $data->birthdate) }}" id="date" name="birthdate" class="form-control col-md-7 col-xs-12">
            @if ($errors->has('birthdate'))
                <span class="help-block">{{ $errors->first('birthdate') }}</span>
            @endif
          </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group row">
        <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.header') }}</label>
        <div class=" col-lg-8 col-md-9 col-sm-12">
          <textarea  class="form-control {{ $errors->has('header') ? ' is-invalid' : '' }}"
          name="header" placeholder="">{{ old('header',$translation->header ?? '') }}</textarea>
          <!-- <span class="form-text text-muted">Please enter your full name</span> -->
          @if ($errors->has('header'))<span class="invalid-feedback">{{ $errors->first('header') }}</span>@endif
        </div>
      </div>
      <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_description') }}</label>
          <div class=" col-lg-8 col-md-9 col-sm-12">
            <textarea  class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}"
            name="meta_description" placeholder="">{{ old('meta_description',$translation->meta_description ?? '') }}</textarea>
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('meta_description'))<span class="invalid-feedback">{{ $errors->first('meta_description') }}</span>@endif
          </div>
      </div>
      <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_keywords') }}</label>
          <div class=" col-lg-8 col-md-9 col-sm-12">
            <textarea  class="form-control {{ $errors->has('meta_keywords') ? ' is-invalid' : '' }}"
            name="meta_keywords" placeholder="">{{ old('meta_keywords',$translation->meta_keywords ?? '') }}</textarea>
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('meta_keywords'))<span class="invalid-feedback">{{ $errors->first('meta_keywords') }}</span>@endif
          </div>
      </div>
      <div class="form-group row">
          <x-admin.countries.active-countries :countries="$countries"/>
      </div>
      <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.qualification') }}</label>
          <div class=" col-lg-8 col-md-9 col-sm-12">
            <textarea  class="form-control {{ $errors->has('qualification') ? ' is-invalid' : '' }}"
              name="qualification" placeholder="">{{ old('qualification', $translation->qualification ?? '') }}</textarea>
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('qualification'))<span class="invalid-feedback">{{ $errors->first('qualification') }}</span>@endif
          </div>
      </div>
      <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.specialization') }}</label>
          <div class=" col-lg-8 col-md-9 col-sm-12">
            <textarea  class="form-control {{ $errors->has('specialization') ? ' is-invalid' : '' }}"
              name="specialization" placeholder="">{{ old('specialization', $translation->specialization ?? '') }}</textarea>
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('specialization'))<span class="invalid-feedback">{{ $errors->first('specialization') }}</span>@endif
          </div>
      </div>
      <div class="form-group row">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.position') }}</label>
          <div class=" col-lg-8 col-md-9 col-sm-12">
            <textarea  class="form-control {{ $errors->has('position') ? ' is-invalid' : '' }}"
              name="position" placeholder="">{{ old('position', $translation->position ?? '') }}</textarea>
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('position'))<span class="invalid-feedback">{{ $errors->first('position') }}</span>@endif
          </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group row">
        <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.html') }}</label>
        <div class=" col-lg-8 col-md-9 col-sm-12">
          <x-inputs.ckeditor name="html"

            data="{!!  file_exists('storage/app/public/'.($translation->description ?? 'no_file')) == true ? file_get_contents('storage/app/public/'.($translation->description ?? '')) : '' !!}" />
          <!-- <span class="form-text text-muted">Please enter your full name</span> -->
          @if ($errors->has('html'))<span class="invalid-feedback">{{ $errors->first('html') }}</span>@endif
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="form-group row">
        <div class="col-lg-1"></div>
        <div class="col-lg-11">
          <x-buttons.but_submit/>
          <br><br><br>
        </div>
      </div>
    </div>

  </div>

</form>





          </div>
        </div>
      </div>
    </div>
  </div>
</div>



@section('js_pagelevel')
<x-admin.dropify-js/>
<x-admin.datatable.footer-single-report-js/>
@endsection

@endsection
