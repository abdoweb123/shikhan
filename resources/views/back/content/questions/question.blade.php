
@if ($mode == 'edit')

    <div class="col-12">
        <div class="row">
            <!-- edit but -->
            <div class="col-md-10" style="text-align: left;">
                {{--          <form method="POST" onsubmit="ajaxForm(event,this,'','','')" action="{{ route('dashboard.questions_test.destroy', ['question' => $question->id]) }}">--}}
                <form method="get" action="{{ route('dashboard.questions_test.destroy', ['question' => $question->id]) }}">
                    @csrf
                    @method('get')
                    <button type="submit" class="btn btn-danger btn-sm pull-left">
                        <i class="fa fa-trash"></i>@isset($butTitle) {{ $butTitle}} @endisset
                    </button>
                </form>
            </div>

            <div class="col-md-2" style="text-align: right;">
                <span style="border: 1px solid gray;padding: 3px 7px;border-radius: 20px;">{{ $serial ?? '' }}</span>
                <a onclick="editQuestion({{$question->id}})" style="background-color: #5bc0de;padding: 6px 25px;border-radius: 5px;color: white;cursor: pointer;" >Edit</a>
            </div>
            <!-- delete but -->

        </div>
    </div>



    <!-- onsubmit="ajaxForm(event,this,'question_{{$question->id}}','','')" -->
    {{--<form method="post" onsubmit="ajaxForm(event,this,'question_{{$question->id}}','','')" action="{{ route('dashboard.questions_test.update', ['question' => $question->id]) }}" style="width: 100%;">--}}
    <form method="post" action="{{ route('dashboard.questions_test.update', ['question' => $question->id]) }}" style="width: 100%;">
        @csrf
        @method('PUT')

        <input type="hidden" name="type" value="drop_list">

        <div class="col-lg-12">
            <div class="row">

                <div class="col-lg-12 question_title">
                    <div style="display: flex;">
                        <div>


                            @foreach($activeLanguages as $language)
                                @php $translation = $question->translation->where('locale', $language->alies)->first(); @endphp
                                <div class="row" style="display:flex">
                                    <div class="question_language">{{ $language->alies }}&nbsp;</div>
                                    @if ($translation)
                                        <div><span id="question[{{$question->id}}][transaltions][{{$language->alies}}][name]" class="switch_to_input_{{$question->id}}" style="display: block;">{{ $translation->title }}</span></div>
                                    @else
                                        <div><span id="question[{{$question->id}}][transaltions][{{$language->alies}}][name]" class="switch_to_input_{{$question->id}}" style="display: block;"></span></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- question -->
        <div class="row" style="display: flex; margin: 5px">
            <div class="col-lg-2" style="display: flex;">
                <input name="question[{{$question->id}}][status]" value="1" class="checkbox" {{ $question->status ? 'checked' : '' }} type="checkbox">
                <label class="control-label lbl">{{ __('general.status') }}</label>
            </div>

            <div class="col-lg-2" style="display: flex;">
                <input name="question[{{$question->id}}][required]" value="1" class="checkbox" {{ $question->required ? 'checked' : '' }} type="checkbox">
                <label class="control-label lbl">{{ __('general.required') }}</label>
            </div>

            <div class="col-lg-3" style="display: flex;">
                <label class="control-label lbl">{{ __('domain.degree') }}</label>
                <input name="question[{{$question->id}}][degree]" value="{{ $question->degree }}" class="form-control input_default" type="number">
            </div>

            <div class="col-lg-5" style="display: flex;">
                <div>
                    <label class="control-label lbl">{{ __('domain.duration_in_minutes') }}</label>
                </div>

                <div>
                    <input name="question[{{$question->id}}][duration]" value="{{ $question->duration }}" class="form-control input_default" type="number" style="width: 300px">
                </div>
            </div>

            <input type="hidden" name="question[{{$question->id}}][type]" value="{{$question->type}}">
        </div>

        <!-- hide answers if qestion type is Essay -->
        @inject('questionService', 'App\Services\QuestionService()')
        @if (! $questionService->ignoreAnswers($question->type))
        <!-- answers -->
            <div class="col-lg-12" style="padding: 8px 42px;">
                <div class="row" id="question_{{$question->id}}_answers_div" style="direction: rtl;padding: 16px 16px 0px 5px;;margin-bottom: 10px;">
                    @foreach ($question->answers as $answer)
                        <div class="col-lg-12" id="answer_{{$answer->id}}" style="border-bottom: 1px solid #cecece;margin-bottom: 13px;">
                            <div class="row">

                                <input type="hidden" name="question[{{$question->id}}][answers][{{$answer->id}}][id]" value="{{ $answer->id }}">

                                <div class="col-lg-7 answer_title">
                                    <div>
                                        @foreach($activeLanguages as $language)
                                            @php $translation = $answer->translations->where('locale', $language->alies)->first(); @endphp
{{--                                            {{$answer->translations->locale}}--}}
{{--                                            {{$question->answers}}--}}
                                            <div style="display: flex;">
                                                <div class="answer_language">{{ $language->alies }}&nbsp;:&nbsp;</div>
                                                @if ($translation)
{{--                                                    <div><span id="question[{{$question->id}}][answers][{{$answer->id}}][translations][{{$language->alies}}][name]"  class="switch_to_input_{{$question->id}}" style="display: block;">{{ $translation->title }}</span></div>--}}
                                                    <div><span id="question[{{$question->id}}][answers][{{$answer->id}}][translations][{{$language->alies}}][name]"  class="switch_to_input_{{$question->id}}" style="display: block;">{{ $translation->title }}</span></div>
                                                @else
                                                    <div><span id="question[{{$question->id}}][answers][{{$answer->id}}][translations][{{$language->alies}}][name]"  class="switch_to_input_{{$question->id}}" style="display: block;"></span></div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>


                                <div class="col-lg-2" style="display: flex;">
                                    <input name="question[{{$question->id}}][answers][{{$answer->id}}][is_correct]" value="{{ $answer->id }}" class="checkbox"
                                           {{ $answer->isCorrectAnswer($question->correct_answers) ? 'checked' : '' }} type="checkbox">
                                    <label class="control-label lbl">{{ __('domain.correct_answer') }}</label>
                                </div>

                                <div class="col-lg-2" style="display: flex;">
                                    <input name="question[{{$question->id}}][answers][{{$answer->id}}][status]" value="1" class="checkbox" {{ $answer->status ? 'checked' : '' }} type="checkbox">
                                    <label class="control-label lbl">{{ __('general.status') }}</label>
                                </div>

                                <div class="col-lg-1">
                                    {{--                                  <button type="button" onclick="removeAnswer({{$answer->id}})" class="btn btn-danger btn-sm pull-left"><i class="fa fa-trash"></i></button>--}}
                                    {{--                                <form method="POST" onsubmit="ajaxForm(event,this,'','','')" action="{{ route('dashboard.questions_test.deleteAnswer', ['answer' => $answer->id]) }}">--}}
                                    {{--                                    --}}{{--          <form method="POST" action="{{ route('dashboard.questions_test.destroy', ['question' => $question->id]) }}">--}}
                                    {{--                                    @csrf--}}
                                    {{--                                    @method('delete')--}}
                                    {{--                                    <button type="submit" class="btn btn-danger btn-sm pull-left confirm-delete">--}}
                                    {{--                                        <i class="fa fa-trash"></i>--}}
                                    {{--                                    </button>--}}
                                    {{--                                </form>--}}
                                    <a href="{{ route('dashboard.questions_test.deleteAnswer', ['answer' => $answer->id]) }}" class="btn btn-danger btn-sm pull-left">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    {{--          <form method="POST" action="{{ route('dashboard.questions_test.destroy', ['question' => $question->id]) }}">--}}


                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-12" style="display: flex;">
                    <button type="button" onclick="addAnswer({{$question->id}})" class="btn btn-info btn-sm pull-left"><i class="fa fa-plus"></i>اضافة اجابة</button>
                </div>
            </div>
        @endif


        <div class="col-lg-12" style="text-align: left;">
            <button class="btn btn-sm btn-warning" type="submit" style="font-size: 18px;padding: 5px 32px;">حفظ</button>
        </div>

    </form>

