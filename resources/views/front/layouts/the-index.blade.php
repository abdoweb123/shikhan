<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
  <head>
    @include('front.layouts.design_3.head')
    @yield('head')

    <style>
      @media only screen and (max-width: 767px) {.page-title-area {height: 20px !important;visibility: collapse;}}
      .prim-back-color{background-color: #ffb751 !important;}
      .prim-back-color-light{background-color: #e3f4ec;}
      .prim-back-color-dark{background-color: #ffe3a0;}
      .prim-back-color-darker{background-color: #30b578;}
      .prim-color{color: #186232 !important;}
      .prim-color-light{color: ##cc8653 !important;}
      .prim-color-dark{color: #3ba274 !important;}
      .prim-color-border{border: 1px solid #b2dbc9;}
      .prim-btn{background-color: #cfeade;color: #8c5159;border-radius: 50px;border: none;}

      .sec-back-color-light{background-color: #fbf1eb;}
      .sec-back-color-dark{background-color: #8c5159 !important;}
      .sec-color{color: #8c5159 !important;}
      .sec-btn{background-color: #8c5159;color: #cfeade;border-radius: 50px;border: none;}
      .sec-color-border{border: 1px solid #8c5159;}

      .main_title{font-size: 30px;padding: 10px 0px;text-align: initial;}

      .inner_banner{background-position: top;}
      .inner_page_title{color: white !important;font-weight: bold;font-size: 32px;padding: 15px;}

      .white-area-shadow{box-shadow: 1px 9px 10px #262c2917;}
      .but-more{
        border-radius: 21px;
        padding: 5px 18px;
        padding-right: 18px;
        background-color: #1aab6d;
        color: white;
        margin: 12px;
        border: none;
      }

      .but-default{
        border-radius: 5px;
        padding: 5px 14px;
        padding-right: 18px;
        background-color: #cfeade !important;
        color: #8c5159 !important;
        border: 1px solid #8c5159;
      }

      .but-default-rounded{
        border-radius: 20px;
        padding: 5px 14px;
        padding-right: 18px;
        background-color: #cfeade !important;
        color: #8c5159 !important;
        border: 1px solid #8c5159;
      }

      .but-login{
        background-color: #3ba274 !important;
        border: none;
        color: white !important;
        border-radius: 25px;
      }

      .but-special{
        border-radius: 50px;
        padding: 10px 25px;
        background-color: #db883f !important;
        color: white !important;
        font-weight: bold;
      }

      .input-default{
        border: 1px solid #9ac5b2;
        box-shadow: 0px 4px 6px #cfeade;
        border-radius: 6px;
      }

      .owl-item{
        border: 1px solid #d1ccea;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 8px 21px 0 rgba(155, 146, 255, 0.29);
      }

      .home-video-bg {
        text-align: center;
        background-color: #dae0ff;
        padding: 10px 10px 10px 10px;
        /* box-shadow: 0px 8px 28px #0000004f; */
        border-radius: 10px;
        /* border: 1px solid #dacee8; */
        margin-bottom: 31px;
      }

      /* cards colofull */
        .card-colorfull.features { border-radius: 6px;}
        .card-colorfull {
          background-color: #fff;
          position: relative;
          margin-bottom: 30px;
          border-radius: 8px;
          -webkit-transform: perspective(1px) translateZ(0);
          transform: perspective(1px) translateZ(0);
          -webkit-transition-duration: 0.3s;
          transition-duration: 0.3s;
          cursor: pointer;
          overflow: hidden;
        }
        .features {  text-align: center;}
          @media (max-width: 992px)
            .main-features {
            padding: 1.5rem !important;
        }
        .main-features {
            position: relative;
            padding: 1.5rem;
            -webkit-transition: .3s all ease-in-out;
            -o-transition: .3s all ease-in-out;
            transition: .3s all ease-in-out;
        }

        .card-colorfull-bg-1 { background-color: rgba(134, 110, 232, 0.2) !important; }
        .card-colorfull-title-color-1 { color: #664dc9 !important; font-size: 30px; }
        .card-colorfull-icon-color-1 { font-size: 35px; color: #c3b8ee; }

        .card-colorfull-bg-2 { background-color: rgb(205, 242, 218) !important; }
        .card-colorfull-title-color-2 { color: #187539 !important; font-size: 30px; }
        .card-colorfull-icon-color-2 { font-size: 35px; color: #8dcca3; padding: 0px 0px 0px 10px; }

        .card-colorfull-bg-3 { background-color: rgba(255, 171, 0,0.2) !important; }
        .card-colorfull-title-color-3 { color: #e19803 !important; font-size: 30px; }
        .card-colorfull-icon-color-3 { font-size: 35px; color: #fc6; }
      /* ---- */


      /* --- */
      .corner-ribbon{
        z-index: 100;
        width: 200px;
        /* background: #e43; */
        background-image: linear-gradient(#8fff85, #009161);
        position: absolute;
        top: 9px;
        left: -70px;
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        line-height: 45px;
        color: white;
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
      }
      /* --- */

      /* special alert zoom today*/
      .alert-newevent{
        color: #27517d;
        background-color: #c9f1fb;
        border-color: #fdd2d0;
      }




      .bread-crumb ul li{
        color: gray;
        margin-left: 4px;
        margin-right: 4px;
      }
      .bread-crumb ul li a{
        color: #2d2d2d;
        text-decoration: underline;
      }

      /* login regester but don'e show in ipad */
      @media only screen and (min-width: 768px) and (max-width: 991px){
          .top-header-btn {
            display: block;
        }
      }

      /* rate ranges colors */
      .rate_range_0{ background-color: #ff3737; }
      .rate_range_1{ background-color: #fd8e00; }
      .rate_range_2{ background-color: #ffbf00; }
      .rate_range_3{ background-color: #00ffe7; }
      .rate_range_4{ background-color: #009fff; }
      .rate_range_5{ background-color: #1aab6d; }
    </style>

      <title></title>
  </head>

  <body>

      @include('front.layouts.design_3.header')

      @yield('content')

      @include('front.layouts.design_3.footer')

      @include('front.layouts.design_3.script')

      @yield('script')

      <script type="text/javascript">
        $(document).ready(function() {
            $('#pobup_modal').modal('show');
        });
      </script>

{{--      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>--}}
      <script src="{{ asset('/assets/sweetalert/sweetalert2.all.js') }}"></script>

      <!-- for ads slide show -->

      <script>
        $("#ads_slideshow > div:gt(0)").hide();

        setInterval(function() {
          $('#ads_slideshow > div:first')
          .fadeOut(1000)
          .next()
          .fadeIn(1000)
          .end()
          .appendTo('#ads_slideshow');
        }, 5000);
      </script>



      @stack('js_pagelevel')
      <script>
          function ajaxLink(e,me,data_div,err_div,data)
          {
              // To hide any alerts
              // $('.alert').html('');
              $('#div_lesson_content_error').html('');
              $('#div_lesson_content').html('');


              e.preventDefault();
              var type='get';
              var url=$(me).attr("data-href");
              var data=data;
              console.log(url);
              gatAnyData(data_div,err_div,type,url,data);

          }

        function gatAnyData(data_div,err_div,type,url,data,special=null)
        {
            $.ajax({
               type: type,
               url: url,
               data: data,
               success: function (data) {
                     // console.log($('#'+data_div));
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
                              type: data['status'] ,
                              timer: 2000,
                              // showConfirmButton: false,
                              showCloseButton: true,
                              closeButtonColor: '#d33',
                              closeButtonText: 'Close'
                          });
                        }
                    }

                    if (data['html']) {

                        console.log(data['html']);

                        $('#'+data_div).html('');
                        $('#'+data_div).html(data['html']);
                    }



                   if (data['htmlErrors']) {

                       console.log(data['htmlErrors']);

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
                 console.log(xhr.responseText);
                   console.log('error');
                   console.log(xhr);
               },
             });

        }

      </script>




  </body>
</html>
