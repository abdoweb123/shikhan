@if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
<style>
  li.course-item-lp_lesson.course-lesson {
      font-size: x-large;text-align: right;background-color: #00375545;color: #126373;margin: 5px;padding:2px;border-radius: 5px;
  }
</style>
@else
<style>
  .single-blog-post .post-content .post-meta li {direction: ltr;}
</style>
@endif

<style>

i.fa.fa-check-circle{color:#17a703!important;font-size:x-large}.top-header img.rounded-circle.z-depth-0{height:40px}.logo-img{max-height:80px}.top-header li.nav-item.dropdown{left:0;direction:ltr;list-style:none}.top-header a#navbarDropdown{color:#fff}.default-btn .icon-arrow.before{top:2px;left:12px;position:absolute;-webkit-transform-origin:left center;transform-origin:left center}.default-btn .icon-arrow{top:4px;font-size:22px}@media only screen and (max-width:767px){.top-header-btn{display:block!important}.default-btn{padding:4px 13px 7px 45px;font-size:13px}.for-web{display:none}.top-header{text-align:center;padding-top:2px;padding-bottom:7px}}.default-btn{-webkit-transition:.5s;transition:.5s;display:inline-block;padding:5px 15px 5px 40px!important;position:relative}.page-title-area{position:relative;z-index:1;background-position:center center;background-size:cover;background-repeat:no-repeat;padding-top:165px;padding-bottom:20px}.clever-btn{font-size:14px;font-weight:600;color:#2266ae;background-color:#fff}.clever-btn.active,.clever-btn:focus,.clever-btn:hover{font-size:14px;font-weight:600;color:#fff;background-color:#2266ae}.owl-carousel .owl-stage-outer{height:500px!important}.main-banner-content{margin-top:-100px!important}.owl-carousel .owl-nav button.owl-next,.owl-carousel .owl-nav button.owl-prev,.owl-carousel button.owl-dot{border-radius:50%!important}.main-banner{height:500px!important;margin-top:130px;background-size:100% 100%}.footer-bottom-area{background-color:#f8e8e9}.footer-area{background-color:#f8e8e9}.footer-area a{color:#884d17}li.course-item-lp_lesson.course-lesson{font-size:x-large;text-align:left;background-color:#00375545;color:#126373;margin:5px;padding:2px;border-radius:5px}.faq-video .video-btn{background-color:#bc8d60}.faq-video .video-btn:hover{color:#7b4613}.faq-video .video-btn:hover::after,.faq-video .video-btn:hover::before{border-color:#7b4613}.faq-video .video-btn i{margin-left:6px}.become-instructor-partner-content.bg-color .default-btn:focus,.become-instructor-partner-content.bg-color .default-btn:hover{color:#fff;background-color:#7ebc43;border-color:#65ad3d}.become-instructor-partner-content.bg-color .default-btn{background-color:#fff;color:#86c344}.become-instructor-partner-content.bg-color .default-btn .icon-arrow{color:#6ab13c}@media only screen and (max-width:767px){.navbar-area{padding-top:5px;padding-bottom:5px}.mean-bar{padding-top:5px!important}.main-banner{height:320px!important;padding-top:0!important;padding-bottom:0!important;margin-top:179px!important;background-size:156% 100%!important}.page-title-area{margin-top:25px!important}a.link-owl{height:100%;width:100%}.page-title-area.page-title-style-three{padding-top:153px!important;padding-bottom:40px!important}}a.d-block{background:#f5deb300!important}.fa:hover{opacity:.7}i.fab.fa-tiktok{color:#fff;background:#000}.fa-whatsapp{color:#fff;background:#5cd335}i.bx.bx-envelope{background:#dd4b39;color:#fff}.single-footer-widget .social-link li a i{padding:5px 0;border-radius:25%}i.fa.fa-telegram{background:#55acee;color:#fff}i.fa-facebook-square{background:#3b5998;color:#fff}i.fa-twitter{background:#55acee;color:#fff}i.fa-envelope{background:#dd4b39;color:#fff}i.fa-linkedin{background:#007bb5;color:#fff}i.fa-youtube-play{background:#b00;color:#fff}i.fa-instagram{background:#ef5858;color:#fff}.single-footer-widget .social-link li a:focus i,.single-footer-widget .social-link li a:hover i{background-color:#5cc9df!important;color:#fff}.top-header-contact-info li i.bx.bx-envelope{background:#faebd700}
</style>

@yield('front-style')
 <!-- Start Header Area -->
<header class="header-area" style="position: relative !important;">

  <div class="top-header prim-back-color">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="for-web col-lg-3">
          <ul class="top-header-contact-info">
            <!--<li>-->
            <!--    <i class='bx bx-phone-call'></i>-->
            <!--    <a href="tel:502464674">+502 464 674</a>-->
            <!--</li>-->
            <li>
                <a class="prim-color" href="{{ url(app()->getLocale()) }}" style="font-size: 22px;">@lang('core.app_name')</a>
                <br>
                @if (app()->getlocale() == 'ha')
                <a class="prim-color-light"  href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=aiaacademyha@gmail.com" style="color: #a85309;font-size: 14px;font-weight: normal;" target="_blank">
                  <!-- <i class='bx bx-envelope'></i> -->
                  aiaacademyha@gmail.com
                </a>
                @endif
            </li>
            {{--
            <li>
                <i class='bx bx-envelope'></i>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=baldatayiba@gmail.com" target="_blank">baldatayiba@gmail.com</a>
            </li>
            --}}
          </ul>
        </div>

        <div class="row col-lg-9 p-0">
            <div class="single-footer-widget col-3  p-0 text-center">
                <ul class="social-link mt-2" style="margin-top: .9rem !important;">

                  @isset($social)
                      @foreach($social as $item_social)

                          <li><a href="{{$item_social->link}}" class="d-block" target="_blank">{!! $item_social->icon !!}</a></li>

                      @endforeach
                  @endisset
                </ul>
            </div>

            <div class="top-header-btn col-9 p-0 row" style="margin-right: 1px !important;">
              <li style="padding: 16px 5px;display: block;">
                <a href="{{ route('diplomas.index') }}" class="but-special">{{ __('trans.study_now') }}</a>
              </li>

              @if(Auth::guard('web')->check())
                  <li class="nav-item dropdown prim-back-color-dark" style="border-radius: 35px;text-align: center;">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="true">
                          @if(!empty(Auth::guard('web')->user()->avatar))
                              {{--<img src="{{ Auth::guard('web')->user()->avatar_path }}" class="rounded-circle z-depth-0" title="{{ Auth::guard('web')->user()->name }}" style="height: 40px;width: 40px;">--}}
                              <img src="data:image/jpeg;base64,{{  Auth::guard('web')->user()->AvatarPath64 }}" class="rounded-circle z-depth-0" title="{{ Auth::guard('web')->user()->name }}" style="height: 40px;width: 40px;">
                          @else
                              <i class="fa fa-user"></i>
                          @endif
                          <div  style="display: inline-block;max-width: 130px;overflow: hidden;direction: rtl; color: #11643f;font-size: 18px;vertical-align: middle;"><span>{{ Auth::guard('web')->user()->email }}</span></div>
                          @if (count($notificationsUnseen)) <span class="badge" style="background-color: red;font-size: 13px;">{{ count($notificationsUnseen) }} <i class="far fa-bell" style="animation: tada 2s linear infinite;font-size: 13px;"></i></span> @endif
                      </a>
                      <div class="dropdown-menu" style="text-align: right;direction: rtl;" aria-labelledby="navbarDropdown">
                          @foreach($notificationsUnseen as $notification)
                            <a class="dropdown-item" style="color: #165b3c;" href="{{ route('notifications_inner_index' , [ 'id' => $notification->id ] ) }}">
                                <i class="far fa-bell"></i> {{ $notification->title }}
                            </a>
                          @endforeach
                          <a class="dropdown-item" style="color: #002b18;font-weight: bold;background-color: #3ba274;padding: 9px 10px;" href="{{ route('notifications_inner_index') }}">
                              <i class="fas fa-bell"></i>{{ __('trans.all_notifications') }}
                          </a>
                          {{--
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                              <i class="fas fa-sign-out-alt"></i>
                              @lang('core.logout')
                          </a>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              @csrf
                          </form>
                          --}}
                      </div>
                  </li>
                  <a class="nav-item dropdown {{ Route::currentRouteName() == 'profile' ? 'active' : '' }} sec-color" style="padding : 17px 5px 0px 5px;" href="{{ route('profile') }}">
                      <i class="fas fa-id-card"></i>
                      @lang('meta.title.profile')
                  </a>
                  <a class="nav-item dropdown {{ Route::currentRouteName() == 'sites_certificates' ? 'active' : '' }} sec-color"  style="padding : 17px 5px 0px 5px;" href="{{ route('sites_certificates') }}">
                      <i class="fas fa-certificate"></i>@lang('meta.title.my_courses_cirts')
                  </a>
              @else
                  <div class="top-header-btn p-0" style="float: left; margin-top: 12px;margin-right: 12px;">
                      <a href="{{ route('login') }}" class="default-btn but-login"><i class='bx bx-log-in icon-arrow before' style="color: #8de7bf;"></i><span class="label"> {{ __('trans.already_registered')}} </span><i class="bx bx-log-in icon-arrow after"></i></a>
                  </div>

                  <div class="top-header-btn p-0" style="float: left; margin-top: 12px;margin-right: 12px;">
                      <a href="{{ route('register') }}" class="default-btn but-login"><i class="bx bx-log-in-circle icon-arrow before"  style="color: #8de7bf;"></i><span class="label"> {{ __('trans.new_account')}}</span><i class="bx bx-log-in-circle icon-arrow  after"></i></a>
                  </div>
                  <div class="top-header-btn p-0" style="float: left; margin-top: 12px;margin-right: 12px;position: absolute;
  right: 25px;">
{{--                    <a style="border-radius: 50px;  background-color: #fff !important;  color: #3ba274 !important;  font-weight: bold;  display: block;  padding: 5px 10px;position: absolute;" href="https://ha.aia-academy.com">Hausa</a>--}}
            </div>
              @endif
            </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Start Navbar Area -->
  <div class="navbar-area">

      <div class="raque-responsive-nav">
          <div class="container">
              <div class="raque-responsive-menu">
                  <div class="logo">
                      <a href="{{ url(app()->getLocale()) }}">
                          <img src="{{asset('assets/img/logo-imameen.png')}}" class="logo-img" style="max-height: 50px;" alt="logo">
                      </a>
                  </div>
              </div>
          </div>
      </div>

      <div class="raque-nav">
          <div class="container-fluid">
              <nav class="navbar navbar-expand-md navbar-light white-area-shadow" style="border-radius: 10px;">

                  <a class="navbar-brand" href="{{ url(app()->getLocale()) }}">
                      <img src="{{asset('assets/img/logo-imameen.png')}}" class="logo-img" alt="logo">
                  </a>


                  <div class="collapse navbar-collapse mean-menu">
                      <ul class="navbar-nav">

                          @if (isset($menu_header) )
            								@foreach ($menu_header as $item)
                              @if ($item->route ?? null)
              									<li class="nav-item">
              										@if (! isset($item->children) )
                                   <a href=" {{ strpos($item->route, 'info') ? route($item->route , json_decode($item->params,true)) :  route($item->route)}}" class="nav-link">{{--  $item->image --}} {{ $item->title }}</a>
              										@else
              											<a href="#" class="nav-link">{{ __('core.Others') }} <i class='bx bx-chevron-down'></i></a>
              											<ul class="dropdown-menu">
              												@foreach ($item->children as $subItem)
                                          <li class="nav-item"><a href="{{ route($subItem->route , json_decode($subItem->params,true) ) }}" class="nav-link">{{ $subItem->title }}</a></li>
              												@endforeach
              											</ul>
              										@endif
              									</li>
                              @endif
            								@endforeach
          						    @endif

                          {{--
                          <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">{{ __('words.login') }}</a>
                          </li>
                          --}}


                          <!-- payment -->
                          @if(Auth::guard('web')->check())
                            @if(Auth::id() == 5671)
                              <li class="nav-item">
                                <a href="{{ route('payment.pay' , ['lang' => app()->getlocale() ] ) }}" class="nav-link">
                                    <span style="background-color: green;padding: 5px 10px;color: white;border-radius: 3px;box-shadow: 1px 3px 10px #0a7b0a;">ادعمنا</span>
                                </a>
                              </li>
                            @endif
                          @endif

                      </ul>


                <!-- google search -->
                <!-- <div class="gcse-search"></div> -->
                {{--<gcse:searchbox-only id="g_search" resultsUrl="{{url('')}}/{{$langAll->alies}}/g_search/search" enableAutoComplete="true"></gcse:searchbox-only>--}}


                        {{--
                      <div class="others-option">
                          <div class="dropdown language-switcher d-inline-block">

                               @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                @if ($properties['name'] != 'Hausa' && $properties['name'] != 'Arabic')
                                    @php
                                        $localizedUrl = !empty(Route::current()->parameters())
                                        ? LaravelLocalization::getURLFromRouteNameTranslated($localeCode,'slug.'.Route::currentRouteName(),@event('routes.translation', [$localeCode, Route::current()->parameters()])[0])
                                        : LaravelLocalization::getLocalizedURL($localeCode)
                                     @endphp
                                     @if($localeCode === App::getLocale())
                                        <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <!-- <img src="{{asset('assets/img/'.$localeCode.'-flag.jpg')}}" class="shadow" alt="image"> -->
                                            <span>{{ $properties['native'] }} <i class='bx bx-chevron-down'></i></span>
                                        </button>
                                      @else

                                      @endif
                                @endif
                               @endforeach
                              <div class="dropdown-menu">
                                   @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    @if ($properties['name'] != 'Hausa' && $properties['name'] != 'Arabic')
                                          @php
                                              $localizedUrl = !empty(Route::current()->parameters())
                                              ? LaravelLocalization::getURLFromRouteNameTranslated($localeCode,'slug.'.Route::currentRouteName(),@event('routes.translation', [$localeCode, Route::current()->parameters()])[0])
                                              : LaravelLocalization::getLocalizedURL($localeCode)
                                          @endphp
                                          @if($localeCode === App::getLocale())

                                          @else
                                              <a href="{{ $localizedUrl }}" hreflang="{{ $localeCode }}" rel="alternate" class="dropdown-item d-flex align-items-center">
                                                  <!-- <img src="{{asset('assets/img/'.$localeCode.'-flag.jpg')}}" class="shadow-sm" alt="flag"> -->
                                                  <span>{{ $properties['native'] }}</span>
                                              </a>
                                          @endif
                                    @endif
                                  @endforeach
                              </div>
                          </div>
                      </div>
                      --}}


                  </div>
              </nav>
          </div>
      </div>
  </div>
  <!-- End Navbar Area -->

  <!-- Start Sticky Navbar Area -->
  <div class="navbar-area header-sticky">
      <div class="raque-nav">
          <div class="container-fluid">
              <nav class="navbar navbar-expand-md navbar-light">
                  <a class="navbar-brand" href="{{ url(app()->getLocale()) }}">
                      <img src="{{asset('assets/img/logo-imameen.png')}}" class="logo-img" alt="logo">
                  </a>

                  <div class="collapse navbar-collapse">
                      <ul class="navbar-nav">
                          @if (isset($menu_header) )
              							@foreach ($menu_header as $item)
                              @if ($item->route ?? null)
                								<li class="nav-item">
                									@if (! isset($item->children) )
                                    <a href=" {{ strpos($item->route, 'info') ? route($item->route , json_decode($item->params,true)) :  route($item->route )}}" class="nav-link">{{--  $item->image --}} {{ $item->title }}</a>
                									@else
                										<a href="#" class="nav-link">{{ __('core.Others') }} <i class='bx bx-chevron-down'></i></a>
                										<ul class="dropdown-menu">
                											@foreach ($item->children as $subItem)
                                          <li class="nav-item"><a href="{{ route($subItem->route , json_decode($subItem->params,true) ) }}" class="nav-link">{{ $subItem->title }}</a></li>
                											@endforeach
                										</ul>
                									@endif
                								</li>
                              @endif
              							@endforeach
              					  @endif
                      </ul>

                      @if (auth()->check() && ourAuth())
                      <div class="others-option">
                          <div class="dropdown language-switcher d-inline-block">
                               @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    @php
                                        $localizedUrl = !empty(Route::current()->parameters())
                                        ? LaravelLocalization::getURLFromRouteNameTranslated($localeCode,'slug.'.Route::currentRouteName(),@event('routes.translation', [$localeCode, Route::current()->parameters()])[0])
                                        : LaravelLocalization::getLocalizedURL($localeCode)
                                    @endphp
                                    @if($localeCode === App::getLocale())
                                      <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <!-- <img src="{{asset('assets/img/'.$localeCode.'-flag.jpg')}}" class="shadow" alt="image"> -->
                                          <span>{{ $properties['native'] }} <i class='bx bx-chevron-down'></i></span>
                                      </button>
                                    @else

                                    @endif
                                @endforeach
                              <div class="dropdown-menu">
                                   @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                        @php
                                            $localizedUrl = !empty(Route::current()->parameters())
                                            ? LaravelLocalization::getURLFromRouteNameTranslated($localeCode,'slug.'.Route::currentRouteName(),@event('routes.translation', [$localeCode, Route::current()->parameters()])[0])
                                            : LaravelLocalization::getLocalizedURL($localeCode)
                                        @endphp
                                      @if($localeCode === App::getLocale())


                                      @else
                                        <a href="{{ $localizedUrl }}" hreflang="{{ $localeCode }}" rel="alternate" class="dropdown-item d-flex align-items-center">
                                            <!-- <img src="{{asset('assets/img/'.$localeCode.'-flag.jpg')}}" class="shadow-sm" alt="flag"> -->
                                            <span>{{ $properties['native'] }}</span>
                                        </a>
                                      @endif
                                  @endforeach
                              </div>
                          </div>
                      </div>
                      @endif

                  </div>
              </nav>
          </div>
      </div>
  </div>
  <!-- End Sticky Navbar Area -->

</header>
<!-- End Header Area -->
