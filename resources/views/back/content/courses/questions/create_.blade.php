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
                    <h2> {{ __('core.add') }} </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if(session()->has('success'))
                        <div class="alert alert-success text-center">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    @include('back.includes.breadcrumb',['routes' => [
                        ['slug' => route('dashboard.courses.index',$site->alias),'name' => $site->name],
                        ['name' => __('core.add')]]
                    ])
                    <hr>

<form method="post" action="{{ route('dashboard.courses.questions_old.store',['site' => $site->alias,'course' => $course->id]) }}">
  @csrf

  <div class="element-form">
      <div class="col-lg-12">
          <div class="row">

            <div class="col-lg-6">

                <div class="col-lg-4">
                  <div class="form-group">
                    <div class="checkbox">
                        <label for="status">
                            {{ __('field.status') }}
                            <input class="checkbox" type="checkbox" name="status" value="1" {{ old('status') ? "checked" : null }}>
                        </label>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="form-group">
                    <div class="checkbox">
                            <label for="required-@{{id}}">
                                {{ __('field.required') }}
                                <input class="checkbox" id="required" type="checkbox" name="required" value="1"  {{ old('required') ? "checked" : null }}>
                            </label>
                    </div>
                  </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="control-label" for="degree">{{ __('field.degree') }}</label>
                        <input name="degree" value="{{ old('degree') }}" class="form-control">
                    </div>
                    <hr>
                    <div class="clearfix"></div>
                </div>

            </div>
            <div class="col-lg-6">
                @foreach ($languages as $lang => $alies)
                    <div class="form-group">
                        <label class="control-label" for="name-@{{id}}-{{ $lang }}" >{{ __('field.question').' '.$alies }}</label>
                        <input name="name[{{$lang}}]" value="{{ old('name.'.$lang) }}" class="form-control">
                    </div>
                @endforeach
            </div>
                  @{{#is.correct_answer}}
                      <div class="col-lg-12">

                          <div class="clearfix"></div>
                          <div class="col-lg-6">
                              <div class="form-group">

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group">

                              </div>
                          </div>
                      </div>

          </div>
      </div>
  </div>



      <div class="col-lg-12">
      <div class="table-responsive">
          <table class="table table-dark table-striped table-bordered">
              <thead>
                  <tr class="text-center">
                      <th>#</th>
                      @foreach ($languages as $lang => $alies)
                          <th class="text-center">{{ $alies }}</th>
                      @endforeach
                      <th class="text-center">{{ __('field.status') }}</th>
                      <th class="text-center">{{ __('field.correct_answer') }}</th>
                      <th class="text-center">
                          <a class="btn btn-success"><i class="fa fa-plus"></i></a>
                      </th>
                  </tr>
              </thead>
              <tbody class="element_items">

                <div class="radio">
                    <label for="correct_answer">
                        {{ __('core.trueq') }}
                        <input class="radio" type="radio" name="correct_answer" value="1" {{ old('correct_answer') ? "checked" : null }}>
                    </label>
                </div>
                <div class="radio">
                    <label for="correct_answer">
                        {{ __('core.falseq') }}
                        <input class="radio" type="radio" name="wrong_answer" value="1" {{ old('wrong_answer') ? "checked" : null }}>
                    </label>
                </div>

                <td></td>
                <td></td>
                <td></td>
              </tbody>
          </table>
      </div>
      </div>


</form>
</div>
</div>
</div>
</div>
</div>

@stop
