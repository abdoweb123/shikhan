@extends('back/layouts.app')

@section('title')
{{__('words.term_title')}}
@stop

@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="kt-portlet">
        <div class="kt-portlet__body">
          <div class="kt-section kt-section--first">

<!-- form -->
<form class="kt_form_1" enctype="multipart/form-data" action="{{ route( 'dashboard.terms.store' ) }}" method="post">
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

{{--          <div class="form-group row">--}}
{{--            <x-admin.sites-tree :tree="$sitesTree"/>--}}
{{--          </div>--}}



          <div class="form-group row">
              <x-admin.is-active/>
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
<x-admin.dropify-js/>
@endsection

@endsection
