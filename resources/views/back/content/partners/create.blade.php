@extends('back/layouts.app')
@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="kt-portlet">

        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
              {{ __('words.add') }} &nbsp;&nbsp;&nbsp; <x-buttons.but_back link="{{ route( 'dashboard.partners.index' ) }} "/>
            </h3>
          </div>
        </div>


        <div class="kt-portlet__body">
          <div class="kt-section kt-section--first">

    <!-- form -->
    <form class="kt_form_1" enctype="multipart/form-data" action="{{ route( 'dashboard.partners.store' ) }}" method="post">
        {{ csrf_field() }}

        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <x-admin.languages.active-languages/>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>
                <div class=" col-lg-9 col-md-9 col-sm-12">
                    <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required maxlength="150"
                    value="{{ old('name') }}" name="name" placeholder="">
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('name'))<span class="invalid-feedback">{{ $errors->first('name') }}</span>@endif
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.alias') }} *</label>
                <div class=" col-lg-9 col-md-9 col-sm-12">
                    <input type="text" class="form-control {{ $errors->has('alias') ? ' is-invalid' : '' }}" required maxlength="alias"
                    value="{{ old('alias') }}" name="alias" placeholder="">
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('alias'))<span class="invalid-feedback">{{ $errors->first('alias') }}</span>@endif
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.image') }}</label>
                <div class="col-lg-9 col-md-9 col-sm-12">
                  <input type="file" name="image" id="image" class="dropify img_edit"/>
                </div>
            </div>
            <div class="form-group row">
                <x-admin.is-active/>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.birthdate') }}</label>
                <div class="col-lg-9 col-md-9 col-sm-12">
                  <input type="date" value="{{ Request::old('birthdate') ?: '' }}" id="date" name="birthdate" class="form-control col-md-7 col-xs-12">
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
                  name="header" placeholder="">{{ old('header') }}</textarea>
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('header'))<span class="invalid-feedback">{{ $errors->first('header') }}</span>@endif
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_description') }}</label>
                <div class=" col-lg-8 col-md-9 col-sm-12">
                  <textarea  class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}"
                  name="meta_description" placeholder="">{{ old('meta_description') }}</textarea>
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('meta_description'))<span class="invalid-feedback">{{ $errors->first('meta_description') }}</span>@endif
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_keywords') }}</label>
                <div class=" col-lg-8 col-md-9 col-sm-12">
                  <textarea  class="form-control {{ $errors->has('meta_keywords') ? ' is-invalid' : '' }}"
                  name="meta_keywords" placeholder="">{{ old('meta_keywords') }}</textarea>
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('meta_keywords'))<span class="invalid-feedback">{{ $errors->first('meta_keywords') }}</span>@endif
                </div>
            </div>

          </div>
          <div class="col-md-12">
            <div class="form-group row">
               <label class="col-form-label col-lg-1 col-sm-12">{{ __('words.html') }}</label>
               <div class=" col-lg-10 col-md-9 col-sm-12">
                 <x-inputs.ckeditor name="html" data="" />
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
<script>
$(document).ready(function() {
    $('.select_2').select2();
});
</script>
<x-admin.dropify-js/>
@endsection

@endsection
