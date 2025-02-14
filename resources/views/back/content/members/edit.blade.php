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

                        <h2> {{ $fields->name.' | '.__('core.edit') }} </h2>
                        <a class="btn btn-success pull-right" href="{{route('dashboard.members.create')}}"> @lang('core.add') </a>
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
                            ['name' => $fields->name.' | '.__('core.edit')]]
                        ])

                        <hr>
                        <form method="post" action="{{ route('dashboard.members.update', ['member' => $fields->id]) }}"  class='form-horizontal form-label-left' enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" name="_method" value="PUT">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name"> Name <span class="required">*</span>
                                        </label>
                                        <div class=" col-md-8 col-sm-8 col-xs-12">
                                            <input type="text" value="{{ Request::old('name') ?: ''.$fields->name.'' }}" id="name" name="name" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('name'))
                                                <span class="help-block">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email"> Email <span class="required">*</span>
                                        </label>
                                        <div class=" col-md-8 col-sm-8 col-xs-12">
                                            <input type="email" value="{{ Request::old('email') ?: ''.$fields->email.'' }}" id="email" name="email" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('email'))
                                                <span class="help-block">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="phone"> Phone
                                        </label>
                                        <div class=" col-md-8 col-sm-8 col-xs-12">
                                            <input type="phone" value="{{ Request::old('phone') ?: ''.$fields->phone.'' }}" id="phone" name="phone" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('phone'))
                                                <span class="help-block">{{ $errors->first('phone') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="birthday"> Birthday
                                        </label>
                                        <div class=" col-md-8 col-sm-8 col-xs-12">
                                            <input type="birthday" value="{{ Request::old('birthday') ?: ''.$fields->birthday.'' }}" id="birthday" name="birthday" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('birthday'))
                                                <span class="help-block">{{ $errors->first('birthday') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="gender"> Gender <span class="required"> *</span>
                                        </label>
                                        <div class=" col-md-8 col-sm-8 col-xs-12">
                                            <select name="gender" class=" form-control">
                                                @foreach(['0' => 'Unknown','1' => 'Male','2' => 'Female'] as $id => $title)
                                                    <option {{ (Request::old('gender') && Request::old('gender') == $id) || $fields->gender == $id ? 'selected' : '' }} value="{{ $id }}"> {{ $title }} </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('gender'))
                                                <span class="help-block">{{ $errors->first('gender') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="password"> Password </label>
                                        <div class=" col-md-8 col-sm-8 col-xs-12">
                                            <input type="password" id="password" name="password" autocomplete="new-password" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('password'))
                                                <span class="help-block">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="password_confirmation"> Password Confirmation </label>
                                        <div class=" col-md-8 col-sm-8 col-xs-12">
                                            <input type="password_confirmation" id="password_confirmation" name="password_confirmation" class="form-control col-md-7 col-xs-12">
                                            @if ($errors->has('password_confirmation'))
                                                <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="avatar"> Avatar </label>
                                        <div class=" col-md-8 col-sm-8 col-xs-12">
                                            <input type="file" value="{{ Request::old('avatar') ?: '' }}" id="avatar" name="avatar" class="form-control col-md-7 col-xs-12" accept="image/*" >
                                            @if ($errors->has('avatar'))
                                                <span class="help-block">{{ $errors->first('avatar') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-4">
                                        <input type="hidden" name="_token" value="{{ Session::token() }}">
                                        <button type="submit" class="btn btn-primary"> @lang('core.save') </button>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <img src="{{ $fields->avatar_path ? url($fields->avatar_path) : '' }}" class="img-thumbnail img-responsive" alt="">
                                </div>
                            </div>

                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
