@extends('back/layouts.app')

@section('content')

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
    width: 200px;
    border: 1px solid #cbcbcb;
    border-radius: 5px;
  }
</style>

<div class="">
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12">
      <div class="x_panel">
        <div class="x_title">
            <h2> {{ $course->name.' | '.__('meta.title.questions_old') }} </h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

          @include('back.includes.breadcrumb',['routes' => [
              ['slug' => route('dashboard.courses.index',$site->id),'name' => $site->name],
              ['name' => $course->name.' | '.__('meta.title.questions_old')]]
          ])

          @include('back.includes.page-alert')

          <div class="col-md-12 row">

              <!-- <a href="{{ route('dashboard.courses.questions_old.create',['site' => $site->id,'course' => $course->id]) }}" class="btn btn-success" >اضافة سؤال</a> -->

              <div class="col-md-3">
                  <div class="input-group">
                      <select id="element_types" class="form-control">
                          <option value="">{{ __('field.select_property_type') }}</option>
                          <!-- <option value="range">{{ __('field.range') }}</option> -->
                          <option value="true_false">{{ __('field.true_false') }}</option>
                          <option value="drop_list">{{ __('field.drop_list') }}</option>
                      </select>
                      <span class="input-group-btn">
                          <button id="add_element" data-toggle="modal" data-target="#newQuestionModal"  class="btn btn-success" type="button">اضافة سؤال</button>
                      </span>
                  </div>
              </div>

              <div class="col-md-3">
                  <form action="{{ route('dashboard.courses.questions_old.import',['site' =>$site->id,'course' => $course_id, 'type' => request()->query('type')]) }}" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="input-group">
                          <input type="file" name="import_file" class="form-control">
                          <div class="input-group-btn">
                              <button class="btn btn-default" type="submit">
                                  <i class="glyphicon glyphicon-cloud-upload"></i>
                              </button>
                          </div>
                      </div>
                          <!-- <p class="text-muted"> Headings for file upload is ("id","type","status","required","degree","name_ar","answers","options","correct_answer") </p> -->
                  </form>
              </div>

              <div class="col-md-2">
                <x-buttons.but_delete link="{{ route('dashboard.courses.questions_old.delete_all',['course' => $course_id, 'type' => request()->query('type') ]) }}" butTitle='حذف كل الاسئلة' />
              </div>

              <div class="col-md-2">
                <a href="{{ route('dashboard.courses.questions_old.export',['site' => $site->id,'course' => $course->id, 'type' => request()->query('type')]) }}" class="btn btn-success" >Export Xml</a>
              </div>

          </div>


          {{--
          <div class="row">
            <x-admin.languages.languages-bar />
          </div>
          --}}

          <hr>

          <style>
            .question_title{font-size: 18px;color: black;}
            .answer_title{font-size: 18px;color: #666;}
            .lbl{padding: 5px;}
          </style>

          <br><br><br>

          @foreach ($questions as $key => $question)
            <div class="row" id="question_{{$question->id}}" style="direction: rtl;border: 1px solid #c4c4c4;border-radius: 7px;padding: 15px;margin-bottom: 10px;">
              <div style="margin-bottom: 7px;"><span style="border: 1px solid gray;padding: 3px 7px;border-radius: 20px;">{{ $key+1 }}</span></div>
              @include('back.content.questions_old.question', ['mode' => 'edit','question' => $question])
            </div>
          @endforeach




          </div>
      </div>
    </div>
  </div>
</div>







<!-- clean answer row template. to add new answer to any question -->
<div style="visibility: hidden;">
  <div class="col-lg-12" id="clean_answer_row" style="border-bottom: 1px solid #cecece;margin-bottom: 13px;">

    @foreach(getActiveLanguages() as $language)
      <div class="row">
            @if ($loop->first)
                <div class="col-lg-1"></div>

                <div class="col-lg-2" style="display: flex;">
                      <input id="new_row_is_correct" value="1" class="checkbox" type="checkbox">
                      <label class="control-label lbl">{{ __('field.correct_answer') }}</label>
                </div>

                <div class="col-lg-2" style="display: flex;">
                      <input id="new_row_status" value="1" class="checkbox" type="checkbox">
                      <label class="control-label lbl">{{ __('field.active') }}</label>
                </div>
            @else
                <div class="col-lg-5"></div><!-- empty space under status and is_correct -->
            @endif

          <!-- loop this only -->
          <div class="col-lg-6 answer_title">
                <input id="new_row_name"  lang="{{ $language->alies }}" type="text" maxlength="200" class="input_default">
          </div>

          <div class="col-lg-1">{{ $language->alies }}</div>
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
            @include('back.content.questions_old.question', ['mode' => 'add'])
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>


@stop

@section('js_pagelevel')
@include('back.includes.swal-alerts.confirm-delete')

<script>
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
@endsection
