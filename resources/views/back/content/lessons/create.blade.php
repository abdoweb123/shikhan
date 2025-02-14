@extends('back/layouts.app')
@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="kt-portlet">

        <div class="kt-portlet__head">
          <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
              {{ __('words.add') }} &nbsp;&nbsp;&nbsp; <x-buttons.but_back link="{{ route( 'dashboard.lessons.index' ) }} "/>
            </h3>
          </div>
        </div>

        @include('back.includes.page-alert')

        <div class="kt-portlet__body">
          <div class="kt-section kt-section--first">

<!-- form -->
<form class="kt_form_1" enctype="multipart/form-data" action="{{ route( 'dashboard.lessons.store' ) }}" method="post" id="form">
    {{ csrf_field() }}


    <div class="form-group row">
      <div class="col-md-6">
          <div class="form-group row">
              <x-admin.languages.active-languages/>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.teachers') }} *</label>
              <div class=" col-lg-9 col-md-9 col-sm-12">
                  <x-admin.datatable.teachers-dd :teachers='$teachers'/>
                  <x-admin.datatable.label-input-error field='teacher_id' />
{{--                <select class="form-control select_2 kt-select2 {{ $errors->has('teacher_id') ? ' is-invalid' : '' }}"  id="kt_select2_1" name="teacher_id">--}}
{{--                    <option {{ old('teacher_id') == null ? 'selected' : '' }} value="">{{__('core.app_name')}}</option>--}}
{{--                  @foreach ( $teachers as $teacher )--}}
{{--                    <option {{ old('teacher_id') == $teacher->id ? 'selected' : '' }} value="{{ $teacher->id }}">{{ $teacher->name }}</option>--}}
{{--                  @endforeach--}}
{{--                </select>--}}
{{--                <!-- <span class="form-text text-muted">Please enter your full name</span> -->--}}
{{--                @if ($errors->has('teacher_id'))--}}
{{--                    <span class="invalid-feedback">{{ $errors->first('teacher_id') }}</span>--}}
{{--                @endif--}}
              </div>
          </div>
          <div class="form-group row {{ $errors->has('course_id') ? ' has-error' : '' }}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="course_id">  {{__('core.courses') }}<span class="required"> *</span> </label>
            <div class=" col-md-9 col-sm-9 col-xs-12">
                <x-admin.dd-courses :courses='$courses'/>
                <x-admin.datatable.label-input-error field='course_id' />
{{--                <select name="course_id" class=" form-control select_2 col-md-6 col-sm-6 col-xs-12">--}}
{{--                    @isset($courses)--}}
{{--                            @foreach ($courses as $course)--}}
{{--                                <option {{ Request::old('course_id') == $course->id  ? 'selected' : '' }} value="{{ @$course->id }}"> {{ $course->name }} -- {{ $course->site != null ? $course->site->title : ''}} </option>--}}
{{--                            @endforeach--}}
{{--                    @endisset--}}
{{--                </select>--}}
{{--                @if ($errors->has('course_id'))--}}
{{--                    <span class="help-block">{{ $errors->first('course_id') }}</span>--}}
{{--                @endif--}}
            </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.name') }} *</label>
              <div class=" col-lg-9 col-md-9 col-sm-12">
                  <input type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" required maxlength="150"
                  value="{{ old('title') }}" name="title" placeholder="">
                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                @if ($errors->has('title'))<span class="invalid-feedback">{{ $errors->first('title') }}</span>@endif
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.alias') }} *</label>
              <div class=" col-lg-9 col-md-9 col-sm-12">
                  <input type="text" class="form-control {{ $errors->has('alias') ? ' is-invalid' : '' }}" required maxlength="alias"
                  value="{{ old('alias') }}" name="alias" placeholder="">
                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                @if ($errors->has('alias'))<span class="invalid-feedback">{{ $errors->first('alias') }}</span>@endif
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.image') }}</label>
              <div class="col-lg-9 col-md-9 col-sm-12">
                <input type="file" name="image" id="image" class="dropify img_edit"/>
              </div>
          </div>
          <div class="form-group row">
              <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.sort') }}</label>
              <div class=" col-lg-4 col-md-9 col-sm-12">
                <input class="form-control {{ $errors->has('sort') ? ' is-invalid' : '' }}" type="number" min="1"
                value="{{ old('sort') }}" maxlength="3" id="example-number-input" name="sort">
                <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                @if ($errors->has('sort'))
                    <span class="invalid-feedback">{{ $errors->first('sort') }}</span>
                @endif
              </div>
          </div>
          <div class="form-group row">
              <x-admin.is-active/>
          </div>
      </div>

      <div class="col-md-6">
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.header') }}</label>
            <div class="col-lg-9 col-md-9 col-sm-12">
              <textarea  class="form-control {{ $errors->has('header') ? ' is-invalid' : '' }}"
              name="header" placeholder="">{{ old('header') }}</textarea>
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('header'))<span class="invalid-feedback">{{ $errors->first('header') }}</span>@endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_description') }}</label>
            <div class="col-lg-9 col-md-9 col-sm-12">
              <textarea  class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}"
              name="meta_description" placeholder="">{{ old('meta_description') }}</textarea>
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('meta_description'))<span class="invalid-feedback">{{ $errors->first('meta_description') }}</span>@endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.meta_keywords') }}</label>
            <div class="col-lg-9 col-md-9 col-sm-12">
              <textarea  class="form-control {{ $errors->has('meta_keywords') ? ' is-invalid' : '' }}"
              name="meta_keywords" placeholder="">{{ old('meta_keywords') }}</textarea>
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('meta_keywords'))<span class="invalid-feedback">{{ $errors->first('meta_keywords') }}</span>@endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.brief') }}</label>
            <div class="col-lg-9 col-md-9 col-sm-12">
              <textarea  class="form-control {{ $errors->has('brief') ? ' is-invalid' : '' }}" maxlength="300"
              name="brief" placeholder="">{{ old('brief') }}</textarea>
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('brief'))<span class="invalid-feedback">{{ $errors->first('brief') }}</span>@endif
            </div>
        </div>

        {{--
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.pdf') }}</label>
            <div class="col-lg-9 col-md-9 col-sm-12">
              <input type="text" name="pdf"  class="form-control "  value="{{ old('pdf')}}"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.sound') }}</label>
            <div class="col-lg-9 col-md-9 col-sm-12">
              <input type="text" name="sound" id="sound" class="form-control {{ $errors->has('sound') ? ' is-invalid' : '' }}" maxlength="500" value="{{ old('sound') }}"/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.video') }}</label>
            <div class=" col-lg-9 col-md-9 col-sm-12">
                <input type="text" class="form-control {{ $errors->has('video') ? ' is-invalid' : '' }}" maxlength="500"
                value="{{ old('video') }}" name="video" placeholder="">
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('video'))<span class="invalid-feedback">{{ $errors->first('video') }}</span>@endif
            </div>
        </div>
        --}}

        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.link_zoom') }}</label>
            <div class=" col-lg-9 col-md-9 col-sm-12">
                <input type="text" class="form-control {{ $errors->has('link_zoom') ? ' is-invalid' : '' }}" maxlength="500"
                value="{{ old('link_zoom') }}" name="link_zoom" placeholder="">
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('link_zoom'))<span class="invalid-feedback">{{ $errors->first('link_zoom') }}</span>@endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-lg-3 col-sm-12">{{ __('trans.start_at') }}</label>
            <div class=" col-lg-9 col-md-9 col-sm-12">
                <input type="datetime-local" class="form-control {{ $errors->has('started_at') ? ' is-invalid' : '' }}" maxlength="500"
                value="{{ old('started_at') }}" name="started_at" placeholder="">
              <!-- <span class="form-text text-muted">Please enter your full name</span> -->
              @if ($errors->has('started_at'))<span class="invalid-feedback">{{ $errors->first('started_at') }}</span>@endif
            </div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group row">
          <label class="col-form-label col-lg-1 col-sm-12">{{ __('words.html') }}</label>
          <div class=" col-lg-11 col-md-9 col-sm-12">
            <x-inputs.ckeditor name="html" data="" />
            <!-- <span class="form-text text-muted">Please enter your full name</span> -->
            @if ($errors->has('html'))<span class="invalid-feedback">{{ $errors->first('html') }}</span>@endif
          </div>
        </div>
      </div>








      <!-- options -->
      <div class="col-md-12" style="padding: 20px 0px;">
          <div class="form-group row">

              @component('components.options.options', [
                'options' => $options,
                //'dataValue' => $data->options
              ])
              @endcomponent
          </div>
      </div>


      <div class="col-md-12">
        <div class="form-group row">
          <div class="col-lg-1"></div>
          <div class="col-lg-11">
            <x-buttons.but_submit/>
          </div>
        </div>
      </div>

    </div>





      {{--
      <div class="row">
        <h2>{{__('words.type_name')}}</h2>
        @isset($options)
          @foreach( $options as $option )
            <div class="col-md-6">
                  <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.type_'.$option->type) }} </label>
                  <div class=" col-lg-9 col-md-9 col-sm-12">
                      <input type="text" class="form-control {{ $errors->has('type_'.$option->type) ? ' is-invalid' : '' }}" required
                      value="{{ old('type_'.$option->type) }}" name="{{'type_'.$option->type}}" placeholder="">
                    <!-- <span class="form-text text-muted">Please enter your full name</span> -->
                    @if ($errors->has('type_'.$option->type))<span class="invalid-feedback">{{ $errors->first('alias') }}</span>@endif
                  </div>
            </div>
          @endforeach
        @endisset
      </div>
      --}}




        <br><br>







