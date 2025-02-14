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
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br/>
                        @if(session()->has('success'))
                            <div class="alert alert-success text-center">
                                {{ session()->get('success') }}
                            </div>
                        @endif


                        <div class="tab-content">
                            @foreach ($coursesCertificatesTemplates as $templates)
                              @foreach ($templates->new_value as $template)
                                <div>
                                    <form method="post" action="{{ route('dashboard.courses.certificates.templates.update', [ 'id' => $templates->id ] ) }}" class="">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form{{ $errors->has('content') ? ' has-error' : '' }}">
                                                    <div style="font-size: 20px;font-weight: bold;" for="content"> {{ $cirtTitles[$templates->property] }} - {{ $template[0] }} <span class="required">*</span></div>
                                                    <div class=" col-sm-12 col-xs-12">
                                                        id="capture"
                                                        <textarea class="craete_editor" name="content" rows="35" type="text" class="form-control col-md-12 col-xs-12" > {!! Request::old('content') ?: $template[1] !!} </textarea>
                                                        @if ($errors->has('content'))
                                                            <span class="help-block">{{ $errors->first('content') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xs-12" style="padding: 20px;">
                                            <input type="hidden" name="_token" value="{{ Session::token() }}">
                                            <input type="hidden" name="language" value="{{ $template[0] }}">
                                            <button type="submit" class="btn btn-primary" style="padding: 15px 50px;">Save</button>
                                        </div>
                                        <hr>
                                    </form>
                                </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
