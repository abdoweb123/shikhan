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
                        <h2> {{$course->name}} </h2>
                        <a class="btn btn-success pull-right" href="{{ route('dashboard.courses.index',$site->alias) }}">Back</a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br/>
                        @if(session()->has('success'))
                            <div class="alert alert-success text-center">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        <ul class="nav nav-tabs">
                            @foreach ($languages as $locale => $name)
                                <li {{ (Request::input('tab') && Request::input('tab') == 'lang-'.$locale) || (!Request::input('tab') && $loop->first) ? 'class=active' : '' }} ><a data-toggle="tab" href="#lang-{{ $locale }}"> {{ $name }} </a></li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach ($languages as $locale => $name)
                                <div id="lang-{{ $locale }}" class="tab-pane fade {{ (Request::input('tab') && Request::input('tab') == 'lang-'.$locale) || (!Request::input('tab') && $loop->first) ? 'in active' : '' }}">
                                    {!! Form::model($fields,['method' => 'PUT','route' => ['dashboard.courses.template.update',$site->alias,$course_id],'class'=>'form-horizontal form-label-left']) !!}
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-group{{ $errors->has('subject') && Request::input('tab') == 'lang-'.$locale ? ' has-error' : '' }}">
                                                    <label class="control-label col-sm-3 col-xs-12" for="subject"> Subject <span class="required">*</span>
                                                    </label>
                                                    <div class=" col-sm-9 col-xs-12">
                                                        <input type="text" value="{{ Request::old('subject') ?: @$fields[$locale]['subject'] }}" id="subject" name="subject" class="form-control col-md-7 col-xs-12">
                                                        @if ($errors->has('subject') && Request::input('tab') == 'lang-'.$locale)
                                                            <span class="help-block">{{ $errors->first('subject') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group{{ $errors->has('content') && Request::input('tab') == 'lang-'.$locale ? ' has-error' : '' }}">
                                                    <label class="control-label col-sm-3 col-xs-12" for="content"> Content <span class="required">*</span>
                                                    </label>
                                                    <div class=" col-sm-9 col-xs-12">
                                                        <textarea class="craete_editor"   rows="15" type="text"   id="content" name="content" class="form-control col-md-12 col-xs-12" > {!! Request::old('content') ?: @$fields[$locale]['content'] !!} </textarea>
                                                        @if ($errors->has('content') && Request::input('tab') == 'lang-'.$locale)
                                                            <span class="help-block">{{ $errors->first('content') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-9 col-xs-12 col-md-offset-3">
                                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                                            <input type="hidden" name="locale" value="{{ $locale }}">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
