@extends('front.layouts.the-index')

@section('head')
<!-- Styles -->
@if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
<link rel="stylesheet" href="{{ asset('assets/front/style_rtl.css') }}">
@else
<link rel="stylesheet" href="{{ asset('assets/front/style.css') }}">
@endif

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<style>
   .header-area {
      position: absolute !important;
   }
   .section-padding-100-0 {
      padding-top: 145px;
      padding-bottom: 0;
    }
    body {

      background-color: #fff !important;
    }
    .ul-taps h4 {
      font-size: large;
    }
      select#country {
          display: block !important;
      }
      .nice-select.form-control {
    display: none !important;
    }
    input#code_country {
    direction: ltr;
    padding: 5px;
    }
    .ul-taps{
    width: 100%;
    display: inline-flex;
    }
    .ul-taps li{

    text-align: center;
    width: 50%;
    }
    .ul-taps li h4{
      margin-bottom: 15px !important;
        margin-top: -18px !important;
        border: 2px solid #e8bb8f;
        box-shadow: 1px 2px 12px 0px #eabe92;
        padding: 10px;
        border-radius: 5px;
        color: #00c1e8;
    }
    h4.unactive {
        background-color: #d89a5f;
        color: #fff !important;
    }
    h4.unactive:hover {
        background-color: #fff;
        color: #d89a5f !important;
    }
    button.btn.clever-btn.w-100 {
    color: #fff;
    background: #2266ae;
    }
    ul.ul-taps {
      margin-bottom: 25px;
    }
    @media only screen and (max-width: 767px){
    /* .register-now .register-now-countdown {
        display: none !important;
      } */
    .register-now .register-contact-form {
        margin-top: 45px !important;
        padding: 6px !important;
    }
    .ul-taps li h4 {
        font-size: 18px !important;
        padding: 4px;
        margin: 7px 2px !important;
        font-weight: 900;
    }

    }
    .section-padding-100-0 {
    padding-top: 105px;
    }
    body {
    background-color: #d0dafb;
    }
    .color-title {
    color: rgb(107 75 41);
    }
    .color-content {
    color: #d89a5f;
    }
</style>

<style>
  select#country ,select#gender {
      display: block !important;
  }
  .nice-select.form-control {
    display: none !important;
    }
    input#code_country {
    direction: ltr;
    padding: 5px;
    }
    .register-now .register-contact-form .forms .form-control {
      color: #000000bf !important;
  }
</style>

@endsection
@section('content')


