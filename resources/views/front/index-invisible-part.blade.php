
@include('front.layouts.new_design.js.jquery-min')

@include('front.layouts.new_design.js.owl-carousel-min')

@include('front.layouts.new_design.js.mixitup')

@include('front.layouts.new_design.js.meanmenu-min')

@include('front.layouts.new_design.js.main-min')


<style>
.owl-carousel .owl-stage-outer {
  height: 450px !important;
}
.owl-item{
  box-shadow: rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
}
</style>

<!-- diploms -->
<section class="blog-area pt-70 pb-70" style="padding-bottom: 0;">
  <div class="container">

    <div class="section-title text-left">
      <span class="sub-title sec-color">@lang('core.Diploma_courses')</span>
      <h2 class=" prim-color"><i class="fas fa-book" style="opacity: 40%;padding: 0px 0px 0px 14px;"></i>{{ __('core.academy_diploms')}}</h2>
      <a href="{{route('diplomas.index')}}" class="default-btn prim-btn"><i class='bx bx-book-reader icon-arrow before'></i><span class="label">@lang('core.view_all')</span><i class="bx bx-book-reader icon-arrow after"></i></a>
    </div>

    <div class="blog-slides owl-carousel owl-theme">
      @isset($result)
        @foreach($result  as $item)
          <div class="single-blog-post mb-30">
              <div class="post-image">
                  <a href="{{ route('courses.index', ['site' => $item->slug]) }}" class="d-block">
                      <img src="{{ url($item->ImageDetailsPath) }}" alt="{{ $item->name }}" title="{{ $item->name }}">
                  </a>

                   <div class="tag">
                      <a href="{{ route('courses.index', ['site' => $item->slug]) }}"> {{ $item->name }}</a>
                  </div>
              </div>

              <div class="post-content">
                  <ul class="post-meta">
                      <li class="post-author" style="padding-right: 15px;">
                          <img src="{{ asset('assets/img/logo2.png') }}" class="d-inline-block rounded-circle mr-2" alt="@lang('core.app_name')">
                          <a href="{{ url(app()->getLocale()) }}" class="d-inline-block sec-color">@lang('core.app_name')</a>
                      </li>
                  </ul>
                  <a href="{{ route('courses.index', ['site' => $item->slug]) }}" class="but-more">@lang('trans.more')</a>
              </div>
          </div>
        @endforeach
      @endisset
    </div>

  </div>
</section>






<section class="blog-area pt-70 pb-70" style="padding-bottom: 0;background-color: #e0f7db;">
  <div class="container">

    <div class="section-title text-center">

      <h2 class="sec-color">{{__('trans.title_team')}}</h2>
      <p>{{__('single-work-process.Team_dis')}}</p>
    </div>

    <div class="blog-slides owl-carousel owl-theme">
        @php $teachers = \App\Teacher::limit(6)->whereTranslation('locale', app()->getlocale())->inRandomOrder()->get(); @endphp

        @foreach($teachers as $item)
          <div class="single-blog-post mb-30">
              <div class="post-image">
                  <a href="{{ route('teachers.show', ['name' => $item->alias]) }}" class="d-block">
                      <img src="{{ url($item->logo_path) }}" style="max-height: 300px;min-height: 300px;" alt="{{ $item->title }}" title="{{ $item->title }}">
                  </a>
                  {{--
                   <!-- <div class="tag">
                      <a href="{{ route('courses.index', ['site' => $item->slug]) }}"> {{ $item->name }}</a>
                  </div> -->
                  --}}
              </div>

              <div class="post-content">
                  <ul class="post-meta">
                      <li class="post-author" style="display: flex;padding: 0px 15px;max-height: 35px;overflow: hidden;">
                          <img src="{{ asset('assets/img/logo2.png') }}" class="d-inline-block rounded-circle mr-2" alt="@lang('core.app_name')">
                          <a href="{{route('teachers.show', ['name' => $item->alias])}}" class="d-inline-block sec-color"
                            style="font-size: 21px;font-weight: bold;">{{ $item->title}}</a>
                      </li>
                  </ul>
              </div>
          </div>
        @endforeach
    </div>

    <div style="width: 100%; text-align: center;padding-bottom: 25px;">
      <a href="{{ route('teachers.index') }}" class="but-more">@lang('trans.more')</a>
    </div>

  </div>
</section>







