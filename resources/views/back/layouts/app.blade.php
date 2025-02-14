<!doctype html>
<html lang="en">
<head>
  @include('back/includes.head')
  @yield('back_css')
  @yield('css_pagelevel')

  <!-- select2 -->

      <title>@yield('title')</title>

  {{--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}
{{--  <link href="{{ asset('assets/select2/select2.css') }}" rel="stylesheet">--}}

{{--    <link rel="stylesheet" href="https://www.ibnkathir-edu.org/assets/admin/plugins/fontawesome-free/css/all.min.css">--}}
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->


    <!-- my rtl -->
{{--    <link rel="stylesheet" href="https://www.ibnkathir-edu.org/assets/admin/plugins/select2/css/select2.min.css">--}}
    <link rel="stylesheet" href="{{asset('assets/select2/select2.min.css')}}">

    <x-admin.dropify-css/>



  <style>
    #goTop {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: #2a3f549e;
        color: white;
        cursor: pointer;
        padding: 7px;
        border-radius: 13px;
    }
    #goTop:hover {
        background-color: #555;
    }
    .language_alies {
      font-size: 27px;
      padding: 10px 0px;
    }
    .them_back_color{background-color: #065764;}
    .them_title{background-color: #06505b;}
  </style>
      @stack('css_pagelevel')
</head>

<body class="nav-md them_back_color">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                @include('back/includes.sidebar')
            </div>
            <div class="top_nav">
                @include('back/includes.header')
            </div>
            <div class="right_col" role="main">

                @yield('content')
            </div>
        </div>
    </div>
    <button onclick="topFunction()" id="goTop" title="Go to top">Top</button>

    {{-- @include('back/includes.footer') --}}
    @include('back/includes.script')
    <x-admin.dropify-js/>

    {{--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
{{--    <script src="{{ asset('assets/select2/select2.min.js') }}"></script>--}}



    @yield('js_pagelevel')
    @stack('js_pagelevel')
    <script>

        // function gatData(data_div,err_div,type,url,data,special=null)
        // {
        //
        //     $.ajax({
        //        type: type,
        //        url: url,
        //        data: data,
        //        success: function (data) {
        //              // console.log(data);
        //              // return;
        //
        //             if (data['status']=='ma') {
        //                 jQuery('#popup_div').modal();
        //                 $('#details').html(data['ma_html']);
        //                 return;
        //             }
        //
        //             msg = data['msg'];
        //             if (msg) {
        //                 if (data['alert'] == 'swal') {
        //                   Swal.fire({
        //                       title: msg,
        //                       text: msg,
        //                       // type: data['status'] ,
        //                       showCloseButton: true,
        //                       closeButtonColor: '#d33',
        //                       closeButtonText: 'Close'
        //
        //                   });
        //                 }
        //             }
        //
        //             if (data['html']) {
        //                 $('#'+data_div).html('');
        //                 $('#'+data_div).html(data['html']);
        //             }
        //
        //             if (data['hide_model']) {
        //                 $('#newQuestionModal').modal('hide'); // translation modal question or answer
        //             }
        //
        //             if (data['remove_div_id']) {
        //                 $('#question_'+data['remove_div_id']).remove(); // when delete question delete it from screen
        //             }
        //
        //
        //
        //             if (data['link']) {
        //               window.location.href = '/' + data['link'];
        //             }
        //
        //             if (data['linkOut']) {
        //               window.location.href = data['linkOut'];
        //             }
        //
        //             if (data['redir']) {
        //               location.reload();
        //             }
        //
        //        },
        //        error: function (xhr, status, error){
        //          console.log(xhr.responseText);
        //        },
        //      });
        //
        // }

        // function ajaxForm(e,me,data_div,err_div,data)
  		// 	{
        //
  		// 		e.preventDefault();
  		// 		var type=$(me).attr('method');
  		// 		var url=$(me).attr('action');
  		// 		var data=$(me).serialize();
        //
  		// 		gatData(data_div,err_div,type,url,data);
  		// 	}

  			function ajaxlink(e,me,data_div,err_div,data)
  			{
  				e.preventDefault();
  				var type='get';
  				var url=$(me).attr("data-href");
  				var data=data;
  				gatData(data_div,err_div,type,url,data);
  			}


        //Get the button
        var goTop = document.getElementById("goTop");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
          if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            goTop.style.display = "block";
          } else {
            goTop.style.display = "none";
          }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
          document.body.scrollTop = 0;
          document.documentElement.scrollTop = 0;
        }



        function showLoading()
        {
            $('#loading_div').html(
                `<span class="spinner-border spinner-border-md" role="status" aria-hidden="true"></span> `
            );
        }

        function hideLoading()
        {
            $('#loading_div').html('');
        }

        function gatData(data_div,err_div,type,url,data,special=null)
        {
            $.ajax({
                type: type,
                url: url,
                data: data,
                success: function (data) {
                    // console.log(data);
                    // return;


                    if (data['status']=='ma') {
                        jQuery('#popup_div').modal();
                        $('#details').html(data['ma_html']);
                        return;
                    }

                    msg = data['msg'];
                    if (msg) {
                        if (data['alert'] == 'swal') {
                            Swal.fire({
                                title: msg,
                                text: msg,
                                // type: data['status'] ,
                                showCloseButton: true,
                                closeButtonColor: '#d33',
                                closeButtonText: 'Close'

                            });
                        }
                    }


                    if (data['html']) {
                        $('#'+data_div).html('');
                        $('#'+data_div).html(data['html']);
                    }

                    if (data['htmlErrors']) {
                        $('#'+err_div).html('');
                        $('#'+err_div).html(data['htmlErrors']);
                    }

                    if (data['hide_model']) {
                        $('#newQuestionModal').modal('hide'); // translation modal question or answer
                    }

                    if (data['remove_div_id']) {
                        $('#question_'+data['remove_div_id']).remove(); // when delete question delete it from screen
                    }

                    // if (data['link']) {
                    //   window.location.href = '/' + data['link'];
                    // }


                    if (data['linkOut']) {
                        window.location.href = data['linkOut'];
                    }

                    if (data['redir']) {
                        location.reload();
                    }

                },
                error: function (xhr, status, error){
                    console.log('error');
                    console.log(xhr);
                },
            });

        }


        function ajaxForm(e,me,data_div,err_div,data)
        {
            e.preventDefault();

            // console.log(me+ data_div+ err_div);

            var type=$(me).attr('method');
            var url=$(me).attr('action');
            var data=$(me).serialize();

            gatData(data_div,err_div,type,url,data);
        }
    </script>


    <!-- jQuery -->
{{--    <script src="https://www.ibnkathir-edu.org/assets/admin/plugins/jquery/jquery.min.js"></script>--}}
    <!-- Bootstrap -->
{{--    <script src="https://www.ibnkathir-edu.org/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>--}}
{{--    <script src="https://www.ibnkathir-edu.org/assets/admin/plugins/select2/js/select2.full.min.js"></script>--}}
    <script src="{{asset('assets/select2/select2.full.min.js')}}"></script>
    <!-- AdminLTE -->
{{--    <script src="https://www.ibnkathir-edu.org/assets/admin/dist/js/adminlte.js"></script>--}}
{{--    <!-- Summernote -->--}}
{{--    <script src="https://www.ibnkathir-edu.org/assets/admin/plugins/summernote/summernote-bs4.min.js"></script>--}}
{{--    <!-- OPTIONAL SCRIPTS -->--}}
{{--    <script src="https://www.ibnkathir-edu.org/assets/admin/plugins/chart.js/Chart.min.js"></script>--}}
    <!-- AdminLTE for demo purposes -->


    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

        })
    </script>

    <script>

        // as function ok
        // function confirmDelete(e, me)
        // {
        //
        //     e.preventDefault();
        //
        //     Swal.fire({
        //       title: 'هل تريد حذف هذه السجلات ؟',
        //       text: 'برجاء العلم ان الحذف سيؤثر على السجلات الأخرى المرتبطة بهذه السجلات',
        //       showCancelButton: true,
        //       confirmButtonColor: '#3085d6',
        //       cancelButtonColor: '#d33',
        //       confirmButtonText: 'نعم , قم بالحذف!' ,
        //       cancelButtonText: 'إلغاء'
        //     }).then((result) => {
        //       if (result.value) {
        //           $(me).closest('form').submit();
        //       }
        //     })
        //
        // };

        // get by class
        $( '.confirm-delete' ).on('click', function(e){
            e.preventDefault();

            Swal.fire({
                title: 'تأكيد الحذف',
                text: 'هل تريد الحذف',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم' ,
                cancelButtonText: 'لا'
            }).then((result) => {
                if (result.value) {
                    $(this).closest('form').submit();
                }
            })
        });



        function confirmDeleteAjax(e)
        {

            e.preventDefault();

            var type = $(this).attr('method');
            var url = $(this).attr('action');
            var data = $(this).serialize();
            data = data + '&' + 'ids=' + dataList;

            Swal.fire({
                title: 'هل تريد حذف هذه السجلات ؟',
                text: 'برجاء العلم ان الحذف سيؤثر على السجلات الأخرى المرتبطة بهذه السجلات',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم , قم بالحذف!' ,
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url : url ,
                        type : type ,
                        data : data , // {'ids':dataList},
                        dataType:"JSON",
                        success: function (data) {
                            // console.log(data);
                            // return;

                            if(data['success']) {
                                location.reload();
                            }

                            if(data['error']) {
                                Swal.fire("خطأ فى الحذف", data['error'], "error");
                            }
                        },
                        error: function (xhr, status, error)
                        {
                            if (xhr.status == 419) // httpexeption login expired or user loged out from another tab
                            {
                                window.location.replace( 'https://www.ibnkathir-edu.org/admin' );
                            }
                            Swal.fire("", "خطأ فى الحذف", "error");
                            console.log(xhr.responseText);

                        }
                    });
                }
            })

        };


    </script>

    <script>
        $(document).ready(function() {
            var name = 'lesson_ids';
            var arabicLabel = 'ترتيب درس ';
            $('select[name="'+name+'[][id]"]').on('change', function() {
                // Clear the additionalFields div
                $('#additionalFieldsContainer_'+name).html('');

                // Loop through the selected options and generate an input field for each one
                $(this).find('option:selected').each(function(index) {
                    var optionValue = $(this).val();
                    var optionText = $(this).text();

                    // Create a div with the class "form-group"
                    var formGroupDiv = $('<div>').addClass('form-group');

                    // Create a label for the input field
                    var label = $('<label>')
                        .attr('for', 'inputField_' + optionValue)
                        .text(arabicLabel +' : ' + optionText );
                    // input for sort number
                    var inputField = $('<input>')
                        .attr('type', 'number')
                        .attr('min', '0')
                        .attr('required','required')
                        .attr('class', 'form-control')
                        .attr('name', name + '['+index+'][sort]');

                    // input hidden for id of item
                    var hiddenInput = $('<input>')
                        .attr('type', 'number')
                        .attr('name', name + '['+index+'][id]').val(optionValue);

                    // Append the label and input field to the form-group div
                    formGroupDiv.append(label).append(inputField);

                    // Append the form-group div to the additionalFieldsContainer div
                    $('#additionalFieldsContainer_'+name).append(formGroupDiv);

                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var name = 'test_ids';
            var arabicLabel = 'ترتيب اختبار ';
            $('select[name="'+name+'[][id]"]').on('change', function() {
                // Clear the additionalFields div
                $('#additionalFieldsContainer_'+name).html('');

                // Loop through the selected options and generate an input field for each one
                $(this).find('option:selected').each(function(index) {
                    var optionValue = $(this).val();
                    var optionText = $(this).text();

                    // Create a div with the class "form-group"
                    var formGroupDiv = $('<div>').addClass('form-group');

                    // Create a label for the input field
                    var label = $('<label>')
                        .attr('for', 'inputField_' + optionValue)
                        .text(arabicLabel +' : ' + optionText );
                    // input for sort number
                    var inputField = $('<input>')
                        .attr('type', 'number')
                        .attr('min', '0')
                        .attr('required','required')
                        .attr('class', 'form-control')
                        .attr('name', name + '['+index+'][sort]');

                    // input hidden for id of item
                    var hiddenInput = $('<input>')
                        .attr('type', 'number')
                        .attr('name', name + '['+index+'][id]').val(optionValue);

                    // Append the label and input field to the form-group div
                    formGroupDiv.append(label).append(inputField);

                    // Append the form-group div to the additionalFieldsContainer div
                    $('#additionalFieldsContainer_'+name).append(formGroupDiv);

                });
            });
        });
    </script>






</body>
</html>
