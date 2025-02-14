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
                {{ __('words.edit') }} &nbsp;&nbsp;&nbsp; <x-buttons.but_back link="{{ route('dashboard.social.index') }}"/>
              </div>
            </h3>

          </div>
        </div>


        <div class="kt-portlet__body">
          <div class="kt-section kt-section--first">
            {{--<div class="col kt-align-right">@include('admin.p_admin_data.development_programs.export' , [ 'data' => $data ])</div>--}}

<!-- form -->
<form class="kt_form_1" enctype="multipart/form-data"
  action="{{route('dashboard.social.update' , ['id' => $data->id ])}}"  method="post">

  <input name="_method" type="hidden" value="PUT">


    {{ csrf_field() }}
    <div class="form-group row">
        <div class="col-md-6">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>
          <div class=" col-lg-4 col-md-9 col-sm-12">
              <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" required maxlength="250"
              value="{{ old('title',$data->title) }}" name="title" placeholder="">
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('title'))<span class="invalid-feedback">{{ $errors->first('title') }}</span>@endif
          </div>
      </div>
      <div class="col-md-6">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.link') }}</label>
          <div class=" col-lg-8 col-md-9 col-sm-12">
            <textarea  class="form-control {{ $errors->has('link') ? ' is-invalid' : '' }}"
            name="link" placeholder="">{{ old('link',$data->link) }}</textarea>
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('link'))<span class="invalid-feedback">{{ $errors->first('link') }}</span>@endif
          </div>
      </div>

    </div>
      <div class="form-group row">

          <a href="https://fontawesome.com/v4.7/icons/" target="_blank"> serch icons</a>
      </div>
    <div class="form-group row">

      <div class="col-md-6">
          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.icon') }}</label>
          <div class=" col-lg-8 col-md-9 col-sm-12">
            <textarea  class="form-control {{ $errors->has('icon') ? ' is-invalid' : '' }}"
            name="icon" placeholder="">{{ old('icon',$data->icon) }}</textarea>
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('icon'))<span class="invalid-feedback">{{ $errors->first('icon') }}</span>@endif
          </div>
      </div>
    </div>
    <x-buttons.but_submit/>

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
