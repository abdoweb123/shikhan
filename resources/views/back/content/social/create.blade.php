@extends('back/layouts.app')
@section('content')
|<style>
  .form-group.row{
    text-align: center;
  }
  .form-group.row a {
    text-align: center;
    padding: 10px;
    background-color: #3566bdc4;
    border-radius: 15px;
    color: #fff;
  }
</style>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="kt-portlet">

        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
              {{ __('words.add') }} &nbsp;&nbsp;&nbsp; <x-buttons.but_back link="{{ route( 'dashboard.social.index' ) }} "/>
            </h3>
          </div>
        </div>


        <div class="kt-portlet__body">
          <div class="kt-section kt-section--first">

<!-- form -->
<form class="kt_form_1" enctype="multipart/form-data" action="{{ route( 'dashboard.social.store' ) }}" method="post">
    {{ csrf_field() }}

<!--name','slug','description','header','meta_description','meta_keywords'];-->
<!--    public $translationModel = 'App\Translations\SiteTranslation';-->
<!--    protected $fillable = ['name','alias','languages','logo','status',-->

      <div class="form-group row">

        {{--
        <div class="form-group row">
          <x-admin.languages.active-languages/>
        </div>
        --}}

          <div class="col-md-6">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>
            <div class=" col-lg-4 col-md-9 col-sm-12">
                <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" required maxlength="250"
                value="{{ old('title') }}" name="title" placeholder="">
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('title'))<span class="invalid-feedback">{{ $errors->first('title') }}</span>@endif
            </div>
        </div>
        <div class="col-md-6">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.link') }}</label>
            <div class=" col-lg-8 col-md-9 col-sm-12">
              <textarea  class="form-control {{ $errors->has('link') ? ' is-invalid' : '' }}"
              name="link" placeholder="">{{ old('link') }}</textarea>
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
              name="icon" placeholder="">{{ old('icon') }}</textarea>
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
@endsection

@endsection
