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
                        ['slug' => route('dashboard.sender.index',['site' => $site->alias,'course' => $course_id]),'name' => $course->name.' | '.__('meta.title.sender')],
                        ['name' => __('core.add')]]
                    ])
                    <hr>

                    <form method="post" action="{{ route('dashboard.sender.store',['site' => $site->alias,'course' => $course_id]) }}" data-parsley-validate class="form-horizontal form-label-left">

                        <div class="form-group{{ $errors->has('frequency') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="frequency"> Frequency <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('frequency') ?: '' }}" id="frequency" name="frequency" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('frequency'))
                                    <span class="help-block">{{ $errors->first('frequency') }}</span>
                                @endif
                                <span class="text-mute"> EX: Every 5 minutes (*/5 * * * *)  </span>
                                <pre class="text-mute">
    # Example of job definition:
    # .---------------- minute (0 - 59)
    # |  .------------- hour (0 - 23)
    # |  |  .---------- day of month (1 - 31)
    # |  |  |  .------- month (1 - 12) OR jan,feb,mar,apr ...
    # |  |  |  |  .---- day of week (0 - 6) (Sunday=0 or 7)
    # |  |  |  |  |
    # *  *  *  *  * user-name  command to be executed
                                </pre>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('count') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="count"> Count <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="text" value="{{ Request::old('count') ?: '' }}" id="count" name="count" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('count'))
                                    <span class="help-block">{{ $errors->first('count') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('languages') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="languages">Languages<span class="required"> *</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <select name="languages[]" class=" form-control col-md-6 col-sm-6 col-xs-12" multiple>
                                    @if( Request::old('languages') )
                                        @foreach($languages as $lang => $name)
                                            <option @if( in_array( $lang , Request::old('languages') ) ) selected value="{{ $lang }}" @endif >{{ $name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($languages as $lang => $name)
                                            <option value="{{ $lang }}"> {{ $name }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('languages'))
                                    <span class="help-block">{{ $errors->first('languages') }}</span>
                                @endif
                            </div>
                        </div>
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