<section class="blog-area pt-70 pb-70" style="padding-bottom: 0;">
  <div class="container">

    {{--
    <div class="section-title text-center">
      <span style="color: #cc9284">{{__('single-work-process.title_team')}}</span>
      <h2 class="sec-color">{{__('trans.title_team')}}</h2>
      <p>{{__('single-work-process.Team_dis')}}</p>
    </div>
    --}}

    {{--
    <div class="blog-slides owl-carousel owl-theme">


        @foreach($partners as $partner)
          <div class="single-blog-post mb-30">
              <div class="post-image">
                  <a href="" class="d-block">
                      <img src="{{ $partner->LogoPath }}" style="max-height: 300px;min-height: 300px;" alt="{{ $partner->title }}" title="{{ $partner->title }}">
                  </a>
              </div>

              <div class="post-content">
                  <ul class="post-meta">
                      <li class="post-author" style="display: flex;padding: 0px 15px;max-height: 35px;overflow: hidden;">
                          <img src="{{ asset('assets/img/logo2.png') }}" class="d-inline-block rounded-circle mr-2" alt="@lang('core.app_name')">

                          <a href="{{route('teachers.show', ['name' => $item->alias])}}" class="d-inline-block sec-color"
                            style="font-size: 21px;font-weight: bold;">{{ $partner->title }}</a>

                          <span style="font-size: 21px;font-weight: bold;"> {{ $partner->title }}</span>
                      </li>
                  </ul>
              </div>
          </div>
        @endforeach
    </div>
    --}}

    <div style="width: 100%; text-align: center;padding-bottom: 25px;">
      <a href="{{ route('partners.index') }}" class="but-more">@lang('trans.more')</a>
    </div>

  </div>
</section>





<!-- 02 -->
<section class="how-it-works-area pt-100 pb-70" style="background-color: rgb(16, 134, 95);">
      <!-- <div style="position: absolute;  width: 100%;  height: 100%;  text-align: center;  font-size: 110px;color: white;opacity: 6%;font-weight: bold;">
          رحمة للعالمين
      </div> -->

      <div class="container">
          <div class="section-title">
              <!-- <span class="sub-title card-colorfull-title-color-1">{{__('single-work-process.Find Courses')}}</span> -->
              <h2 >{{__('trans.how_to_start')}}</h2>
          </div>

          <div class="row">
              <div class="col-lg-4 col-md-6">
                  <div class="single-work-process mb-30">
                      <div class="icon" style="color: white; border: none;background-color: #5cd3df;">
                          <i class='bx bx-mouse-alt'></i>
                      </div>
                      <h3 >{{ __('trans.select_diploma') }}</h3>
                      <p>{{ __('trans.select_diploma_brief') }}</p>
                  </div>
              </div>

              <div class="col-lg-4 col-md-6">
                  <div class="single-work-process mb-30">
                      <div class="icon" style="color: white; border: none;background-color: #ff73ae;">
                          <i class='bx bx-info-square'></i>
                      </div>
                      <h3>{{ __('trans.show_course_details') }}</h3>
                      <p>{{ __('trans.show_course_details_brief') }}</p>
                  </div>
              </div>

              <div class="col-lg-4 col-md-6 offset-lg-0 offset-md-3">
                  <div class="single-work-process mb-30">
                      <div class="icon" style="color: white; border: none;background-color: #f9c837;">
                          <i class='bx bx-like'></i>
                      </div>
                      <h3>{{ __('trans.register_subscripe') }}</h3>
                      <p>{{ __('trans.register_subscripe_brief') }}</p>
                  </div>
              </div>
          </div>
      </div>
</section>






<section class="become-instructor-partner-area" style="padding: 60px;">
  <div class="container-fluid">
    <div class="row">
      @foreach($partners as $partner)
        <div class="col-md-3" style="font-size: 24px; padding: 10px;text-align: center;">
            <img src="{{ $partner->LogoPath }}" style="max-width: 200px;border-radius: 15px;border: 1px solid #bbb;"><br>
            {{ $partner->title }}
        </div>
      @endforeach
      <div style="text-align: center;width: 100%;">
        <a href="{{ route('partners.index') }}" class="default-btn sec-btn">{{ __('trans.more') }}</a>
      </div>
    </div>
  </div>
</section>



{{--
<section class="become-instructor-partner-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="become-instructor-partner-content">
                    <h2 style="color: #f85e47">@lang('core.get_touch')</h2>
                    <p style="color: #f85e47">{{ __('trans.more') }} ..</p>
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
                    <h2 style="color: #1e7852">{{ __('trans.course_schedule') }}</h2>
                    <!-- <p style="color: #f85e47">تواصل معنا الان لا إعطاء رأيك ومقترحات او اي تعليق اخر -->
                    </p>
                    <a href="https://www.baldatayiba.com/ar/info/schedules" class="default-btn" style="background-color: #1e7852; border: none;"><i class='bx bx-plus-circle icon-arrow before'></i><span class="label">{{ __('trans.more') }}</span></a>
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
            <span class="sub-title prim-color">{{ __('trans.broshure') }}</span>
            <h2 class="prim-color">{{ __('trans.recognize_us') }}</h2>
            <p>{{ __('trans.recognize_us_brief') }}</p>
            <a href="{{url(\Storage::url('upload/bosher.pdf'))}}" class="default-btn but-more" download><i class='bx bx-download icon-arrow before'></i><span class="label">{{ __('trans.download_now') }}</span><i class="bx bx-download icon-arrow after"></i></a>
        </div>
    </div>
    <div class="business-shape9"><img src="assets/new_front/img/business-coaching/business-shape7.png" alt="@lang('core.app_name')"></div>
    <div class="business-shape10"><img src="assets/new_front/img/business-coaching/business-shape8.png" alt="@lang('core.app_name')"></div>
</section>
--}}
