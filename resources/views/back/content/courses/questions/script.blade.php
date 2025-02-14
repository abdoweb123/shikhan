

    <script type="text/template" id="element_template">

        <tr id="element-@{{id}}">
            <td style="direction: rtl;">


                <a class="handle1 btn btn-primary btn-sm"><i class="fa fa-sort"></i></a>
                <strong><a class="element_title" href="#element-form-@{{id}}">@{{type_veiw}} : @{{id}} - {{ '{{name.'.app()->getLocale() }}}} <i class="fa fa-edit" style="font-size: 18px;font-weight: 800;"></i></a></strong>
                <button type="button" class="btn btn-danger btn-sm pull-left" onclick="remove_element(@{{id}},'{{ __('core.delete_item') }}');"><i class="fa fa-trash"></i></button>
                <input type="hidden" name="questions[@{{id}}][type]" value="@{{type}}">
                <input type="hidden" name="questions[@{{id}}][id]" value="@{{record_id}}">

                <div class="element-form" id="element-form-@{{id}}">
                    <div class="col-lg-12">
                        <div class="row">

                          <div class="col-lg-6">

                              <div class="col-lg-4">
                                <div class="form-group">
                                  <div class="checkbox">
                                      <label for="status-@{{id}}">
                                          {{ __('field.status') }}
                                          <input class="checkbox" id="status-@{{id}}" type="checkbox" name="questions[@{{id}}][status]" value="1"  @{{#status}} checked="checked" @{{/status}}>
                                      </label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-lg-4">
                                <div class="form-group">
                                  <div class="checkbox">
                                          <label for="required-@{{id}}">
                                              {{ __('field.required') }}
                                              <input class="checkbox" id="required-@{{id}}" type="checkbox" name="questions[@{{id}}][required]" value="1"  @{{#required}} checked="checked" @{{/required}}>
                                          </label>
                                  </div>
                                </div>
                              </div>

                              <div class="col-lg-4">
                                  <div class="form-group">
                                      <label class="control-label" for="degree-@{{id}}" >{{ __('field.degree') }}</label>
                                      <input name="questions[@{{id}}][degree]" value="@{{degree}}" class="form-control" id="degree-@{{id}}">
                                  </div>
                                  <hr>
                                  <div class="clearfix"></div>
                              </div>

                          </div>
                          <div class="col-lg-6">
                              @foreach ($languages as $lang => $alies)
                                  <div class="form-group">
                                      <label class="control-label" for="name-@{{id}}-{{ $lang }}" >{{ __('field.question').' '.$alies }}</label>
                                      <input name="questions[@{{id}}][name][{{ $lang }}]" value="{{ '{{name.'.$lang }}}}" class="form-control" id="name-@{{id}}-{{ $lang }}">
                                  </div>
                              @endforeach
                          </div>
                                @{{#is.correct_answer}}
                                    <div class="col-lg-12">
                                        <label class="control-label" for="options-correct_answer-@{{id}}" >{{ __('field.correct_answer') }}</label>
                                        <div class="clearfix"></div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label for="correct_answer-@{{id}}">
                                                        {{ __('core.trueq') }}
                                                        <input class="radio" id="correct_answer-@{{id}}" type="radio" name="questions[@{{id}}][correct_answer][0]" value="1"  @{{#correct_answer.0}} checked="checked" @{{/correct_answer.0}}>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label for="correct_answer-@{{id}}">
                                                        {{ __('core.falseq') }}
                                                        <input class="radio" id="correct_answer-@{{id}}" type="radio" name="questions[@{{id}}][correct_answer][0]" value="0"  @{{^correct_answer.0}} checked="checked" @{{/correct_answer.0}}>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @{{/is.correct_answer}}
                		    </div>
                    </div>
                </div>

                    @{{#is.range}}
                    <div class="row element-values-Form">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="options-min-@{{id}}" >{{ __('field.min') }}</label>
                                <input name="questions[@{{id}}][options][min]" value="{{ '{{options.min' }}}}" class="form-control input-sm" id="options-min-@{{id}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="options-max-@{{id}}" >{{ __('field.max') }}</label>
                                <input name="questions[@{{id}}][options][max]" value="{{ '{{options.max' }}}}" class="form-control input-sm" id="options-max-@{{id}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="options-correct_answer-@{{id}}" >{{ __('field.correct_answer') }}</label>
                                <input name="questions[@{{id}}][correct_answer][]" value="{{ '{{correct_answer.0' }}}}" class="form-control input-sm" id="options-correct_answer-@{{id}}">
                            </div>
                        </div>
                    </div>
                    @{{/is.range}}

                    @{{#is.list}}
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
                                            <a class="btn btn-success" onclick="add_element_value(@{{id}},{status:1,correct_answer:0,type:'@{{type}}'});"><i class="fa fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="element_items" id="element_items-@{{id}}">
                                </tbody>
                            </table>
                        </div>
                        </div>
                    @{{/is.list}}


                </div>

                <!-- in create we pass this variable to hide update and delete but -->
                @if(! isset($hide_update))
                  <div>
                    <input type="submit" value="update_@{{id}}" name="updateThisOnly" class="btn btn-primary">
                  </div>
                  <div>
                    <input type="submit" value="delete_@{{id}}" name="deleteThisOnly" class="btn btn-danger">
                  </div>
                @endif

              </form>
            </td>
        </tr>



    </script>







  <script type="text/template" id="element_value_template">


          @{{#is.list}}
          <tr class="remove">
              <td>
                  <input type="hidden" name="questions[@{{id}}][answers][@{{valId}}][id]" value="@{{record_id}}">
                  <a class="btn btn-primary btn-sm pull-left"><i class="fa fa-sort"></i></a>
              </td>
              @foreach ($languages as $lang => $alies)
              <td>
                  <div class="form-group">
                      <label class="control-label" for="name-@{{id}}-{{ $lang }}" >{{ __('field.name').' '.$alies }}</label>
                      <input name="questions[@{{id}}][answers][@{{valId}}][name][{{ $lang }}]" value="{{ '{{name.'.$lang }}}}" class="form-control" id="name-@{{id}}-{{ $lang }}">
                  </div>
              </td>
              @endforeach
              <td>
                  <div class="form-group">
                      <div class="checkbox">
                          <label>
                              <input class="checkbox" type="checkbox" name="questions[@{{id}}][answers][@{{valId}}][status]" value="1"  @{{#status}} checked="checked" @{{/status}}>
                          </label>
                      </div>
                  </div>
              </td>
              <td>
                  <div class="form-group">
                      <div class="checkbox">
                          <label>
                              <input class="checkbox" type="checkbox" name="questions[@{{id}}][answers][@{{valId}}][is_correct]" value="@{{valId}}"  @{{#correct_answer}} checked="checked" @{{/correct_answer}}>
                          {{--<input class="checkbox" type="checkbox" name="questions_old[@{{id}}][correct_answer][@{{valId}}]" value="@{{valId}}"  @{{#correct_answer}} checked="checked" @{{/correct_answer}}>--}}
                          </label>
                      </div>
                  </div>
              </td>
              <td>
                  <a class="delete_element_value btn btn-danger pull-right btn-sm"><i class="fa fa-close"></i></a>
              </td>
          </tr>
          @{{/is.list}}


  </script>





<script type="text/javascript">

    var element_count = 0;
    var element_value_count = 0;

    var is_array =
    {
        true_false:{list:null,range:null,correct_answer:true},
        drop_list:{list:true,range:null,correct_answer:null},
        range:{list:null,range:true,correct_answer:null},
    };

    var langs = {!! json_encode([
        'range' => __('field.range'),
        'true_false' => __('field.true_false'),
        'drop_list' => __('field.drop_list'),
    ]) !!}

    $.each({!! $questions !!}, function(k,v)
    {
        questions_sortable();
        element_values_Sortable();

        v.type_veiw = langs[v.type];
        v.is = is_array[v.type];
        add_element(v);
    });

    $('.element-form').slideUp();
    $('.element-form').last().slideDown();
    var last_id = $('.element-form').last().attr('id');
    go_to(last_id);

    // $( "#add_element" ).click(function(){
    //     var type = $('#element_types').val();
    //     console.log(type);
    //
    //     if(type != '')
    //     {
    //         var vals =
    //         {
    //             name:[],
    //             id:'',
    //             degree:1,
    //             status:1,
    //             correct_answer:null,
    //             required:1,
    //             answers:{0:{status:1,correct_answer:0}},
    //             type_veiw:langs[type],
    //             is:is_array[type],
    //             type:type
    //         }
    //         $('.element-form').slideUp();
    //         add_element(vals);
    //         var last_id = $('.element-form').last().attr('id');
    //         go_to(last_id);
    //     }
    // });


    function create_element()
    {
        var type = $('#element_types').val();

        if(type != '')
        {
            var vals =
            {
                name:[],
                id:'',
                degree:1,
                status:1,
                correct_answer:null,
                required:1,
                answers:{0:{status:1,correct_answer:0}},
                type_veiw:langs[type],
                is:is_array[type],
                type:type
            }
            $('.element-form').slideUp();
            add_element(vals);
            var last_id = $('.element-form').last().attr('id');
            go_to(last_id);
        }
    };


    function add_element(vals)
    {

        element_count++;
        var view = vals;
        view.status = (view.status == 0 ? false : true);
        view.required = (view.required == 0 ? false : true);
        view.record_id = vals.id;
        view.id = element_count;
        var output = Mustache.render($('#element_template').html(), view);

        $('#questions_container').append(output);
        if (vals.answers)
        {
            $.each(vals.answers, function(key,val)
            {
                if ($.isNumeric(key))
                {
                    val.type = view.type;
                    val.status = (val.status == 0 ? false : true);
                    add_element_value(element_count,val);
                }
            });
        }
        questions_sortable();
    }


    function add_element_value(id,vals)
    {
        element_value_count++;
        var view = vals;
        view.record_id = (vals.id == undefined?element_value_count:vals.id);
        view.is = is_array[view.type];
        view.id = id;
        view.valId = element_value_count;
        var output = Mustache.render($('#element_value_template').html(), view);
        $('#element_items-'+id).append(output);
        element_values_Sortable();
    }

    function go_to(id)
    {
        var off = $('#' + id).offset().top;
        $("html,body").animate({scrollTop:off},1000);
    }

    function questions_sortable()
    {
        $('#questions_container').sortable({
            axis: "y",
            items:'tr',
            handle:'.handle1',
            forceHelperSize: true,
            forcePlaceholderSize: true
        });
    }

    function element_values_Sortable()
    {
        $('.element_items').sortable({
            axis: "y",
            handle:'.handle2',
            forceHelperSize: true,
            forcePlaceholderSize: true
        });
    }

    function remove_element(id,string)
    {
        if(confirm(string))
        {
            $('#element-'+id).remove();
        }
    }

    $(".sortable").sortable();
    $(".sortable > col-medium-").disableSelection();


    $('body').on('click', '.element_title', function()
    {
        $($(this).attr('href')).slideToggle();
        return false;
    }).on('click', '.delete_element_value', function()
    {
        if(confirm("{{ __('core.delete_item') }}"))
        {
            $(this).closest('.remove').remove();
        }
    });

</script>
