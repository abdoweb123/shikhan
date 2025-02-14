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
                <x-buttons.but_back link="{{ route('dashboard.diplomas.index') }}"/>
              </div>
            </h3>

            @include('back.includes.page-alert')
          </div>
        </div>


        <div class="kt-portlet__body">
          <div class="kt-section kt-section--first">
            {{--<div class="col kt-align-right">@include('admin.p_admin_data.development_programs.export' , [ 'data' => $data ])</div>--}}



<!-- form -->
<form action="{{route('dashboard.diplomas.update', ['id' => $data->id ])}}"  method="post" enctype="multipart/form-data" class="kt_form_1">


    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PUT">
    <input name="language" type="hidden" value="{{ request()->query('language') }}">


    <div class="row">
      <div class="col-md-6">

          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>
              <div class=" col-lg-9 col-md-9 col-sm-12">
                  <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" required maxlength="150"
                    value="{{ old('title', $translation->name ?? '') }}" name="title" placeholder="">
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('title'))<span class="invalid-feedback">{{ $errors->first('title') }}</span>@endif
              </div>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.alias') }} *</label>
              <div class=" col-lg-9 col-md-9 col-sm-12">
                  <input type="text" class="form-control {{ $errors->has('alias') ? ' is-invalid' : '' }}" required maxlength="alias"
                    value="{{ old('alias',$translation->slug ?? '') }}" name="alias" placeholder="">
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('alias'))<span class="invalid-feedback">{{ $errors->first('alias') }}</span>@endif
              </div>
          </div>

          <div class="form-group row">
            <x-admin.sites-tree :tree="$sitesTreeExcept" :dataValue="$data->parent_id"/>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.image') }}</label>
              <div class="col-lg-9 col-md-9 col-sm-12">
                <input type="file" name="image" id="image" class="dropify img_edit" data-default-file="{{ $translation->ImagePath ?? '' }}"/>
                <input type="checkbox" name="image_remove" value="{{ old('image_remove') }}"> حذف الصورة
              </div>
          </div>

          <div class="form-group row">
              <x-admin.is-active dataValue="{{ $translation->trans_status ?? null }}"/>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.brief') }}</label>
              <div class="col-lg-9 col-md-9 col-sm-12">
                <textarea class="form-control {{ $errors->has('brief') ? ' is-invalid' : '' }}" maxlength="300" rows="5"
                  name="brief" placeholder="">{{ old('brief',$translation->brief ?? '') }}</textarea>
                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                @if ($errors->has('brief'))<span class="invalid-feedback">{{ $errors->first('brief') }}</span>@endif
              </div>
          </div>

      </div>
      <!-- half -->
      <div class="col-md-6">
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.header') }}</label>
            <div class=" col-lg-9 col-md-9 col-sm-12">
              <textarea  class="form-control {{ $errors->has('header') ? ' is-invalid' : '' }}" rows="4"
                name="header" placeholder="">{{ old('header', $translation->header ?? '') }}</textarea>
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('header'))<span class="invalid-feedback">{{ $errors->first('header') }}</span>@endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_description') }}</label>
            <div class=" col-lg-9 col-md-9 col-sm-12">
                <textarea  class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}" rows="6"
                  name="meta_description" placeholder="">{{ old('meta_description',$translation->meta_description ?? '') }}</textarea>
                @if ($errors->has('meta_description'))<span class="invalid-feedback">{{ $errors->first('meta_description') }}</span>@endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_keywords') }}</label>
            <div class="col-lg-9 col-md-9 col-sm-12">
              <textarea  class="form-control {{ $errors->has('meta_keywords') ? ' is-invalid' : '' }}" rows="4"
              name="meta_keywords" placeholder="">{{ old('meta_keywords',$translation->meta_keywords ?? '') }}</textarea>
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('meta_keywords'))<span class="invalid-feedback">{{ $errors->first('meta_keywords') }}</span>@endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12" style="padding-top: 10px;">{{ __('words.sort') }}</label>
            <div class="col-lg-3 col-md-9 col-sm-12" style="padding-top: 10px;">
                <input type="number" class="form-control {{ $errors->has('sort') ? ' is-invalid' : '' }}" required
                  value="{{ old('sort',$data->sort) }}" name="sort" placeholder="{{ __('words.sort') }}">
                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                @if ($errors->has('sort'))<span class="invalid-feedback">{{ $errors->first('sort') }}</span>@endif
            </div>
        </div>

      </div>


      <div class="col-md-12">
          <div class="form-group row">
            <label class="col-form-label col-lg-1 col-sm-12">{{ __('words.description') }}</label>
            <div class="col-lg-11 col-md-9 col-sm-12">
              <x-inputs.ckeditor name="description" data="{{ old('description',$translation->description ?? '') }}" />
              @if ($errors->has('description'))<span class="invalid-feedback">{{ $errors->first('description') }}</span>@endif
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
<x-admin.datatable.footer-single-report-js/>
@endsection

@endsection
