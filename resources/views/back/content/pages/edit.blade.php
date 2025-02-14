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
                    <h2> {{ __('core.edit') }}  {{ $translation->title?? $data->title_general }}  &nbsp;  {{ request()->query('language') }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    {{--
                    @if(session()->has('success'))
                        <div class="alert alert-success text-center">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    --}}

                    {{--
                    @include('back.includes.breadcrumb',['routes' => [
                        ['slug' => route('dashboard.courses.index',$site->alias),'name' => $site->name],
                        ['name' => __('core.add')]]
                    ])
                    --}}
                    <x-buttons.but_back link="{{ route( 'dashboard.pages.index' ) }} "/>
                    @include('back.includes.page-alert')
                    <hr>

<!-- form -->
<form class="" enctype="multipart/form-data" action="{{route('dashboard.pages.update' , ['id' => $data->id ])}}"  method="post">

  <input name="_method" type="hidden" value="PUT">

    {{ csrf_field() }}
    <input name="language" type="hidden" value="{{ request()->query('language') }}">


    <div class="form-group row{{ $errors->has('title') ? ' has-error' : '' }}">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"> Title <span class="required">*</span></label>
        <div class=" col-md-6 col-sm-6 col-xs-12">
            <input type="text" value="{{ old('title', $translation?->title ) }}" id="title" name="title" required maxlength="150" class="form-control col-md-7 col-xs-12">
            @if ($errors->has('title'))
                <span class="help-block">{{ $errors->first('title') }}</span>
            @endif
        </div>
    </div>


    <input type="hidden" name="parent_id" value="0">

    {{--<x-admin.is-active dataValue="{{ $data->is_active }}"/>--}}


      <div class="form-group row{{ $errors->has('image') ? ' has-error' : '' }}">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="logo"> image </label>
          <div class=" col-md-6 col-sm-6 col-xs-12">
              <input type="file"  id="image" name="image" class="form-control col-md-7 col-xs-12" accept="image/*" >
              @if ($errors->has('image'))
                  <span class="help-block">{{ $errors->first('image') }}</span>
              @endif
              <br>
              <img src="{{ $translation?->imagePath() }}" style="max-width: 200px;">
          </div>

      </div>


        @if($data->id == 1)
          <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.video') }}</label>
            <div class=" col-lg-4 col-md-9 col-sm-12">
                <input type="text" class="form-control {{ $errors->has('video') ? ' is-invalid' : '' }}" maxlength="500"
                value="{{ old('video' , !($data->page_info->isEmpty()) ? $data->page_info->first()->video : '' ) }}" name="video" placeholder="">

              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('video'))<span class="invalid-feedback">{{ $errors->first('video') }}</span>@endif
            </div>
          </div>
        @endif


          <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.description') }}</label>
            <div class=" col-lg-8 col-md-9 col-sm-12">
              <x-inputs.ckeditor name="description"
                data="{!! $translation?->description ?
                      \Illuminate\Support\Facades\Storage::exists($translation?->description) ?
                        \Illuminate\Support\Facades\Storage::get($translation?->description)
                        : ''
                      : ''
                    !!}" />

{{--                <x-inputs.ckeditor name="description"--}}
{{--                                   data="--}}
{{--                    @if(file_exists('storage/'.$translation->description) == true)--}}
{{--                    {!!  file_get_contents('storage/'.$translation->description) !!}--}}
{{--                @endif" />--}}

              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('html'))<span class="invalid-feedback">{{ $errors->first('html') }}</span>@endif
            </div>
          </div>





          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_description') }}</label>
              <div class=" col-lg-8 col-md-9 col-sm-12">
                <textarea  class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}"
                name="meta_description" maxlength="500" placeholder="">{{ old('meta_description' , $translation?->meta_description) }}</textarea>
                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                @if ($errors->has('meta_description'))<span class="invalid-feedback">{{ $errors->first('meta_description') }}</span>@endif
              </div>
          </div>


          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.header') }}</label>
              <div class=" col-lg-8 col-md-9 col-sm-12">
                <textarea  class="form-control {{ $errors->has('header') ? ' is-invalid' : '' }}"
                name="header" maxlength="500" placeholder="">{{ old('header' , $translation?->header) }}</textarea>
                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                @if ($errors->has('header'))<span class="invalid-feedback">{{ $errors->first('header') }}</span>@endif
              </div>
          </div>



          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_keywords') }}</label>
              <div class=" col-lg-8 col-md-9 col-sm-12">
                <textarea  class="form-control {{ $errors->has('meta_keywords') ? ' is-invalid' : '' }}"
                name="meta_keywords" maxlength="500" placeholder="">{{ old('meta_keywords' , $translation?->meta_keywords) }}</textarea>
                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                @if ($errors->has('meta_keywords'))<span class="invalid-feedback">{{ $errors->first('meta_keywords') }}</span>@endif
              </div>
          </div>

          <x-admin.is-active dataValue="{{ $translation->is_active }}"/>


          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <input type="hidden" name="_token" value="{{ Session::token() }}">
              <button type="submit" class="btn btn-success"> @lang('core.update') </button>
          </div>



      </form>
  </div>
</div>
</div>
</div>
</div>




@endsection
