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
                        ['slug' => route('dashboard.members.index'),'name' => __('meta.title.members')],
                        ['name' => __('core.add')]]
                    ])
                    <hr>
                    <form method="post" action="{{ route('dashboard.members.store') }}" data-parsley-validate class="form-horizontal form-label-left" enctype="multipart/form-data">
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
                                <input type="email" value="{{ Request::old('email') ?: '' }}" id="email" name="email" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('email'))
                                    <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone"> Phone <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="phone" value="{{ Request::old('phone') ?: '' }}" id="phone" name="phone" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('phone'))
                                    <span class="help-block">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="birthday"> Birthday <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="date" value="{{ Request::old('birthday') ?: '' }}" id="birthday" name="birthday" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('birthday'))
                                    <span class="help-block">{{ $errors->first('birthday') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gender"> Gender <span class="required"> *</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <select name="gender" class=" form-control">
                                    @foreach(['0' => 'Unknown','1' => 'Male','2' => 'Female'] as $id => $title)
                                        <option {{ Request::old('gender') && Request::old('gender') == $id ? 'selected' : '' }} value="{{ $id }}"> {{ $title }} </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('gender'))
                                    <span class="help-block">{{ $errors->first('gender') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password"> Password <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="password" value="{{ Request::old('password') ?: '' }}" id="password" name="password" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('password'))
                                    <span class="help-block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password_confirmation"> Password Confirmation <span class="required">*</span>
                            </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="password" value="{{ Request::old('password_confirmation') ?: '' }}" id="password_confirmation" name="password_confirmation" class="form-control col-md-7 col-xs-12">
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="avatar"> Avatar </label>
                            <div class=" col-md-6 col-sm-6 col-xs-12">
                                <input type="file" value="{{ Request::old('avatar') ?: '' }}" id="avatar" name="avatar" class="form-control col-md-7 col-xs-12" accept="image/*" >
                                @if ($errors->has('avatar'))
                                    <span class="help-block">{{ $errors->first('avatar') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                            <button type="submit" class="btn btn-success">Add Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