</form>





          </div>
        </div>
      </div>
    </div>
  </div>
</div>



@section('js_pagelevel')
<script>
$(document).ready(function() {
    $('.select_2').select2();
});
</script>
<x-admin.dropify-js/>


<script>

function setType(e){

  var option = document.getElementById('option');
  // var type = e.target.children[e.target.value].getAttribute('data-type');
  var type = e.options[e.selectedIndex].getAttribute('data-type');
  var value = document.getElementById('value');  // input for input,file
  var valueSelect = document.getElementById('valueSelect');  // dropdownList
  var optionTitle = document.getElementById('option_title');

  //alert(option.value);
  if (type =="none")               // Hide Input and DropdownList if Firest Option is selected
  {
    valueSelect.style.visibility = 'hidden';
    value.style.visibility = 'hidden';
    optionTitle.style.visibility = 'hidden';
    return;
  }

  if (type == "select")           // get Data and Fill DropdownList
  {
    value.style.visibility = 'hidden';
    valueSelect.style.visibility = 'visible';
    optionTitle.style.visibility = 'visible';
    var fill=autoCompelete(option.value,'valueSelect'); // get data and fill drobdownlist
  }
  else                             // if type text,file
  {
    valueSelect.style.visibility = 'hidden';
    value.style.visibility = 'visible';
    optionTitle.style.visibility = 'visible';
    value.setAttribute('type', type); // input for input,file
  }




}

