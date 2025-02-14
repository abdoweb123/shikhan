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
                    <h2> ارسال بريد الكترونى {{ $currentQuery }}</h2>
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

                    <div class="clearfix"></div>

                    <div class="row">
                      <div class=" col-lg-12 col-md-12 col-sm-12">
                        <form method="post" action="{{ route('dashboard.send_emails.update.details', ['id' => $data->id]) }}" class="form-horizontal form-label-left" enctype="multipart/form-data">
                          {{ csrf_field() }}
                          <x-inputs.ckeditor name="message" data="{{ $data->message }}" height="800"/>
                          @if ($errors->has('message'))<span class="invalid-feedback">{{ $errors->first('message') }}</span>@endif
                          <input type="submit" value="update">
                        </form>
                      </div>
                    </div>

                </div>
            </div>
        </div>
    </div>





</div>

@stop
