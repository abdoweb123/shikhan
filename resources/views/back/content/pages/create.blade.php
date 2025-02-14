@extends('back/layouts.app')

@section('content')

<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h2> {{ __('core.add') }} </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if(session()->has('success'))
                        <div class="alert alert-success text-center">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    {{--
                    @include('back.includes.breadcrumb',['routes' => [
                        ['slug' => route('dashboard.courses.index',$site->alias),'name' => $site->name],
                        ['name' => __('core.add')]]
                    ])
                    --}}
                    <hr>
                    <form method="post" action="{{ route('dashboard.pages.store') }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">

                        <input type="hidden" name="language" value="ar">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"> Title <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('title') ?: '' }}" id="title" name="title"required maxlength="150" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('title'))
                                    <span class="help-block">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('alias') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title"> Alias <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('alias') ?: '' }}" id="alias" name="alias"required maxlength="150" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('alias'))
                                    <span class="help-block">{{ $errors->first('alias') }}</span>
                                @endif
                            </div>
                        </div>


                        <input type="hidden" name="parent_id" value="0">

                        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="logo"> image </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="file" value="{{ Request::old('image') ?: '' }}" id="image" name="image" class="form-control col-md-7 col-xs-12" accept="image/*" >
                                @if ($errors->has('image'))
                                    <span class="help-block">{{ $errors->first('image') }}</span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group row">
                          <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.description') }}</label>
                          <div class=" col-lg-8 col-md-9 col-sm-12">
                            <x-inputs.ckeditor name="description" />
                            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                            @if ($errors->has('html'))<span class="invalid-feedback">{{ $errors->first('html') }}</span>@endif
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


                        {{--
                        <div class="form-group{{ $errors->has('languages') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="languages">Languages<span class="required"> *</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <select name="languages[]" class=" form-control col-md-6 col-sm-6 col-xs-12" multiple>
                                    @foreach($languages as $lang => $name)
                                        <option {{ Request::old('languages') && in_array($lang,Request::old('languages')) ? 'selected' : '' }} value="{{ $lang }}"> {{ $name }} </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('languages'))
                                    <span class="help-block">{{ $errors->first('languages') }}</span>
                                @endif
                            </div>
                        </div>
                        --}}


                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                            <button type="submit" class="btn btn-success"> @lang('core.add') </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
