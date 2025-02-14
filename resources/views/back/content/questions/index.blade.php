@extends('back/layouts.app')

@push('css_pagelevel')
  <style>
    .question_language{
      background-color: #ba54df;
      border-radius: 9px;
      padding: 4px;
      font-size: 15px;
      color: white;
      min-width: 40px;
      margin: 2px 4px;
      text-align: center;
    }
    .answer_language{
      background-color: #df54c3;
      border-radius: 7px;
      padding: 3px;
      font-size: 14px;
      color: white;
      min-width: 39px;
      margin: 2px 4px;
      text-align: center;
    }
    .input_default{
      width: 500px;
      border: 1px solid #cbcbcb;
      border-radius: 5px;
    }
    .lbl{
      font-size: 14px;
      font-weight: normal !important;
    }

  </style>
@endpush



@section('content')


@component('admin.page-header', [
  'title' => __('actions.index') . __('dashboard.tests'),
  'routes' => [
        ['header_route' => route('dashboard.tests.index'), 'header_name' => __('domain.tests')],
        ['header_name' => __('dashboard.questions')]
  ]
])




@php $activeLanguages = getActiveLanguages(); @endphp

<section class="content">
  <div class="container-fluid">
    <div class="card card-default">
      <div class="card-body">


          <!-- <div class="card-header">
            <h3 class="card-title">Quick Example</h3>
          </div> -->


          <x-admin.datatable.page-alert />

          <div class="card-body">
            <div class="row">
                  <div class="col-md-3">
                    <div class="input-group">


                      <x-admin.question-types-dd :questionsTypes='$questionsTypes' />

                      <span class="input-group-btn">
                          <button id="add_element" onclick="setQuestionType();" data-toggle="modal" data-target="#newQuestionModal"  class="btn btn-success" type="button">اضافة سؤال</button>
                      </span>
                    </div>
                </div>

                <!-- Import Questions -->
                <div class="col-md-5">
                    <form action="{{ route('dashboard.questions_test.import',['test' => $test->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" required name="import_file" id="exampleInputFile">
                              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                            </div>
                            <div class="input-group-append">
                              <button class="btn btn-default" type="submit">Upload</button>
                            </div>
                          </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-2">
                  <x-admin.datatable.but_delete link="{{ route('dashboard.questions_test.destroy_all', ['test' => $test->id]) }}" butTitle='حذف كل الاسئلة' />
                </div>

                <!-- Export Questions -->
                <div class="col-md-2">
                  <a href="{{ route('dashboard.questions_test.export',['test' => $test->id]) }}" class="btn btn-success" >Export Xml</a>
                </div>

            </div>

            <style>
              .question_title{font-size: 18px;color: black;}
              .answer_title{font-size: 18px;color: #666;}
              .lbl{padding: 5px;}
            </style>

            <br>

            @foreach ($questions as $key => $question)
              <div class="row" id="question_{{$question->id}}" style="direction: rtl;border: 1px solid #c4c4c4;border-radius: 7px;padding: 15px;margin-bottom: 10px;">
                  @include('back.content.questions.question', ['mode' => 'edit', 'activeLanguages' => $activeLanguages, 'question' => $question, 'serial' => $key+1 ])


              </div>
            @endforeach

          </div>
        </div>
      </div>
    </div>

</section>



<!-- clean answer row template. to add new answer to any question -->
<div style="visibility: hidden;">
  <div class="col-lg-12" id="clean_answer_row" style="border-bottom: 1px solid #cecece;margin-bottom: 13px;">


    @foreach($activeLanguages as $language)
      <div class="row">
            <!-- loop this only -->
            <div class="col-lg-1">{{ $language->alies }}</div>
            <div class="col-lg-6 answer_title">
                  <input id="new_row_name"  lang="{{ $language->alies }}" type="text" maxlength="200" class="input_default">
            </div>


            @if ($loop->first)
                <div class="col-lg-1"></div>

                <div class="col-lg-2" style="display: flex;">
                      <input id="new_row_is_correct" value="1" class="checkbox" type="checkbox">
                      <label class="control-label lbl">{{ __('domain.correct_answer') }}</label>
                </div>

                <div class="col-lg-2" style="display: flex;">
                      <input id="new_row_status" value="1" class="checkbox" type="checkbox">
                      <label class="control-label lbl">{{ __('general.status') }}</label>
                </div>
            @else
                <div class="col-lg-5"></div><!-- empty space under status and is_correct -->
            @endif


      </div>
    @endforeach

  </div>
</div>


<!-- clean question block modal to add new question-->
<div class="modal fade" id="newQuestionModal" tabindex="-1" role="dialog" aria-labelledby="newQuestionModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newQuestionModalTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div id="err_div_modal"></div>
      </div>
      <div class="modal-body" id="div_translations">
            @include('back.content.questions.question', ['mode' => 'add', 'activeLanguages' => $activeLanguages])
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>

@endsection


@push('js_pagelevel')
  <script>




    function setQuestionType()
    {
        // Get the select element
        var dropdown = document.getElementById("question_types");

        // Get the selected value
        var selectedValue = dropdown.options[dropdown.selectedIndex].value;

        // Now, 'selectedValue' contains the value of the selected option
        console.log(selectedValue);

        var e = document.getElementById('question_type');
        e.value = selectedValue;
    }

    // function setQuestionType()
    // {
    //   $questionType = getDroplistValue('question_types');
    //     console.log($questionType);
    //   var e = document.getElementById('question_type');
    //   e.value = $questionType;
    // }

    function removeAnswer(div_id)
    {
        $('#answer_'+div_id).remove();
    }

    function editQuestion(id)
    {

        var switch_to_inputs = document.getElementsByClassName("switch_to_input_"+id);

        for (var i = 0; i < switch_to_inputs.length; i++) {

              current_el = switch_to_inputs.item(i);

              // 01- switch to edit mode
              if (current_el.style.display == "block"){
                  current_el.style.display = "none";

                  let new_input = document.createElement('input');
                  new_input.type = 'text';
                  new_input.name = current_el.getAttribute('id');
                  new_input.value = current_el.innerHTML;
                  new_input.setAttribute('class', 'input_default');

                  current_el.parentNode.insertBefore(new_input, current_el.nextSibling)
              // 02 - returen to plain text
              } else {
                  current_el.style.display = "block";

                  let old_input = document.getElementsByName(current_el.getAttribute('id'));
                  let parent_old_input = old_input[0].parentNode;
                  parent_old_input.removeChild(old_input[0]);
              }

        }

    }

    var cloned_count = 0;
    function addAnswer(id)
    {
        // https://stackoverflow.com/questions/2649798/cloning-and-renaming-form-elements-with-jquery
        cloned_count = cloned_count + 1;

        var new_answer_row = $("#clean_answer_row").eq(0).clone();

        new_answer_row.find('#new_row_is_correct').each(function(i,e){
            $(e).attr('name', 'question['+id+'][new_answers]['+cloned_count+'][is_correct]');
        })
        new_answer_row.find('#new_row_status').each(function(i,e){
            $(e).attr('name', 'question['+id+'][new_answers]['+cloned_count+'][status]');
        })
        new_answer_row.find('#new_row_name').each(function(i,e){
            let lang = $(e).attr('lang');
            $(e).attr('name', 'question['+id+'][new_answers]['+cloned_count+'][translations]['+lang+'][name]');
        })
        $('#question_'+id+'_answers_div').append(new_answer_row);


        // new_answer_row.find('#new_row_is_correct').attr('name', 'new_answers['+cloned_count+'][is_correct]');
        // new_answer_row.find('#new_row_status').attr('name', 'new_answers['+cloned_count+'][status]');
    }

    $(window).load(function(){
      $('html,body').animate({scrollTop: document.body.scrollHeight},"slow");
    });

  </script>
@endpush
