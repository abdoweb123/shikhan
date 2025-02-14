@extends('front.layouts.new')

@section('head')

<style>
.file-upload {
            background-color: #ffffff00;
            width: 100%;
            max--width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .file-upload-btn {
            width: 100%;
            margin: 0;
            color: #fff;
            background: #1FB264;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #15824B;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .file-upload-btn:hover {
            background: #1AA059;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .file-upload-btn:active {
            border: 0;
            transition: all .2s ease;
        }

        .file-upload-content {
            display: none;
            text-align: center;
        }

        .file-upload-input {
            position: absolute;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            outline: none;
            opacity: 0;
            cursor: pointer;
        }

        .image-upload-wrap {
            margin-top: 20px;
            border: 4px dashed #1FB264;
            position: relative;
        }

        .image-dropping,
        .image-upload-wrap:hover {
            background-color: #1FB264;
            border: 4px dashed #ffffff;
        }

        .image-title-wrap {
            padding: 0 15px 15px 15px;
            color: #222;
        }

        .drag-text {
            text-align: center;
        }

        .drag-text h3 {
            font-weight: 100;
            text-transform: uppercase;
            color: #15824B;
            padding: 60px 0;
        }

        .file-upload-image {
            max-height: 200px;
            max-width: 200px;
            margin: auto;
            padding: 20px;
        }

        .remove-image {
            width: 200px;
            margin: 0;
            color: #fff;
            background: #cd4535;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #b02818;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .remove-image:hover {
            background: #c13b2a;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .remove-image:active {
            border: 0;
            transition: all .2s ease;
        }
        span.span-number-em {
            font-size: 10px;
            text-align: left;
            direction: ltr;
            color: #0e0e0e;
            display: table;
        }
        .tele__member_duty.div-job {
            font-size: 10px;
            font-weight: 700;
            color: #2b2b2bed;
        }
        span#addNew_row {
    color: #fff;
    background-color: #1fb264;
    padding: 8px;
    font-weight: 700;
    font-size: large;
    border-radius: 15%;
}
.form-control {
    margin: 5px;
}
.single-courses-item {
    padding: 5px;
    border-radius: 15px !important;
    -webkit-box-shadow: 0px 8px 16px 0px rgba(40, 167, 113, 0.21)
    box-shadow: 0px 8px 16px 0px rgb(40 167 69);
    -webkit-transition: 0.5s;
    transition: 0.5s;
}
.courses-image {
    text-align: right;
    margin-right: 5px;
}
.after_hint{
  color: #f77f7f;
  font-size: 13px;
  padding: 3px 8px;
}
.title_hint{
  font-size: 18px;
  color: #419f81;
  padding: 10px;
}
</style>

