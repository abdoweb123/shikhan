@extends('back.layouts.auth_app')

@section('title', __('core.reset_password'))
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-2"></div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" data-background-color="blue">
                        <h4 class="title text-center">{{ __('core.reset_password') }}</h4>
                    </div>
                    <div class="card-content">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('dashboard.password.email') }}">
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
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-info">
                                        {{ __('core.send_password_title') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
