@extends('back.layouts.auth_app')

@section('title', __('core.login'))
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-2"></div>
            <div class="col-md-6">
                <div class="card">
                    @include('back.includes.page-alert')
                    <div class="card-header" data-background-color="green">
                        <h4 class="title text-center">{{ __('core.titele_login') }} Teacher</h4>
                    </div>
                    <div class="card-content">
                        <form method="POST" action="{{ route('login.post.teacher') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group label-floating">
                                        <label for="email" class="control-label">{{ __('field.email') }}</label>
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group label-floating">
                                        <label for="password" class="control-label">{{ __('field.password') }}</label>
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('core.stay_logged_in') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('core.login') }}
                                    </button>
                                    <a class="btn btn-info" href="{{ route('dashboard.password.request') }}">
                                        {{ __('core.forgot_password') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <a href="{{route('dashboard.login')}}">Are You Admin?</a>
                </div>
            </div>
        </div>
    </div>
@endsection
