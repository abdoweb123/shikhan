
<div class="col-md-12" style="padding: 20px 0px;">
  <div class="row">
      <span id="optionsError" style="color:red;text-align: center;" ></span>
      <div class="col-md-1">

      </div>
      <div class="col-md-3 form-group{{ $errors->has('options') ? ' has-error' : '' }}">
              <label class="control-label col-md-8 col-sm-12 col-xs-12 text-center" for="name">{{ __('general.options') }}<span class="required">*</span>
              </label>
              <div class="col-md-12 col-sm-12 col-xs-12">
              <select class="form-control col-md-12 col-xs-12" id="option" onchange="setType(this)" required>
                      <option value="0" data-name="none" data-type="none">Select</option>
                      @foreach($options as $option)
                      @if ($option->type != 'file') <!-- if options is file will get it in section imgoption -->
                      <option value="{{$option->id}}" data-name="{{$option->name}}" data-type="{{$option->type}}">{{$option->name}}</option>
                      @endif
                      @endforeach
              </select>
              @if ($errors->has('options'))
                      <span class="help-block">{{ $errors->first('options') }}</span>
              @endif
              </div>
      </div>
      <div class="col-md-4 form-group{{ $errors->has('values') ? ' has-error' : '' }}">
          <label class="control-label col-md-8 col-sm-12 col-xs-12 text-left" for="name">{{ __('general.value') }}<span class="required">*</span></label>
          <div class="col-md-12 col-sm-12 col-xs-12">
              <input type="text" value="" id="value" name="name" class="form-control col-md-12 col-xs-12" style="visibility : hidden">
              <select name="valueSelect" id="valueSelect" style="visibility : hidden; top:-33px;" class="form-control col-md-12 col-xs-12">
                  <option value="0">Select</option>
              </select>
              @if ($errors->has('values'))
                      <span class="help-block">{{ $errors->first('values') }}</span>
              @endif
          </div>
      </div>
      <div class="col-md-3 form-group{{ $errors->has('options') ? ' has-error' : '' }}">
          <label class="control-label col-md-8 col-sm-12 col-xs-12 text-left" for="name">{{ __('general.title') }}<span class="required">*</span></label>
            <input type="text" value="" id="option_title" name="option_title" class="form-control col-md-12 col-xs-12" style="visibility : hidden">
      </div>
      <div class="form-group col-md-1 col-sm-1 col-xs-4">
          <div class="col-md-12 col-sm-6 col-xs-12">
          <input type="hidden" name="_token" value="{{ Session::token() }}">
          <a class="btn btn-success" style="margin-top: 20%;border-radius: 50%" onClick="addElement()"><li class="fa fa-plus"></li> </a>
          </div>
      </div>
          <div class="col-md-12 col-sm-6 col-xs-12">
              <div class="col-lg-1"></div>
              <div class="col-lg-11">
                  <table  id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                      <tr>
                          <th width="21%">Option</th>
                          <th width="27%">Value</th>
                          <th width="22%">Title</th>
                          <th width="5%">Remove</th>
                      </tr>
                      </thead>
                      <tbody id="data">
                      <?php $a = 0; $b = 0; $c = 0; ?>
                      @if (Session::has('oldOptions'))
                          @foreach(Session::get('oldOptions') as $a=>$opt)
                              <tr class="{{$a}}">
                                  <td class="col-md-2">{{ $opt[4] }}</td>
                                  <td class="col-md-5">{{ $opt[1] }}</td>
                                  <td id="{{$a}}" class="btn btn-danger fa fa-minus-circle" onclick="deleteElement(event)"></td>
                                  <td class="col-md-1">{{ $opt[2] }}</td>
                                  <td class="col-md-1">{{ $opt[3] }}</td>
                                  <td class="col-md-1"></td>
                                  <input type="hidden" name="options[{{$a}}]" class="{{$a}}" value="{{ $opt[0] }}">
                                  <input type="hidden" name="values[{{$a}}]" class="{{$a}}" value="{{ $opt[1] }}" maxlength="1000">
                                  <input type="hidden" name="types[{{$a}}]" class="{{$a}}" value="{{ $opt[2] }}">
                                  <input type="hidden" name="SelValue[{{$a}}]" class="{{$a}}" value="{{ $opt[3] }}">
                                  <input type="hidden" name="optionName[{{$a}}]" class="{{$a}}" value="{{ $opt[4] }}">
                                  <input type="hidden" name="titles[{{$a}}]" class="{{$a}}" value="{{ $opt[5] }}">
                              </tr>
                          @endforeach
                      @else
                          @if (isset($dataValue))
                              @foreach($dataValue as $b=>$itemOption)

                                  <tr class="{{$b}}">
                                      <td class="col-md-2">{{$itemOption->option?->name}}</td>
                                      {{--
                                      @if($itemOption->option)
                                        @if(count($option->options->option_info))
                                            <td  class="col-md-2">{{$option->options->title}}</td>
                                            $option->option
                                        @endif
                                      @endif
                                      --}}

                                      <td class="col-md-5">{{$itemOption->value}}</td>
                                      <td class="col-md-1">{{$itemOption->option->type}}</td>
                                      <a> <td id="{{$b}}" class="btn btn-danger fa fa-times" onclick="deleteElement(event)"></td></a>
                                      <td class="col-md-1"></td>
                                      <td class="col-md-1"></td>
                                      <input class="{{$b}}" hidden name="options[{{$b}}]" value="{{$itemOption->option_id}}"/>
                                      <input class="{{$b}}" hidden name="values[{{$b}}]" value="{{$itemOption->value}}" maxlength="1000"/>
                                      <input class="{{$b}}" hidden name="types[{{$b}}]" value="{{$itemOption->option->type}}"/>
                                      <input class="{{$b}}" hidden name="SelValue[{{$b}}]"  value="">
                                      <input class="{{$b}}" hidden name="optionName[{{+$b}}]"  value="{{$itemOption->option?->title}}" >
                                      <input class="{{$b}}" hidden name="titles[{{$b}}]"  value="{{$itemOption->title}}" maxlength="1000">
                                  </tr>
                              @endforeach

                              {{--
                              @foreach($data->option_values as $c=>$option_value)
                                  <tr class="{{$b+$c+1}}">

                                      @if($option->options)
                                      @if(count($option->options->option_info))
                                          <td  class="col-md-2">{{$option_value->options->option_info[0]->title}}</td>
                                      @endif
                                      @endif
                                      <td class="col-md-5">{{$option_value->option_value_info[0]->title}}</td>
                                      <a> <td id="{{$b+$c+1}}" class="btn btn-danger fa fa-times" onclick="deleteElement(event)"></td></a>
                                      <td class="col-md-1">{{$option_value->options->type}}</td>
                                      <td class="col-md-1">{{$option_value->pivot->option_value_id}}</td>
                                      <input class="{{$b+$c+1}}" hidden name="options[{{$b+$c+1}}]" value="{{$option_value->option_id}}"/>
                                      @if($option->options)
                                      @if($option->options->option_info)
                                          <input class="{{$b+$c+1}}" hidden name="values[{{$b+$c+1}}]" value="{{$option_value->option_value_info[0]->title}}" maxlength="1000"/>
                                      @endif
                                      @endif
                                      <input class="{{$b+$c+1}} "hidden name="types[{{$b+$c+1}}]" value="{{$option_value->options->type}}"/>
                                      <input class="{{$b+$c+1}}" hidden name="SelValue[{{$b+$c+1}}]"  value="{{$option_value->pivot->option_value_id}}">
                                      <input class="{{$b+$c+1}}" hidden name="optionName[{{$b+$c+1}}]"  value="{{$option_value->options->option_info[0]->title}}">
                                      <input class="{{$b+$c+1}}" hidden name="titles[{{$b+$c+1}}]"  value="{{ $option_value->titles[app()->getlocale()] ?? ''}}">
                                  </tr>
                              @endforeach
                              --}}
                          @endif
                      @endif
                      </tbody>
                  </table>
              </div>
          </div>
  </div>
</div>


@push('js_pagelevel')
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
    if (option.value == 0) {
      optionsError.innerHTML = "Select Attribute and Value ";
      return;
    }

    // if Cuurent option is (select) Check to not select the firest one
    if (optionType == "select") {
      if( valueSelect.value == 0 ) {
        optionsError.innerHTML = "Select Item from Attribute Values";
        return;
      }
    }

    // if Cuurent option is (text) Check to have value
    if (optionType == "text") {
      if(value.value == "" || value.value == null || value.value.length > 1000) {
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
    tr.appendChild(td6);
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



</script>
@endpush
