@extends('front.layouts.the-index')
@section('head')
<style>
.single-blog-post .post-image img{-webkit-transition:all 2s cubic-bezier(.2,1,.22,1);transition:all 2s cubic-bezier(.2,1,.22,1)}.main-banner-content h1,.main-banner-content p,.main-banner-content span,.main-banner-content.text-center .sub-title{color:#1d5ea4}.main-banner-content .default-btn .label{color:#fff}.single-instructor-member .social i{color:#f2b827;font-size:16px;margin-right:-2px}.single-instructor-member .member-image img{height:300px}.faq-accordion.faq-accordion-style-two{background-color:#fff;border-radius:15px;padding:10px
}

/* section  */
blockquote{
    border-left:none;
}

.quote-badge{
    background-color: rgba(0, 0, 0, 0.2);
}

.blockquote::before, blockquote::before {content: none}
.quote-box{
    overflow: hidden;
    margin-top: -50px;
    padding-top: -100px;
    border-radius: 17px;
    background-color: #4ADFCC;
    margin-top: 25px;
    color:white;
    width: 80%;
    box-shadow: 2px 2px 2px 2px #E0E0E0;
}

.quotation-mark{
    margin-top: -10px;
    font-weight: bold;
    font-size:100px;
    color:white;
    font-family: "Times New Roman", Georgia, Serif;
}

.quote-text{
    font-size: 19px;
    margin-top: -65px;
}

</style>

