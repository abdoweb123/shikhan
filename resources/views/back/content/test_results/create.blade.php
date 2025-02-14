@extends('back/layouts.app')

@section('content')

<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
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
                    <h2> @lang('core.add') </h2>

                    <a class="btn btn-success pull-right" href="{{route('dashboard.test_results.index',['site' => $site->alias,'course' => $course_id])}}">Back</a>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if(session()->has('success'))
                        <div class="alert alert-success text-center">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    @include('back.includes.breadcrumb',['routes' => [
                        ['slug' => route('dashboard.courses.index',$site->alias),'name' => $site->name],
                        ['slug' => route('dashboard.test_results.index',['site' => $site->alias,'course' => $course_id]),'name' => $course->name.' | '.__('meta.title.test_results')],
                        ['name' => __('core.add')]]
                    ])
                    <hr>

                    <form method="post" action="{{ route('dashboard.test_results.store',['site' => $site->alias,'course' => $course_id]) }}" data-parsley-validate class="form-horizontal form-label-left">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"> Name <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('name') ?: '' }}" id="name" name="name" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('name'))
                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email"> Email <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('email') ?: '' }}" id="email" name="email" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('email'))
                                    <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone"> Phone <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('phone') ?: '' }}" id="phone" name="phone" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('phone'))
                                    <span class="help-block">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('degree') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="degree"> Degree <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('degree') ?: '' }}" id="degree" name="degree" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('degree'))
                                    <span class="help-block">{{ $errors->first('degree') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('locale') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="locale"> Language <span class="required"> *</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <select name="locale" class=" form-control col-md-6 col-sm-6 col-xs-12">
                                    @if( Request::old('locale') )
                                        @foreach($languages as $locale => $name)
                                            <option @if( $locale == Request::old('locale') ) selected value="{{ $locale }}" @endif >{{ $name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($languages as $locale => $name)
                                            <option value="{{ $locale }}"> {{ $name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('locale'))
                                    <span class="help-block">{{ $errors->first('locale') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                            <button type="submit" name="submit" value="create" class="btn btn-info"> @lang('core.add') </button>
                            <button type="submit" name="submit" value="create_send" class="btn btn-success"> @lang('core.add') & @lang('core.send') </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