@endsection
@section('content')


 <!-- Start Page Title Area -->
        <div class="page-title-area item-bg2 jarallax" data-jarallax='{"speed": 0.3}' style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
            <div class="container">
                <div class="page-title-content">
                    <ul>
                        <li><h1>{{ __('core.prizes') }}</h1></li>
                    </ul>
                  @if (! Auth::guard('web')->user())
                      <h5 style="color: white;">{{ __('core.please_login') }}</h5>
                      @include('front.units.steps')
                  @endif

                </div>
            </div>
        </div>
        <!-- End Page Title Area -->

      {{--
      <section class="courses-area ptb-300">
            <div class="container">
              <div class="row">
                <div class="col-md-12" style="text-align: center;padding: 50px;font-size: 30px;font-weight: 600;">
                الصفحة تحت الانشاء
                </div>
              </div>
            </div>
      </section>
      --}}




        <!-- Start Courses Area -->
        <section class="courses-area ptb-300">
            <div class="container">
                <div class="courses-topbar">
                    <div class="row align-items-center">

                        {{--
                        <div class="col-lg-4 col-md-4">
                            <div class="topbar-result-count">
                                <h3>{{ __('core.prizes') }}</h3>
                            </div>
                        </div>
                        --}}

                        <div class="col-md-12">
                            <div class="topbar-ordering-and-search">
                                <div class="row align-items-center">
                                    <div class="col-12 text-center">
                                      <div class="topbar-result-count text-center p-2">
                                          <h3 style="color: #20bb88;padding: 34px 55px 11px 55px;">{{ __('core.prizes_type_publish') }}</h3>
                                      </div>

                                      <div class="row div-mass">
                                        @isset($prizeMessages)
                                          <span style="color: #060606;padding: 24px;text-decoration: underline;font-weight: bold;">يرجى نشر الرسائل التالية على اى من وسائل التواصل الاجتماعى</span>
                                          @foreach($prizeMessages as $prizeMessage)
                                            <div class="col-lg-12 col-md-12">
                                               <div class="single-courses-item mb-30">
                                                   <div class="courses-image" id="Div-content{{$loop->iteration}}" style="padding: 20px;">
                                                      {!! $prizeMessage->message!!}
                                                   </div>

                                                   <div class="courses-content">
                                                       <div class="d-flex justify-content-between align-items-center"></div>
                                                   </div>

                                                   <!--<div class="courses-box-footer">
                                                        <ul>
                                                           <li class="courses-price">
                                                               <button href="#" att-URL="#" class="btn btn btn-success  "  style="font-size: 13px;" onclick="myFunction({{$loop->iteration}})"> نسخ </button>
                                                           </li>
                                                       </ul>
                                                   </div>-->
                                               </div>
                                           </div>
                                           @endforeach
                                         @endisset
                                      </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="text-align: right;">
                <div class="col-lg-12 col-md-12 col-sm-12" style="font-weight: bold;">

                  <span style="color: #060606;padding: 24px;text-decoration: underline;font-weight: bold;">يرجى تسجيل المشاركات التى قمت بها فى الحقول التالية</span>

                  <form method="POST" class="row justify-content-center" action="{{ route('front.prizes.add_link_share') }}" enctype="multipart/form-data">
                      @csrf

                      <div class="row row w-100" style="padding: 10px">
                          @if ($errors->any())
                              <div class="alert alert-danger  w-100">
                                  <ul>
                                      @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                      @endforeach
                                  </ul>
                              </div>
                          @endif

                          @if (session()->has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! session()->get('success') !!}</li>
                                </ul>
                            </div>
                          @endif
                      </div>


                      <div class="row w-100" style="padding: 30px;box-shadow: 0px 6px 13px #08175112;border-radius: 20px;border: 1px solid #ccf2cc;margin: 15px;">
                          <div class="col-12 row p-0">

                              {{--
                              <div class="form-group col-10 p-0">
                                <div id="div-input-link"class="col-12 p-0">
                                  <input type="text" class="form-control @error('link.0') is-invalid @enderror" name="link[]" id="link"  value=""   style="color: black" autofocus placeholder="رابط المشاركه *">
                                </div>
                              </div>
                              <div class="form-group col-2">
                                  <span id="addNew_row" class="fa fa-plus"></span>
                              </div>
                              --}}

                              <div class="form-group col-10 p-0">
                                <div id="div-input-link"class="col-12 p-0">
                                  <div class="title_hint">برجاء إدخال البريد الإلكترونى لكل من سجل عن طريقك</div>
                                  <div class="after_hint"> - يرجى الفصل بين كل بريد والآخر بعلامة ^</div>
                                  <textarea class="form-control @error('emails') is-invalid @enderror" rows="4" cols="50" name="emails" style="color: black" autofocus placeholder="بريد من سجل عن طريقك">{{ old('emails', $UserData ? $UserData->emails : '' ) }}</textarea>
                                </div>
                              </div>

                              <div class="form-group col-10 p-0">
                                <div id="div-input-link"class="col-12 p-0">
                                  <div class="title_hint">برجاء ادخال إجمالى عدد المشاركات التى قمت بها على الواتس اب</div>
                                  <textarea class="form-control @error('whatsapp') is-invalid @enderror" rows="4" cols="50" name="whatsapp" style="color: black" autofocus placeholder="إجمالى مشاركات الواتس اب">{{ old('whatsapp', $UserData ? $UserData->whatsapp : '' ) }}</textarea>
                                  <!-- <span class="after_hint">يرجة كتابة مجموع المشاركات على الواتس اب</span> -->
                                </div>
                              </div>

                              <div class="form-group col-10 p-0">
                                <div id="div-input-link"class="col-12 p-0">
                                  <div class="title_hint">برجاء ادخال روابط التليجرام التى قمت بمشاركة رسائلنا عليها</div>
                                  <div class="after_hint">يرجى الفصل بين كل رابط بعلامة ^</div>
                                  <textarea class="form-control @error('telegram') is-invalid @enderror" rows="4" cols="50" name="telegram" style="color: black" autofocus placeholder="روابط التليجرام">{{ old('telegram', $UserData ? $UserData->telegram : '' ) }}</textarea>
                                </div>
                              </div>

                              <div class="form-group col-10 p-0">
                                <div id="div-input-link"class="col-12 p-0">
                                  <div class="title_hint">تستطيع كتابة نص عام لما قمت بنشرة بالارقام</div>
                                  <textarea class="form-control @error('description') is-invalid @enderror" rows="4" cols="50" name="description" style="color: black" autofocus placeholder="نص عام">{{ old('description', $UserData ? $UserData->description : '' ) }}</textarea>
                                  <!-- <span class="after_hint"></span> -->
                                </div>
                              </div>

                              <div class="form-group col-10 p-0">
                                <div id="div-input-link"class="col-12 p-0">
                                  <div class="title_hint">برجاء إدخال روابط الفيس بوك التى قمت بمشاركة رسائلنا عليها</div>
                                  <div class="after_hint">يرجى الفصل بين كل رابط بعلامة ^</div>
                                  <textarea class="form-control @error('links') is-invalid @enderror" rows="4" cols="50" name="links" style="color: black" autofocus placeholder="روابط الفيس بوك">{{ old('links', $UserData ? $UserData->links : '' ) }}</textarea>
                                </div>
                              </div>


                              @error('link.0')
                               <span class="invalid-feedback" role="alert">
                                   <strong>{{ $message }}</strong>
                               </span>
                              @enderror
                          </div>

                          {{--
                          <div class="col-12">
                            <div class="file-upload">
                                <button class="file-upload-btn" type="button" onclick="$('.file-upload-input').trigger( 'click' )">اضافة صورة مشاركة الرسالة</button>

                                <div class="image-upload-wrap">
                                    <input class="file-upload-input" type='file' name="photos[]" onchange="readURL(this);" accept="image/*" multiple />
                                    <div class="drag-text">
                                        <h3>ارفق صورة المشاركه هنا</h3>
                                    </div>
                                </div>
                                <div class="file-upload-content" id="imag-div">

                                </div>
                                @error('photos.0')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                                @enderror
                            </div>
                          </div>
                          --}}

                          {{--
                          <div class="col-12">
                            <div class="form-group col-10 p-0">
                              <div id="div-input-note"class="col-12 p-0">
                                <input type="text" class="form-control @error('note') is-invalid @enderror" name="note" id="note"  value="{{old('note')}}"   style="color: black" autofocus placeholder="ملاحظات">
                              </div>
                              @error('note')
                               <span class="invalid-feedback" role="alert">
                                   <strong>{{ $message }}</strong>
                               </span>
                              @enderror
                            </div>
                          </div>
                          --}}

                          <div class="col-12" style="text-align: center;padding: 30px;">
                              <button type="submit" class="btn clever-btn w-100" style="background-color: #25b981;color: white;">
                                  حفظ
                              </button>
                          </div>

                      </div>
                  </form>
                </div>


                </div>
        </section>
        <!-- End Courses Area -->