function createElement(type, attributes)
{
    var element = document.createElement(type);
    for(attribute in attributes){
      element.setAttribute(attribute, attributes[attribute]);
    }

    return element;
}

function addElement()
{
    var form = document.getElementById('form');
    var option = document.getElementById('option');
    var value = document.getElementById('value');
    var valueSelect = document.getElementById('valueSelect');
    var dataTable = document.getElementById('data');
    var optionTitle = document.getElementById('option_title');

    //Get Current Option Type
    // var optionType = option.children[option.value].getAttribute('data-type');
    var optionType = option[option.selectedIndex].getAttribute('data-type')

    // if no option select then dont insert and give error message
    if (option.value == 0)
    {
      optionsError.innerHTML = "Select Attribute and Value ";
      return;
    }

    // if Cuurent option is (select) Check to not select the firest one
    if (optionType == "select")
    {
      if( valueSelect.value == 0 )
      {
        optionsError.innerHTML = "Select Item from Attribute Values";
        return;
      }
    }
    // if Cuurent option is (text) Check to have value
    if (optionType == "text")
    {
      if(value.value == "" || value.value == null || value.value.length > 1000)
      {
        optionsError.innerHTML = "Insert Value Not More Than 1000 Charactrs";
        return;
      }
    }



    var flg=false;
    var chkid=dataTable.childElementCount;
    while (flg == false) {
    var newid = dataTable.getElementsByClassName(chkid).length;
    if (newid == 0 )
    {flg = true;}
    else
    { chkid = chkid +1 ;}
    }


    //var tr = createElement('tr', {class: dataTable.childElementCount});
    var tr = createElement('tr', {class: chkid});
    var td1 = createElement('td');
    var td2 = createElement('td');
    var td3 = createElement('td', {id: chkid, class: 'btn btn-danger'});
    var td4 = createElement('td');
    var td5 = createElement('td');
    var td6 = createElement('t6');


    // td1.innerHTML = option.children[option.value].getAttribute('data-name');
    td1.innerHTML =  option[option.selectedIndex].getAttribute('data-name')

    if (optionType == "select")
    {
      td5.innerHTML = valueSelect.value;
      td2.innerHTML = valueSelect.options[valueSelect.selectedIndex].text;  //valueSelect.value + valueSelect.innerHTML;
    }
    if (optionType == "text" )
    {
      td2.innerHTML = value.value;
      td6.innerHTML = optionTitle.value;
    }

    td3.addEventListener('click', deleteElement, false);
    td4.innerHTML = optionType;

    tr.appendChild(td1);
    tr.appendChild(td2);
    tr.appendChild(td3);
    tr.appendChild(td4);
    tr.appendChild(td5);
    // tr.appendChild(td6);
    dataTable.appendChild(tr);


    var hidden1 = createElement('input', {type:'hidden', name: 'options['+(td3.id)+']', class: td3.id, value: option.value});
    if (optionType == "text") {
      var hidden2 = createElement('input', {type:'hidden', name: 'values['+(td3.id)+']', class: td3.id, value: value.value});
    }
    if (optionType == "select") {
      var hidden2 = createElement('input', {type:'hidden', name: 'values['+(td3.id)+']', class: td3.id, value: valueSelect.options[valueSelect.selectedIndex].text});
    }
    var hidden3 = createElement('input', {type:'hidden', name: 'types['+(td3.id)+']', class: td3.id, value: optionType});
    var hidden4 = createElement('input', {type:'hidden', name: 'SelValue['+(td3.id)+']', class: td3.id, value: valueSelect.value});
    var hidden5 = createElement('input', {type:'hidden', name: 'optionName['+(td3.id)+']', class: td3.id, value: option.children[option.selectedIndex].getAttribute('data-name')});
    var hidden6 = createElement('input', {type:'hidden', name: 'titles['+(td3.id)+']', class: td3.id, value: optionTitle.value});

    form.appendChild(hidden1);
    form.appendChild(hidden2);
    form.appendChild(hidden3);
    form.appendChild(hidden4);
    form.appendChild(hidden5);
    form.appendChild(hidden6);

    if(dataTable.childElementCount >= 1){
      dataTable.style.display = "table-row-group";
    }

    value.value = null;
    optionTitle.value = null;

}

function deleteElement(event)
{
    var id = event.target.id;
    var dataTable = document.getElementById('data');
    var saveButton = document.getElementById('save');
    var paras = document.getElementsByClassName(id);

    while(paras[0])
    paras[0].parentNode.removeChild(paras[0]);

    if(dataTable.childElementCount < 0){
    // saveButton.style.display = "none";
    dataTable.style.display = "none";
    }
}

function autoCompelete(crit,controlname)
{
    if(crit)
    {
        $.ajax({
            //url: "Search_option_values",
            url: "{{ route('dashboard.item.search_option_values') }}",
            type: "GET",
            dataType: "json",
            data : { crit: crit },
            success:function(data) {
              var valueSelect = document.getElementById(controlname);  // dropdownList
              $(valueSelect).empty();
              $(valueSelect).append('<option value="0">Select</option>');

              for (var key in data) {
                var obj = data[key];
                $(valueSelect).append('<option value="'+ obj['id'] +'">'+ obj['value'] +'</option>');
              }
            },
            error: function(response){
            console.log(response);
            alert('Error'+response);
            }
        });
      }  else  {
        alert('no data');
      }
}

</script>
@endsection

@endsection
