<link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/favicon.png') }}"/>
<!-- Bootstrap -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

{{--<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>--}}
<script src="{{ asset('assets/sweetalert/sweetalert2.all.js') }}"></script>

<link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- Font Awesome -->
<link href="{{ asset('assets/admin/css/font-awesome.min.css') }}" rel="stylesheet">
<!-- NProgress -->
<link href="{{ asset('assets/admin/css/nprogress.css') }}" rel="stylesheet">
<!-- iCheck -->
<link href="{{ asset('assets/admin/css/green.css') }}" rel="stylesheet">
<!-- bootstrap-progressbar -->
<link href="{{ asset('assets/admin/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
<!-- JQVMap -->
<link href="{{ asset('assets/admin/css/jqvmap.min.css') }}" rel="stylesheet"/>
<!-- Custom Theme Style -->
<link href="{{ asset('assets/admin/css/custom.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/css/index.css') }}" rel="stylesheet">

<!-- Datatables -->
<link href="{{ asset('assets/admin/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/css/scroller.bootstrap.min.css') }}" rel="stylesheet">


<!-- ckeditor -->
{{--<script src="//cdn.ckeditor.com/4.13.1/full/ckeditor.js"></script>--}}
<script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>

<script>
CKEDITOR.editorConfig = function( config ) {
  config.toolbarGroups = [
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
    { name: 'forms', groups: [ 'forms' ] },
    '/',
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
    { name: 'links', groups: [ 'links' ] },
    { name: 'insert', groups: [ 'insert' ] },
    '/',
    { name: 'styles', groups: [ 'styles' ] },
    { name: 'colors', groups: [ 'colors' ] },
    { name: 'tools', groups: [ 'tools' ] },
    { name: 'others', groups: [ 'others' ] },
    { name: 'about', groups: [ 'about' ] }
  ];
};
</script>



<style media="screen">
  .form-control {
      min-width: 90px;
  }
  span.invalid-feedback {
      color: #d00909;
      direction: rtl;
      text-align: center;
  }
  td.select-checkbox.sorting_1 {
      padding: 25px !important;
  }
  a.btn.btn-outline-primary {
      float: right;
      margin: 0 0 10px;
      color: white;
      background-color: #158bc3;
  }
  .dropify-render img {
      width: 150px !important;
  }
</style>
<!-- ... -->
    <!--<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>-->

<script type="text/javascript" src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
<meta name="_token" content="{{ csrf_token() }}" />

{{--
<script src='{{ asset("assets/admin/js/tinymce/js/tinymce/tinymce.min.js") }}'></script>
<script>
    if ($('html').attr('lang') == 'ar')
    {
        var direction = 'rtl' ;
        var toolbar_align = 'right' ;
        var language = 'ar' ;
    }
    else
    {
        var direction = 'ltr' ;
        var toolbar_align = 'left' ;
        var language = $('html').attr('lang') ;

    }
    var editor_config = {
        content_css: [
        ],
        setup: function (ed)
        {
            ed.on('change', function (e)
            {
                ed.save(e);
            });
        },
        relative_urls: false,
        remove_script_host: false,
        path_absolute : "{{ url('admin') }}/",
        document_base_url : "{{ url('admin') }}/",
        allow_unsafe_link_target: true,
        force_br_newlines : false,
        force_p_newlines : false,
        forced_root_block : '',
        // height: 200,
        directionality : direction,
        language : language,
        theme_advanced_toolbar_align : toolbar_align,
        theme_advanced_source_editor_wrap : false,
        convert_newlines_to_brs : true,
        entity_encoding : "raw",
        extended_valid_elements : 'option[*]',
        apply_source_formatting : false,

        selector: "textarea.widget_html",
        valid_children : '+h3[p|div|i|span|u|small|b|img],+a[p|div|i|span|u|small|b|h1|h2|h3|h4|h5|h6|img],+ul[#text|p|div|li|i|span|u|small|b|h1|h2|h3|h4|h5|h6|img],table[tbody[text|tr]|thead[text|tr]]',

        selector: ".craete_editor",
        theme: "modern",
        paste_data_images: true,
        plugins: [
            "advlist autolink lists link image imagetools charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
        toolbar1: "undo redo searchreplace | styleselect | bold italic underline | table | link image media ",
        toolbar2: " alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor hr | preview code quicklink",
        toolbar3: "sizeselect | bold italic | fontselect |  fontsizeselect",

        image_advtab: true,
        templates: [{
        title: 'Test template 1',
        content: 'Test 1'
        }, {
        title: 'Test template 2',
        content: 'Test 2'
        }],
        style_formats:
        [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},

            {title: 'Title', block: 'h1'},
            {title: 'Header', block: 'h2'},
            {title: 'Subheader', block: 'h3'},
            {title: 'Headers', items: [
                {title: 'Header 1', format: 'h1'},
                {title: 'Header 2', format: 'h2'},
                {title: 'Header 3', format: 'h3'},
                {title: 'Header 4', format: 'h4'},
                {title: 'Header 5', format: 'h5'},
                {title: 'Header 6', format: 'h6'}
            ]},
            {title: 'Inline', items: [
                {title: 'Bold', icon: 'bold', format: 'bold'},
                {title: 'Italic', icon: 'italic', format: 'italic'},
                {title: 'Underline', icon: 'underline', format: 'underline'},
                {title: 'Strikethrough', icon: 'strikethrough', format: 'strikethrough'},
                {title: 'Superscript', icon: 'superscript', format: 'superscript'},
                {title: 'Subscript', icon: 'subscript', format: 'subscript'},
                {title: 'Code', icon: 'code', format: 'code'}
            ]},
            {title: 'Blocks', items: [
                {title: 'Paragraph', format: 'p'},
                {title: 'Blockquote', format: 'blockquote'},
                {title: 'Div', format: 'div'},
                {title: 'Pre', format: 'pre'}
            ]},
            {title: 'Alignment', items: [
                {title: 'Left', icon: 'alignleft', format: 'alignleft'},
                {title: 'Center', icon: 'aligncenter', format: 'aligncenter'},
                {title: 'Right', icon: 'alignright', format: 'alignright'},
                {title: 'Justify', icon: 'alignjustify', format: 'alignjustify'}
            ]}

        ],
        formats:
        {
            strikethrough: {inline : 'del'},
            forecolor: {inline : 'span', classes : 'forecolor', styles : {color : '%value'}},
            hilitecolor: {inline : 'span', classes : 'hilitecolor', styles : {backgroundColor : '%value'}},
            custom_format: {block : 'h1', attributes : {title : 'Header'}, styles : {color : 'red'}}
        },
        file_browser_callback : function(field_name, url, type, win) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
            if (type == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }
            // cmsURL = cmsURL + "&_token=" + $('meta[name="csrf-token"]').attr('content') ;

            tinyMCE.activeEditor.windowManager.open({
                file : cmsURL,
                title : 'Filemanager',
                width : x * 0.8,
                height : y * 0.8,
                resizable : "yes",
                close_previous : "no"
            });
        }
    };

    tinymce.init(editor_config);
</script>
--}}

<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />


<link href="{{ asset('assets/js_Admin/jquery.bxslider.min.css') }}" rel="stylesheet"/>

<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css">-->
<!--<link href="{{ asset('assets/js_Admin/bootstrap-datetimepicker.css') }}" rel="stylesheet"/>-->