@endif



@if ($mode == 'add')
    <div class="row" style="direction: rtl;border: 1px solid #c4c4c4;border-radius: 7px;padding: 15px;margin-bottom: 10px;">

        <!-- onsubmit="ajaxForm(event,this,'','','')" -->
        {{--    <form method="post" onsubmit="ajaxForm(event,this,'','','')"  action="{{ route('dashboard.questions_test.store', ['test' => request()->test ]) }}">--}}
        <form method="post" action="{{ route('dashboard.questions_test.store', ['test' => request()->test ]) }}">
            @csrf

            <div class="col-lg-12 mb-2" style="margin-bottom: 20px;">
                <div class="row">
                    <div class="col-lg-12 question_title">
                        <label class="control-label lbl">السؤال</label>
                        @foreach($activeLanguages as $language)
                            <div style="display: flex;">
                                <div class="question_language">{{ $language->alies }}&nbsp;</div>
                                <div><input type="text" name="question[0][translations][{{$language->alies}}][name]" class="input_default"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- question -->
            <div class="row" style="padding-top: 16px;">

                <div class="col-lg-2" style="display: flex;">
                    <input name="question[0][status]" value="1" class="checkbox" type="checkbox">
                    <label class="control-label lbl">{{ __('general.status') }}</label>
                </div>

                <div class="col-lg-2" style="display: flex;">
                    <input name="question[0][required]" value="1" class="checkbox" type="checkbox">
                    <label class="control-label lbl">{{ __('general.required') }}</label>
                </div>

                <div class="col-lg-3" style="display: flex;">
                    <label class="control-label lbl">{{ __('domain.degree') }}</label>
                    <input name="question[0][degree]" value="2" class="form-control" type="number">
                </div>

                <div class="col-lg-5" style="display: flex;">
                    <div class="">
                        <label class="control-label lbl">{{ __('domain.duration_in_minutes') }}</label>
                    </div>
                    <div class="">
                        <input name="question[0][duration]" value="5" class="form-control" type="number">
                    </div>
                </div>

                <input type="hidden" id="question_type" name="question[0][type]" value="">
            </div>


            <div class="col-lg-2" style="display: flex;">
                <button type="button" onclick="addAnswer(0)" class="btn btn-info btn-sm pull-left"><i class="fa fa-plus"></i>اضافة اجابة</button>
            </div>

            <!-- answers -->
            <div class="row" id="question_0_answers_div" style="direction: rtl;padding: 15px;margin-bottom: 10px;"></div>

            <div class="col-lg-12" style="display: flex;">
                <button class="btn btn-sm btn-warning" type="submit" style="font-size: 18px;padding: 5px 32px;">حفظ</button>
            </div>

        </form>
    </div>

@endif
