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
                            <h1 class="py-3 px-5"> @lang('title.contact_teacher') </h1>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <section id="contacts">
                                <div class="form mb-5">
                                    <form action="{{ route('contact_teacher') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            {{ Form::label('subject',__('field.subject'), ['class' => 'control-label']) }}
                                            {{ Form::text('subject',old('subject'),['class' => 'form-control','id'=>'subject','placeholder' => __('field.subject')]) }}
                                            <div class="{{ $errors->has('subject') ? ' invalid-feedback' : ' valid-feedback' }}">
                                                @if(isset($errors->messages()['subject']))
                                                @foreach($errors->messages()['subject'] as $er)
                                                <p>{{ $er }}</p>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('message',__('field.message'), ['class' => 'control-label']) }}
                                            {{ Form::textarea('message',old('message'),['class' => 'form-control','id'=>'message','rows' => 4,'placeholder' => __('field.message')]) }}
                                            <div class="{{ $errors->has('message') ? ' invalid-feedback' : ' valid-feedback' }}">
                                                @if(isset($errors->messages()['message']))
                                                @foreach($errors->messages()['message'] as $er)
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
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