@endsection
@section('content')

      {{--@include('front.layouts.new_design.banner')--}}

      <div class="mx-auto" style="width: 1px;display: block;height: 10px;"></div>

      @include('front.content.auth.register_every_page')

      @include('front.include.global_alert')

      <!-- videos -->
      <div class="col-12" style="text-align: center; padding: 15px 0px;">
          @if(Auth::check())
            @include('front.include.support_videos.how_to_study')
          @else
            @include('front.include.support_videos.create_user')
          @endif
      </div>

      @if (auth()->check())
        @if (auth()->id() == 5972)
        <section class="" style="padding-bottom: 10px;">
          <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                  <div style="width: 100%;height: 100%;background-color: white;border-radius: 15px;box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;;padding: 7px;">
                    <div style="width: 100%;height: 100%;border-radius: 10px;text-align: center;">
                      <i class="fas fa-info-circle" aria-hidden="true" style="padding: 45px 0px 30px 0px;;font-size: 55px;color: #ff6021;"></i>
                      <div style="padding: 0px 0px 15px 0px;"><h2 style="color: #ff6021;">لماذا</h2><h1>البلدة الطيبة</h1></div>
                      <div style="padding: 11px 34px 37px 34px;font-size: 17px;color: #686868;">
                        ظهرت فكرة إنشاء الأكاديمية نتيجة لوجود حالة من الفراغ العلمي في البلدان العربية، ولمواجهة الأفكار الباطلة التي تعمل على زعزعة عقائد الناس، كانت الحاجة ماسة لتبسيط العلم الشرعي وجعله في متناول الجميع عبر الوسائل والتقنيات الحديثة.

                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-12 home-video-bg">
                    <?php
                      $raw_link = $home->video;
                      $link_without_details = preg_replace('/&(.*)/','',$raw_link);
                      $t = str_replace('watch?v=', 'embed/', $link_without_details);
                      $src=$t;
                    ?>
                    <iframe id="video_iframe" style="height: 420px;width: 100%;" class="embed-responsive-item" src="{{$src}}"></iframe>
                    <span style="font-size: 28px; color: #5c69a8;">{{ __('trans.video_intro') }}</span>
                </div>
                <div class="col-lg-2 col-md-12">

                </div>
            </div>
          </div>
        </section>
        @endif
      @endif

      <!-- 01 -->
      <section class="faq-area bg-f8e8e9 pb-100 pt-3" style="padding-bottom: 10px;">
        <div class="container">
          <div class="row">
              <div class="col-lg-6 col-md-12 home-video-bg">

                      {{--
                      <img src="{{ asset('assets/img/imac-bg.png')}}" alt="@lang('core.app_name')" style="height: 300px;">
                      <a href="{{$home->video}}" class="video-btn popup-youtube"><i class='bx bx-play'></i></a>
                      --}}
                      <?php
                        $raw_link = $home->video;
                        $link_without_details = preg_replace('/&(.*)/','',$raw_link);
                        $t = str_replace('watch?v=', 'embed/', $link_without_details);
                        $src=$t;
                      ?>
                      <iframe id="video_iframe" style="height: 420px;width: 100%;" class="embed-responsive-item" src="{{$src}}"></iframe>
                      <span style="font-size: 28px; color: #5c69a8;">{{ __('trans.video_intro') }}</span>
              </div>

              <div class="col-lg-6 col-md-12">
                <div class="col-lg-12">
                  <div class="card-colorfull features main-features wow fadeInUp card-colorfull-bg-2" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                    <div class="text-left">
                      <h4 class="card-colorfull-title-color-2"><i class="fas fa-info-circle card-colorfull-icon-color-2"></i> لماذا البلدة الطيبة</h4>
                      <p class="mb-0">
                        ظهرت فكرة إنشاء الأكاديمية نتيجة لوجود حالة من الفراغ العلمي في البلدان العربية، ولمواجهة الأفكار الباطلة التي تعمل على زعزعة عقائد الناس، كانت الحاجة ماسة لتبسيط العلم الشرعي وجعله في متناول الجميع عبر الوسائل والتقنيات الحديثة.<br>
                        •  تخريج طلبة على قدر كافٍ من العلم والتمكن علمياُ ومهارياً في العلوم الشرعية والعلوم المساعدة.<br>
                        •  تنمية المهارات وبناء الملكات العلمية التي يحتاجها طلبة العلم.<br>
                        •  مناهج تربوية إيمانية وأخلاقية للصفات التي لا يستغني عنها طلبة العلم<br>
                        •  منهج علمي يسهم في صناعة شخصية علمية متميزة في تخصصها بدبلومات شرعية ومهارية تخصصي<br>
                      </p>
                    </div>
                  </div>
                </div>
              </div>

          </div>
        </div>
      </section>


      <!-- diploms -->
      <section class="blog-area pt-70 pb-70" style="padding-bottom: 0;">
        <div class="container">
          <div class="section-title text-left">
            <span class="sub-title sec-color">@lang('core.Diploma_courses')</span>
            <h2 class=" prim-color"><i class="fas fa-book" style="opacity: 40%;padding: 0px 0px 0px 14px;"></i>دبلومات الأكاديمية</h2>
            <a href="{{route('diplomas.index')}}" class="default-btn prim-btn"><i class='bx bx-book-reader icon-arrow before'></i><span class="label">@lang('core.view_all')</span><i class="bx bx-book-reader icon-arrow after"></i></a>
          </div>

          <div class="blog-slides owl-carousel owl-theme">
              @isset($result)
                  @foreach($result  as $item)
                      <div class="single-blog-post mb-30">

                        <!-- ribbone -->
                        <!-- <div class="corner-ribbon bottom-left sticky orange">89 %</div> -->


                          <div class="post-image">
                              <a href="{{ route('courses.index',$item->alias) }}" class="d-block">
                                  <img src="{{ url($item->logo_path) }}" alt="{{ $item->name }}" title="{{ $item->name }}">
                              </a>

                               <div class="tag">
                                  <a href="{{ route('courses.index',$item->alias) }}"> {{ $item->name }}</a>
                              </div>
                          </div>

                          <div class="post-content">
                              <ul class="post-meta">
                                  <li class="post-author" style="padding-right: 15px;">
                                      <img src="{{ asset('assets/img/logo2.png') }}" class="d-inline-block rounded-circle mr-2" alt="@lang('core.app_name')">
                                      <a href="{{ url(app()->getLocale()) }}" class="d-inline-block sec-color">@lang('core.app_name')</a>
                                  </li>
                              </ul>
                              <!-- <h3><a href="{{ route('courses.index',$item->alias) }}" class="d-inline-block">  {!! $item->description !!} </a></h3> -->
                              <a href="{{ route('courses.index',$item->alias) }}" class="but-more">@lang('core.more')</a>
                          </div>
                      </div>
                  @endforeach
              @endisset
          </div>
        </div>
      </section>

      <!-- 02 -->
      <section class="how-it-works-area pt-100 pb-70" style="background-color: rgb(92 119 219);">
            <!-- <div style="position: absolute;  width: 100%;  height: 100%;  text-align: center;  font-size: 110px;color: white;opacity: 6%;font-weight: bold;">
                رحمة للعالمين
            </div> -->

            <div class="container">
                <div class="section-title">
                    <!-- <span class="sub-title card-colorfull-title-color-1">{{__('single-work-process.Find Courses')}}</span> -->
                    <h2 >{{__('single-work-process.How It Works?')}}</h2>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="single-work-process mb-30">
                            <div class="icon" style="color: white; border: none;background-color: #5cd3df;">
                                <i class='bx bx-mouse-alt'></i>
                            </div>
                            <h3 style="color: #8ff5ff;">اختيار الدبلوم</h3>
                            <p style="color: #8ff5ff;">الدخول الى الدبلومات اولًا ومن ثم اختر الدورة المراده</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="single-work-process mb-30">
                            <div class="icon" style="color: white; border: none;background-color: #ff73ae;">
                                <i class='bx bx-info-square'></i>
                            </div>
                            <h3 style="color: #ffc6e9;">عرض تفاصيل الدورة</h3>
                            <p style="color: #ffc6e9;">عند اختيار الدورة المراد دراستها من الدبلوم يتم عرض محتوى الدورة (الفيديو والصوت والمرجع)</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 offset-lg-0 offset-md-3">
                        <div class="single-work-process mb-30">
                            <div class="icon" style="color: white; border: none;background-color: #f9c837;">
                                <i class='bx bx-like'></i>
                            </div>
                            <h3 style="color: #fddb75;">التسجيل أو الاشتراك</h3>
                            <p style="color: #fddb75;">قم بالضغط على (التسجيل كطالب جديد، وأدخل البيانات المطلوبة</p>
                        </div>
                    </div>
                </div>
            </div>
      </section>


      <!-- teachers -->
      <section class="team-area ptb-100" style="background-color: #f7e6e2">
          <div style="position: absolute;  width: 100%;  height: 100%;  text-align: center;  font-size: 80px;color: white;opacity: 40%;font-weight: bold;">
            www.baldatayiba.com
          </div>

          <div class="container">
              <div class="section-title">
                  <span style="color: #cc9284">{{__('single-work-process.title_team')}}</span>
                  <h2 class="sec-color">{{__('single-work-process.Team of Instructors')}}</h2>
                  <p>{{__('single-work-process.Team_dis')}}</p>
              </div>

              <div class="row">
                  @foreach($teachers as $item)
                      <div class="col-lg-4 col-md-6 col-sm-6">
                          <div class="single-instructor-member mb-30">
                              <a href="{{route('teachers.show',str_replace(' ', '_', $item->name))}}">
                                  <div class="member-image">
                                      <img src="{{ url($item->logo_path) }}" alt="{{ $item->name}}">
                                  </div>
                              </a>
                              <div class="member-content">
                                  <h3><a href="{{route('teachers.show',str_replace(' ', '_', $item->name))}}">{{ $item->name}}</a></h3>
                                  <span class="prim-color">{{__('core.app_name')}}</span>
                                  <ul class="social">
                                      <li >
                                          @for ($i = 0; $i < 5; $i++)
                                              <i class="fa fa-star{{ $item-> rated == $i + .5 ? '-half' : ''}}{{$item-> rated <= $i ? '-o' : ''}}" aria-hidden="true"></i>
                                          @endfor
                                      </li>
                                      <li>{{$item-> rated}} ({{$item-> number_rated}})</li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                  @endforeach
                  <div class="col-lg-12 col-md-12 col-sm-12">
                      <div class="team-btn-box text-center">
                          <a href="{{route('teachers.index')}}" class="default-btn sec-btn"><i class='bx bx-show-alt icon-arrow before'></i><span class="label">{{__('single-work-process.see_more')}}</span><i class="bx bx-show-alt icon-arrow after"></i></a>
                      </div>
                  </div>
              </div>
          </div>

          <div id="particles-js-circle-bubble-3"></div>
      </section>


      <section class="become-instructor-partner-area">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-lg-6 col-md-6">
                      <div class="become-instructor-partner-content">
                          <h2 style="color: #f85e47">@lang('core.get_touch')</h2>
                          <p style="color: #f85e47">للتعرف على المزيد ..</p>
                          <!-- {{ route('front.page.contact_us') }} -->
                          <a href="https://t.me/joinchat/Cf7bgxQVxmo4MmFk" class="default-btn" style="background-color: #f85e47; border: none;">
                            <i class='bx bx-plus-circle icon-arrow before'></i><span class="label">أنقر هنا</span><i class="bx bx-plus-circle icon-arrow after"></i></a>
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6">
                      <div class="become-instructor-partner-image bg-image2 jarallax" data-jarallax='{"speed": 0.3}'style="background-image: url({{ asset('assets/new_front/banner_connect_us.jpg')}});">
                      </div>
                  </div>

                  <!-- register -->
                  @if(!Auth::check())
                    <div class="col-lg-6 col-md-6">
                        <div class="become-instructor-partner-image bg-image1 jarallax" data-jarallax='{"speed": 0.3}' style="background-image: url({{ asset('assets/new_front/banner_regester.jpg')}});">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="become-instructor-partner-content bg-color">
                            <h2>{{__('core.newsletter_submit')}} </h2>
                            <p>فضلا اشترك الآن وتمتع بدورات مجانية في كافة المجالات والتي يقدمها صفوة من كبار العلماء</p>
                            <a href="{{ route('register') }}" class="default-btn"><i class='bx bx-plus-circle icon-arrow before'></i><span class="label">{{__('core.newsletter_submit')}}</span><i class="bx bx-plus-circle icon-arrow after"></i></a>
                        </div>
                    </div>
                  @endif

              </div>
          </div>
      </section>



      <section class="become-instructor-partner-area">
          <div class="container-fluid">
              <div class="row">
                @if(Auth::check())
                  <!-- schdueles -->
                  <div class="col-lg-6 col-md-6">
                      <div class="become-instructor-partner-image bg-image2 jarallax" data-jarallax='{"speed": 0.3}'style="background-image: url({{ asset('assets/new_front/schedules.jpg')}});">
                      </div>
                  </div>
                  <div class="col-lg-6 col-md-6" style="background-color: #dcf0e8;">
                      <div class="become-instructor-partner-content">
                          <h2 style="color: #1e7852">جدول الدورات</h2>
                          <!-- <p style="color: #f85e47">تواصل معنا الان لا إعطاء رأيك ومقترحات او اي تعليق اخر -->
                          </p>
                          <a href="https://www.baldatayiba.com/ar/info/schedules" class="default-btn" style="background-color: #1e7852; border: none;"><i class='bx bx-plus-circle icon-arrow before'></i><span class="label">المزيد</span></a>
                      </div>
                  </div>
                @endif


                @if(!Auth::check())
                  <!-- cirts samples -->
                  <div class="col-12 d-flex justify-content-center" style="background-image: url({{ asset('assets/new_front/cirt_forms.jpg')}});background-position: center;">
                      <a href="https://www.baldatayiba.com/ar/info/certificate_forms" class="sec-btn" style="margin: 50px 0px;font-size: 30px;padding: 5px 20px;">نماذج الشهادات</a>
                  </div>
                @endif
              </div>
          </div>
      </section>


      <section class="premium-access-area ptb-100">
          <div class="container">
              <div class="premium-access-content">
                  <span class="sub-title prim-color">البروشور التعريفي </span>
                  <h2 class="prim-color">تعرف أكثر على أكاديمية  البلدة الطيبة </h2>
                  <p>تعرف أكثر على أكاديمية البلدة الطيبة  من خلال تحميل البروشور التعريفي الخاص بالاكاديمية</p>
                  <a href="{{url(\Storage::url('upload/bosher.pdf'))}}" class="default-btn but-more" download><i class='bx bx-download icon-arrow before'></i><span class="label">حمل الان</span><i class="bx bx-download icon-arrow after"></i></a>
              </div>
          </div>
          <div class="business-shape9"><img src="assets/new_front/img/business-coaching/business-shape7.png" alt="@lang('core.app_name')"></div>
          <div class="business-shape10"><img src="assets/new_front/img/business-coaching/business-shape8.png" alt="@lang('core.app_name')"></div>
      </section>




@endsection