<div id="up"></div>


 <!-- Start Page Title Area -->
        <div class=" item-bg2 jarallax" data-jarallax='{"speed": 0.3}' style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
            <div class="container">
                <div class="page-title-content" style="text-align: center;">
                    <ul>
                        {{--<li><h1>{{ __('core.prizes') }}</h1></li>--}}
                        <li>
                          {{--<h3>⭐️مسابقة مهرجان الجوائز النقدية الكبرى⭐️</h3>--}}
                          <h3 class="sec-color">💵  المسابقة الرابعة من أكاديمية البلدة الطيبة 💵</h3>
                          <h3 class="sec-color">💵 أكثر من ثلاثة مليون  وثمانمائة وأربعين ألف ريال يمني 💵</h3>
                        </li>
                    </ul>
                  {{--
                  <h5 style="color: white;">{{ __('core.header_content') }}</h5>
                   <br>
                   @if (! Auth::guard('web')->user())
                      @include('front.units.steps')
                  @endif
                  --}}
                </div>
            </div>
        </div>
        <!-- End Page Title Area -->

        @include('front.content.auth.register_every_page')

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

                        <div class="col-lg-8 col-md-8">
                            <div class="topbar-ordering-and-search">
                                <div class="row align-items-center">
                                    <div class="col-lg-5 col-md-6 col-sm-6">
                                    </div>
                                    {{--
                                    <div class="col-lg-5 col-md-6 col-sm-6">
                                        <div class="topbar-search">
                                           <form>
                                                <label><i class="bx bx-search"></i></label>
                                                <input id='myInput' type="text" class="input-search" placeholder="Search here..." >
                                            </form>
                                        </div>
                                    </div>
                                    --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="text-align: right;">




                                    @if (auth())
                                      @if(auth()->id() == 5972)
                                        {!! $data['html'] !!}

                                      @endif
                                    @endif



                    <div style="margin-bottom: 55px;padding: 20px;background-color: #f7f7f7;border-radius: 17px;border: 1px solid #c19b2f;box-shadow: 1px 5px 20px #00000040;text-align: center;" class="ol-lg-12 col-md-12 col-sm-12">
                      @include('front.prizes.winners')
                    </div>








                  <br><br><br>

                  <!-- Prize data -->
                  <div class="col-lg-12 col-md-12 col-sm-12 text-center" style="font-size: x-large;font-weight: bold;">
                    {{--مسابقة مهرجان الجوائز النقدية الكبرى<br>--}}
                   💵  المسابقة الرابعة من أكاديمية البلدة الطيبة 💵<br>
                   💵 أكثر من ثلاثة مليون  وثمانمائة وأربعين ألف ريال يمني 💵<br>
                  </div>



                  <br><br>

                  <div class="table-responsive" style="text-align: center;padding: 40px 0px;">
                    <table class="table table-hover" style="border: 1px solid #cfe1c6;font-size: 16px;">
                      <thead>
                        <tr style="background-color: #d8eccf;">
                          <th>رقم الفئة</th>
                          <th style="min-width: 300px;">فئات المسابقة</th>
                          <th style="min-width: 200px;">عدد المسارات</th>
                          <th>عدد الجوائز</th>
                          <th style="min-width: 175px;">قيمة الجائزة لكل مسار</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">1</th>
                          <td>من انهى من دبلومات المرحلة الأولى من رمضان حتى يوم التقييم بأفضل تقدير وعند التساوي الذي حضر البث المباشر واختبر أكثر في دبلومات المرحلة الثانية فإن تساوى فالأفضل درجات في دبلومات المرحلة الثانية</td>
                          <td>9 مسارات لكل دبلوم لوحده مسار ولجميع الدبلومات مسار ولكل مسار ثلاث جوائز</td>
                          <td>27 جائزة</td>
                          <!-- <td>الأول 80 الف <br> الثاني 50 الف<br> الثالث 30 الف<br></td> -->
                          <td>
                            <span style="color: #094400;font-weight: bold;font-size: 15px;">مسار جميع الدبلومات </span><br>
                            الاول ١٠٠ ألف<br>
                            الثاني ٩٠ ألف <br>
                            الثالث ٨٠ ألف<br>
                            <span style="color: #094400;font-weight: bold;font-size: 15px;">ومسار كل دبلوم</span><br>
                            الأول ٨٠ ألف <br>
                            الثاني ٥٠ ألف<br>
                            الثالث ٣٠ ألف<br>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">2</th>
                          <td>من انهى الدورات المقدمة في كل دبلوم من دبلومات المرحلة الثانية بأفضل تقدير وعند التساوي في التقدير من حضر واختبر أكثر وعند التساوي من مشارك في وسائل التواصل وعند التساوي قرعة</td>
                          <td>10 مسارات لكل دبلوم مسار ومسار لمجموع الدبلومات لكل مسار ثلاث جوائز</td>
                          <td>30 جائزة</td>
                          <!-- <td>الأول 80 الف<br> الثاني 50 الف<br> الثالث 30 الف<br></td> -->
                          <td>
                            <span style="color: #094400;font-weight: bold;font-size: 15px;">مسار جميع الدبلومات </span><br>
                            الاول ١٠٠ ألف<br>
                            الثاني ٩٠ ألف <br>
                            الثالث ٨٠ ألف<br>
                            <span style="color: #094400;font-weight: bold;font-size: 15px;">ومسار كل دبلوم</span><br>
                            الأول ٨٠ ألف <br>
                            الثاني ٥٠ ألف<br>
                            الثالث ٣٠ ألف<br>
                          </td>
                        </tr>
                        <tr>
                          <th scope="row">3</th>
                          <td>من حضر البث المباشر واختبر أكثر في دبلومات المرحلة الثانية وعند التساوي من تقديره أفضل في الاختبارات وعند التساوي من مشارك في وسائل التواصل أكثر وعند التساوي قرعة</td>
                          <td>مسار واحد من عشر جوائز.</td>
                          <td>10 جوائز</td>
                          <td>100 الف – 90 الف- <br> 80 الف –70 الف – <br>  60 الف – 50 الف – <br> 40 الف – 30 الف –  <br> 30 الف – 30 الف </td>
                        </tr>
                        <tr style="font-weight: bold;background-color: #d8eccf;">
                          <td colspan="2">الإجمالي</td>
                          <td>20 مسار</td>
                          <td>67 جائزة</td>
                          <td>3.620.000 ريال</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="col-lg-12 col-md-12 col-sm-12" style="padding-top: 30px;">
                    <span style="font-weight: bold;">ملاحظات :</span><br>
                    <span style="font-weight: bold;">-	آخر يوم قبل التقييم للمسابقة هو يوم</span>الأربعاء 2 ربيع الأول (2/3)الموافق 28 سبتمبر9/28  حتى نهاية اليوم<br>
                    <span style="font-weight: bold;">-	تعلن النتائج خلال 10 أيام من يوم التقييم</span><br>
                    <span style="font-weight: bold;">-	لا يوجد تكرار للفائزين في نفس القسم </span><br>
                    <span style="font-weight: bold;">-	كل من له أكثر من حساب في الأكاديمية يتواصل معنا لإيقاف حساباته المكررة أو سيلغى من الجوائز</span><br>
                    <span style="font-weight: bold;">-	المسابقة ليست مرتبطة باليمن فالذين في اليمن يستلمون جوائزهم باليمني والذين خارج اليمن بما يعادلها بالدولار ( طبقا لسعر البنك المركزى اليمني )</span>
                  </div>

                  <br><br><br>

                  <div class="col-lg-12 col-md-12 col-sm-12" style="padding-top: 25px;">
                        <span style="font-weight: bold;font-size: 17px;">تساؤولات حول مسابقة الأكاديمية الكبرى </span><br>

                        <span style="font-weight: bold;">كيف أتعرف أكثر على المسابقة وشروطها؟<br> &nbsp;&nbsp;</span>
                                  بزبارة صفحة المسابقة في المنصة: <a href="https://www.baldatayiba.com/ar/prizes">https://www.baldatayiba.com/ar/prizes</a><br><br>
                        <span style="font-weight: bold;">	كيف التسجيل في المسابقة؟<br> &nbsp;&nbsp;</span>
                                 لا تحتاج المسابقة لتسجيل، فكل من سجل في الأكاديمية مسجل فيها اذا كنت لم تشترك بعد في الأكاديمية قم بالاشتراك عبر الرابط التالي:  <a href="https://www.baldatayiba.com/ar/register">https://www.baldatayiba.com/ar/register</a> <br><br>
                        <span style="font-weight: bold;">هل يمكن لطالب جديد أن يفوز في المسابقة؟<br> &nbsp;&nbsp;</span>
                                 نعم ممكن إذا بذل وسعه ولا يزال في الوقت متسع كبيرجداً ولله الحمد، والمسابقة موضوعه لتستوعب الطلاب الجدد والقدامى<br><br>
                        <span style="font-weight: bold;">ماذا يعني يوم التقييم؟ ومتى تنتهي المسابقة وتعلن النتائج؟ وما الدورات الداخلة فيها؟<br> &nbsp;&nbsp;</span>
                            يوم التقييم هو اليوم الذي يبدأ التقييم وفرز النتائج فيه وكل ما قبله يحسب للطالب تنتهي المسابقة بيوم التقييم وهو الأربعاء 2 ربيع الأول (2/3)الموافق 28 سبتمبر9/28 نهاية اليوم  ، وتعلن النتائج بعد يوم التقييم بعشرة أيام، ويدخل فيها كل دبلومات المرحلة الأولى وما قدم من دورات دبلومات المرحلة الثانية.<br><br>

                        <span style="font-weight: bold;">هل ممكن تحسين درجة من لديه اختبارات سابقة ضعيفة الدرجة أو لم يوفق في اختبار ؟<br> &nbsp;&nbsp;</span>
                                 نعم يحق لجميع الطلاب إعادة مرة ثانية للاختبار بعد الدراسة جيداً، وتؤخذ الدرجة الأفضل، وبعد الاختبار الثاني لا يوجد أي تحسين فليحرص الجميع على المذاكرة الجيدة قبل الاختبار وقبل إعادته.<br><br>
                        <span style="font-weight: bold;">هل ممكن الفوز في أكثر من مسار أو قسم؟<br> &nbsp;&nbsp;</span>
                                 كلما نافست في مسارات أكثر كانت فرص فوزك أكثر وننصحك بالمنافسة في جميع المسارات إن استطعت، ولا يكرر الفائز في نفس القسم في المسابقة <br><br>
                        <span style="font-weight: bold;">إذا تساوى طالبين أو أكثر فكيف يتم الاختيار؟:<br> &nbsp;&nbsp;</span>
                                 إذا تساوى طالبين أو أكثر فيطبق ما ذكر في المعايير المفاضلة أولاً ثم القرعة وما تراه لجنة المسابقة.<br><br>
                        <span style="font-weight: bold;">هل المسابقة خاصة لليمنيين؟<br> &nbsp;&nbsp;</span>
                                ليست خاصة لأهل اليمن بل هي لكل طلبة علم الأكاديمية<br><br>

                                ـــــــــــــــــــــــــ🎁ــــــــ🎉ــــــــ💵ـــــــــــــــــــــــــــ
                        و يسعدنا الإجابة على أي تساؤل لديكم وفي أي وقت 24/7 في الدعم الفني: <br> <a href="https://t.me/joinchat/Cf7bgxQVxmo4MmFk">https://t.me/joinchat/Cf7bgxQVxmo4MmFk</a>

                    </div>
                  <br><br><br>
                  <!-- peize 2 ///////////////// -->





          <div style="padding-top: 50px;">.</div>
          <hr>





<!-- whats_app -->

                <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 30px;">
                  @if (! Auth::guard('web')->user())
                  <!-- ##### Register Now Start ##### -->
                  <section class="register-now section-padding-100-0 d-flex justify-content-between align-items-center" style="background-image: url(img/core-img/texture.png);">
                      <!-- Register Contact Form -->

                      <div class="register-now-countdown mb-100" style="text-align: center;">
                        <h2 class="color-title" >{{ __('core.landing1_header_title') }}</h2>
                        <h6 class="color-content">{{ __('core.landing1_header_content1') }}</h6>
                          <h1 class="color-title" >{{ __('core.landing1_header_title1') }}</h1>
                        <h6 class="color-content">{{ __('core.landing1_header_content2') }}</h6>
                          <!-- Register Countdown -->
                          <div class="register-countdown">
                              <div class="events-cd d-flex flex-wrap" data-countdown="2019/03/01"></div>
                          </div>
                      </div>



                      <!-- Register Now Countdown -->
                  </section>
                  <!-- ##### Register Now End ##### -->
                  @else
                    {{-- @include('front.prizes.subscrip_form') --}}
                  @endif
              </div>

                {{--
                <div class="row" id="ItemsDiv">
                    @foreach ($result as $item)
                        <div class=" ItemDiv col-lg-4 col-md-6">
                            <div class="single-courses-item mb-30">
                                <div class="courses-image">
                                    <a href="{{ route('courses.index',$item->alias) }}" class="d-block"><img src="{{ url($item->logo_path) }}" alt="image"></a>
                                </div>

                                <div class="courses-content">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="course-author d-flex align-items-center">
                                            <img class="shadow img-owl"  src="{{ asset('assets/img/logo2.png') }}" alt="{{ $item->name }}" >
                                            <span>@lang('core.app_name')</span>
                                        </div>
                                    </div>

                                    <h3><a href="{{ route('courses.index',$item->alias) }}" class="d-inline-block">{{ $item->name }}</a></h3>
                                    <p>{{ $item->description }}</p>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
                --}}
            </div>
        </section>
        <!-- End Courses Area -->







@endsection
@section('script')

<script>
$( "#country" )
  .change(function() {
    var str = "";
    $( "#country option:selected" ).each(function() {
      str = '+' + $( this ).attr('attr-code') ;
    });
    if(str !='+undefined'){

      $('input[name=code_country]#code_country').attr('value',str);

    }


  })
  .trigger( "change" );
</script>
<script>
$(document).ready(function(){
    //  $('html,body').animate({
    //     scrollTop: $("#div_words").offset().top
    // }, 'slow');

    $('html, body').animate({
        scrollTop: $('#up').offset().top
    }, 'slow');

  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#ItemsDiv .ItemDiv").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

@endsection
