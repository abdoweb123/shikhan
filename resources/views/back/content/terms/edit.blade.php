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
            {{--<div class="col kt-align-right">@include('admin.p_admin_data.development_programs.export' , [ 'data' => $term ])</div>--}}



<!-- form -->
<form action="{{route('dashboard.terms.update', ['id' => $term->id ])}}"  method="post" enctype="multipart/form-data" class="kt_form_1">


    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PUT">
    <input name="language" type="hidden" value="{{ request()->query('language') }}">


    <div class="row">
      <div class="col-md-6">

          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>
              <div class=" col-lg-9 col-md-9 col-sm-12">
                  <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" required maxlength="150"
                    value="{{ old('title', $termTranslation->name ?? '') }}" name="title" placeholder="">
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('title'))<span class="invalid-feedback">{{ $errors->first('title') }}</span>@endif
              </div>
          </div>


          <div class="form-group row">
              <x-admin.is-active dataValue="{{ $termTranslation->trans_status ?? null }}"/>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12" style="padding-top: 10px;">{{ __('words.sort') }}</label>
              <div class="col-lg-3 col-md-9 col-sm-12" style="padding-top: 10px;">
                  <input type="number" class="form-control {{ $errors->has('sort') ? ' is-invalid' : '' }}" required
                         value="{{ old('sort',$term->sort) }}" name="sort" placeholder="{{ __('words.sort') }}">
                  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                  @if ($errors->has('sort'))<span class="invalid-feedback">{{ $errors->first('sort') }}</span>@endif
              </div>
          </div>

      </div>
      <!-- half -->



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
