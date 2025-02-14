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
                    <h2>تعديل اشعار بعد التسجيل فى الموقع</h2>
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

                    <form method="post" action="{{ route('dashboard.send_notifications.inner.store_template') }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
                          {{ csrf_field() }}

                          <div class="form-group row">
                            <div class=" col-lg-12 col-md-9 col-sm-12">
                              <x-inputs.ckeditor name="message" data="{{ $messageAfterRegestration }}" />
                                @if ($errors->has('message'))<span class="invalid-feedback">{{ $errors->first('message') }}</span>@endif
                            </div>
                          </div>

                          <div class="form-group row">
                            <div class=" col-lg-12 col-md-9 col-sm-12">
                              <button type="submit" class="btn btn-success">حفظ</button>
                            </div>
                          </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


</div>

@stop
