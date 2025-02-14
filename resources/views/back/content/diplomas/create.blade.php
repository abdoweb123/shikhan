@extends('back/layouts.app')
@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="kt-portlet">

        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
              {{ __('words.add') }} &nbsp;&nbsp;&nbsp; <x-buttons.but_back link="{{ route( 'dashboard.diplomas.index' ) }} "/>
            </h3>
          </div>
        </div>


        <div class="kt-portlet__body">
          <div class="kt-section kt-section--first">

<!-- form -->
<form class="kt_form_1" enctype="multipart/form-data" action="{{ route( 'dashboard.diplomas.store' ) }}" method="post">
    {{ csrf_field() }}

    <div class="row">
      <div class="col-md-6">

          <div class="form-group row">
            <x-admin.languages.active-languages/>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>
              <div class=" col-lg-9 col-md-9 col-sm-12">
                  <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" required maxlength="150"
                  value="{{ old('title') }}" name="title" placeholder="">
                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                @if ($errors->has('title'))<span class="invalid-feedback">{{ $errors->first('title') }}</span>@endif
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
            <x-admin.sites-tree :tree="$sitesTree"/>
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
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.brief') }}</label>
              <div class=" col-lg-9 col-md-9 col-sm-12">
                <textarea  class="form-control {{ $errors->has('brief') ? ' is-invalid' : '' }}" maxlength="300"
                name="brief" placeholder="">{{ old('brief') }}</textarea>
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
              name="header" placeholder="">{{ old('header') }}</textarea>
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('header'))<span class="invalid-feedback">{{ $errors->first('header') }}</span>@endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_description') }}</label>
            <div class=" col-lg-9 col-md-9 col-sm-12">
              <textarea  class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}" rows="5"
              name="meta_description" placeholder="">{{ old('meta_description') }}</textarea>
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('meta_description'))<span class="invalid-feedback">{{ $errors->first('meta_description') }}</span>@endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_keywords') }}</label>
            <div class=" col-lg-9 col-md-9 col-sm-12">
              <textarea  class="form-control {{ $errors->has('meta_keywords') ? ' is-invalid' : '' }}" rows="4"
              name="meta_keywords" placeholder="">{{ old('meta_keywords') }}</textarea>
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('meta_keywords'))<span class="invalid-feedback">{{ $errors->first('meta_keywords') }}</span>@endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.sort') }} </label>
            <div class=" col-lg-4 col-md-9 col-sm-12">
                <input type="number" class="form-control {{ $errors->has('sort') ? ' is-invalid' : '' }}" required
                value="{{ old('sort') }}" name="sort" placeholder="{{ __('words.sort') }}">
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('sort'))<span class="invalid-feedback">{{ $errors->first('sort') }}</span>@endif
            </div>
        </div>

      </div>

      <div class="col-md-12">
        <div class="form-group row">
            <label class="col-form-label col-lg-1 col-sm-12">{{ __('words.description') }}</label>
            <div class=" col-lg-11 col-md-9 col-sm-12">
              <!-- <textarea  class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                  name="description" placeholder="" style="margin: 0px 45.6562px 0px 0px; width: 668px; height: 218px;">{{ old('description') }}</textarea> -->
              <x-inputs.ckeditor name="description" data="{{ old('description') }}" />
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
<x-admin.dropify-js/>
@endsection

@endsection