@endsection
@section('script')
<script>
function myFunction(id) {
  /* Get the text field */
  var copyText = document.getElementById("Div-content"+id);

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

  /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.textContent);

  /* Alert the copied text */
  alert("تم النسخ بنجاح ");
}
$(document).ready(function(){


    //  $('html,body').animate({
    //     scrollTop: $("#div_words").offset().top
    // }, 'slow');

  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#ItemsDiv .ItemDiv").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  $('#addNew_row').click(function() {
     $('#div-input-link').append('<input type="text" class="form-control @error('link') is-invalid @enderror" name="link[]" id="link"   required  maxlength="50" style="color: black" autofocus placeholder="رابط المشاركه *">');
  });
});
function readURL(input) {
            if (input.files && input.files[0]) {

                   $('#imag-div').html(' ');
                  for(var i=0;i < input.files.length;i++) {
                     const file = input.files[i];
                     if(file.size > 5242880 || file.fileSize > 5242880) {
                       errorMessage = 'Files must be less than 5MB.';
                       alert(errorMessage);
                     }else{
                       var reader = new FileReader();

                       reader.onload = function(e) {
                           $('.image-upload-wrap').hide();

                           $('#imag-div').append('<img class="file-upload-image" src="'+ e.target.result+'" alt="your image" />');

                           $('.file-upload-content').show();

                           $('.image-title').html(input.files[i].name);
                       };

                       reader.readAsDataURL(input.files[i]);


                       // console.log(input.files[i].name);

                     }

                   }

            } else {
                removeUpload();
            }
        }

        function removeUpload() {
            $('.file-upload-input').replaceWith($('.file-upload-input').clone());
            $('.file-upload-content').hide();
            $('.image-upload-wrap').show();
        }
        $('.image-upload-wrap').bind('dragover', function () {
            $('.image-upload-wrap').addClass('image-dropping');
        });
        $('.image-upload-wrap').bind('dragleave', function () {
            $('.image-upload-wrap').removeClass('image-dropping');
        });
</script>

@endsection
