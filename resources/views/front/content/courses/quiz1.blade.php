@extends('front.layouts.new')
@section('content')
<style >
.form-group {
  display: flex;
  width: 50% !important;
}
.form-group label {
    width: 30% !important;
    padding: 10px 0;
    font-size: revert;
    color: #a97f51;
    font-weight: 600;
}
</style>
<!-- ##### Hero Area Start ##### -->
<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 425px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12"style=" margin-top: 130px;">
                <!-- Hero Content -->
                <div class="hero-content text-center row">
                    <div class="col-4">
                        <img src="{{ url($course->logo_path) }}" alt="{{ $course->name }}" class="bg-light img-raised img-fluid" style="width: 200px;border-radius: 18px;"> <!-- class="p-3 bg-light img-raised rounded-circle img-fluid" -->
                    </div>
                    <div class="col-8">

                        <h1 style="color: white;">{{ $course->name }}</h1>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- ##### Hero Area End ##### -->
    <div class="container pt-3">

        {{--@include('front.units.breadcrumb',['routes' => [['slug' => route('courses.index',$site->alias),'name' => $site->name],['slug' => route('courses.show',['site' => $site->alias,'course' => $course->alias]),'name' => $course->name],['name' => __('meta.title.quiz') ]]])--}}
        @if(!empty($errors) || session('error') || session('success') || session('message'))
            <section class="notify">


              @if(count($errors) > 0)
                  <div class="container">
                      <div class="row">
                          <div class="col-md-12">

                              <div class="alert alert-danger text-center">
                                @if($errors->first() != __('core.invalid_quiz_count'))
                                  {{ __('words.error_Quiz') }}
                                @else
                                  {{__('core.invalid_quiz_count')}}
                                @endif
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>
              @endif



            </section>
        @endif
        <div class="row">
            <div class="text-left col-md-12">

              @if( $questions->count() > 0  )
                  <form class="" action="{{ route('courses.quiz1',['site' => $site->alias,'course' => $course->alias]) }}" method="post">
                      @csrf
                      {{-- <div class="tim-container"> --}}
                      <div class="row">
                          @foreach ($questions as $question)

                              <div class="col-md-6" style="padding-bottom: 25px;">
                                  <div class="card bg-light" style="border: 1px solid #ead9a8;box-shadow: 0 3px 20px rgba(0, 0, 0, 0.15);padding: 5px 5px;border-radius: 14px;">
                                      <div class="card-body" style="@if (LaravelLocalization::getCurrentLocaleDirection() =='rtl') text-align: right; @endif ">
                                          <h4 class="card-title text-dark" style="font-size: 17px;line-height: 2;"> {{$loop->iteration}} - {{ $question->name }} </h4>
                                          <div class="{{ $errors->has('answers.'.$question->id) ? 'inputs-has-error' : '' }}" style="margin-right: 25px;">
                                              @if ($question->type == 'true_false')
                                                  <div class="form-check form-check-radio">
                                                      <label class="form-check-label">
                                                          <input style="margin-right: -20px;margin-left: -20px;" class="form-check-input {{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" type="radio" {{ $question->required ? 'required' : '' }} name="answers[{{ $question->id }}]" {{ old('answers.'.$question->id) == '1' ? 'checked' : '' }} value="1">
                                                          @lang('core.trueq')
                                                          <span class="circle">
                                                              <span class="check"></span>
                                                          </span>
                                                      </label>
                                                  </div>
                                                  <div class="form-check form-check-radio">
                                                      <label class="form-check-label">
                                                          <input style="margin-right: -20px;margin-left: -20px;" class="form-check-input {{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" type="radio" {{ $question->required ? 'required' : '' }} name="answers[{{ $question->id }}]" {{ old('answers.'.$question->id) == '0' ? 'checked' : '' }} value="0">
                                                          @lang('core.falseq')
                                                          <span class="circle">
                                                              <span class="check"></span>
                                                          </span>
                                                      </label>
                                                  </div>
                                              @elseif ($question->type == 'range')
                                                  <input class="{{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" type="range" {{ $question->required ? 'required' : '' }} name="answers[{{ $question->id }}]" min="{{ $question->options['min'] }}" max="{{ $question->options['max'] }}" value="{{ old('answers.'.$question->id) ?? $question->options['min'] }}">
                                              @elseif (count($question->correct_answer) > 1)
                                                  @foreach ($question->answers()->where('status',1)->orderBy('sequence','ASC')->select('id')->get() as $answer)
                                                      <div class="form-check">
                                                          <label class="form-check-label">
                                                              <input class="form-check-input {{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $answer->id }}" {{ old('answers.'.$question->id) == $answer->id ? 'checked': '' }}>
                                                              {{--<!-- <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $answer->id }}" {{ old('answers.'.$question->id) == $answer->id || $loop->first ? 'checked': '' }}> -->--}}
                                                              {{ $answer->name }}
                                                              <span class="form-check-sign">
                                                                  <span class="check"></span>
                                                              </span>
                                                          </label>
                                                      </div>
                                                  @endforeach
                                              @else
                                                  <select class="form-control selectpicker {{ $errors->has('answers.'.$question->id) ? ' is-invalid' : '' }}" data-style="btn btn-link" name="answers[{{ $question->id }}]">
                                                        <option value=""  disabled {{ old('answers.'.$question->id) == null ? 'selected': '' }}>{{ __('words.p_select_option') }} </option>
                                                      @foreach ($question->answers()->where('status',1)->orderBy('sequence','ASC')->select('id')->get() as $answer)
                                                        <option value="{{ $answer->id }}"  {{ old('answers.'.$question->id) == $answer->id  ? 'selected': '' }}> {{ $answer->name }} </option>
                                                      @endforeach
                                                  </select>
                                              @endif
                                              {{--@if ($errors->has('answers.'.$question->id))
                                                  <span class="invalid-feedback" role="alert">
                                                      {{ $errors->first('answers.'.$question->id) }}
                                                  </span>
                                              @endif--}}

                                          </div>
                                      </div>
                                  </div>
                              </div>
                          @endforeach
                          <div class="form-group">
                              <label for="phone">فضلا ضع رقم هاتفك</label>
                              <input type="text" class="form-control"  value="{{old('phone')}}"id="phone" name="phone" aria-describedby="phone" placeholder="فضلا ضع رقم هاتفك">
                            </div>
                      </div>

                      <div class="row" style="padding: 50px 100px 70px 100px;">
                          <button type="submit" class="btn btn-primary btn-lg btn-block"> @lang('core.send') </button>
                      </div>

                  </form>

                @else

                  <div class="alert alert-danger text-center p-4">

                    {{__('core.question_notfound')}}<br/>
                    {{date('Y/m/d',strtotime($course->exam_at))}}  {{ isset($course) ? $course->getExamAtDay() : '' }} {{ isset($course) ? $course->getExamAtHijri() : ''}} هـ

                  </div>
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
