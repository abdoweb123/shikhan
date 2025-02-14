@extends('front.layouts.app')
@section('content')
    <section class="profile text-center site-hero site-sm-hero" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row justify-content-center site-hero-sm-inner">
                <div class="col-md-3">
                    @include('front.units.aside.index')
                </div>
                <div class="col-md-9 card">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="btn1 py-3 px-5 mx-0"> {{ $row['name'] }} </h1>
                            <h2 class="btn1 py-3 px-5 mx-0"> @lang('title.activity') </h2>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            {!! $row->activity !!}
                            <h2 class="btn2 py-2 px-2"> @lang('title.upload_file') </h2>
                            <section id="contacts">
                                <div class="form mb-5">
                                    <form action="{{ route('lessons.activity',$row['id']) }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            {{ Form::label('file',__('field.bugs_title'), ['class' => 'control-label']) }}
                                            {{ Form::textarea('bugs',old('bugs'),['class' => 'form-control','id'=>'bugs','rows' => 4,'placeholder' => __('core.type_here')]) }}
                                            <div class="{{ $errors->has('bugs') ? ' invalid-feedback' : ' valid-feedback' }}">
                                                @if(isset($errors->messages()['bugs']))
                                                @foreach($errors->messages()['bugs'] as $er)
                                                <p>{{ $er }}</p>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('file',__('field.file'), ['class' => 'control-label']) }}
                                            <input class="form-control" type="file" name="file">
                                            <div class="{{ $errors->has('file') ? ' invalid-feedback' : ' valid-feedback' }}">
                                                @if(isset($errors->messages()['file']))
                                                @foreach($errors->messages()['file'] as $er)
                                                <p>{{ $er }}</p>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <button class=" btn btn-info mt-4 " type="submit"> @lang('core.send') </button>
                                    </form>
                                </div>
                            </section>
                        </div>
                        <div class="col-md-12">
                            <div class="text-center">
                                @include('front.units.actions')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
