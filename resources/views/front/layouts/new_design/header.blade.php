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

  <div class="top-header" style="background-color: #b57f4b;">
    <div class="container">
      <div class="row align-items-center">
        <div class="for-web col-lg-3">
          <ul class="top-header-contact-info">
            <!--<li>-->
            <!--    <i class='bx bx-phone-call'></i>-->
            <!--    <a href="tel:502464674">+502 464 674</a>-->
            <!--</li>-->
            <li>
                <a href="{{ url(app()->getLocale()) }}">@lang('core.app_name')</a>
                <br>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=baldatayiba@gmail.com" style="font-size: 12px;font-weight: normal;color: #fde6a0;" target="_blank">
                  <!-- <i class='bx bx-envelope'></i>  -->
                  baldatayiba@gmail.com</a>
            </li>
            {{--
            <li>
                <i class='bx bx-envelope'></i>
                <a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=baldatayiba@gmail.com" target="_blank">baldatayiba@gmail.com</a>
            </li>
            --}}
          </ul>
        </div>

        <div class="row col-lg-9 p-0  row">
            <div class="single-footer-widget col-5  p-0 text-center">
                <ul class="social-link mt-2">
                  @isset($social)
                      @foreach($social as $item_social)
                        @if( str_contains($item_social->link, 'facebook') || str_contains($item_social->link, 'wa.me') || str_contains($item_social->link, 't.me') || str_contains($item_social->link, 'youtube') )
                          <li><a href="{{$item_social->link}}" class="d-block" target="_blank">{!! $item_social->icon !!}</a></li>
                        @endif
                      @endforeach
                  @endisset
                </ul>
            </div>

            <div class="top-header-btn col-7  p-0 row" style="margin-right: 1px !important;">
              @if(Auth::guard('web')->check())
                  <li class="nav-item dropdown" style="background-color: #78b920;  border-radius: 12px;text-align: center;">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="true">
                          @if(!empty(Auth::guard('web')->user()->avatar))
                              <img src="{{ asset(Auth::guard('web')->user()->avatar_path) }}" class="rounded-circle z-depth-0" title="{{ Auth::guard('web')->user()->name }}" height="30">
                          @else
                              <i class="fa fa-user"></i>
                          @endif
                          <div style="display: inline-block;max-width: 130px;overflow: hidden;direction: rtl;">{{ Auth::guard('web')->user()->name }}</div>
                          @if (count($notificationsUnseen)) <span class="badge" style="background-color: red;">{{ count($notificationsUnseen) }} <i class="far fa-bell"></i></span> @endif
                      </a>
                      <div class="dropdown-menu" style="text-align: right;direction: rtl;" aria-labelledby="navbarDropdown">
                          @foreach($notificationsUnseen as $notification)
                            <a class="dropdown-item" style="color: #b57f4b;" href="{{ route('notifications_inner_index' , [ 'id' => $notification->id ] ) }}">
                                <i class="far fa-bell"></i> {{ $notification->title }}
                            </a>
                          @endforeach
                          <a class="dropdown-item" style="color: #814e1d;" href="{{ route('notifications_inner_index') }}">
                              <i class="fas fa-bell"></i> كل الإشعارات
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
                  <a class="nav-item dropdown {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" style="color: #fde6a0;padding : 8px 5px 0px 5px;" href="{{ route('profile') }}">
                      <i class="fas fa-id-card" style="color: a3c2ea;"></i>
                      @lang('meta.title.profile')
                  </a>
                  <a class="nav-item dropdown {{ Route::currentRouteName() == 'my_courses_cirts' ? 'active' : '' }}"  style="color: #fde6a0;padding : 8px 5px 0px 5px;" href="{{ route('my_courses_cirts') }}">
                      <i class="fas fa-certificate" style="color: a3c2ea;"></i>@lang('meta.title.my_courses_cirts')
                  </a>
              @else
                  <div class="top-header-btn col-6  p-0" style="margin-left: -14px;float: left;">
                      <a href="{{ route('login') }}" class="default-btn"><i class='bx bx-log-in icon-arrow before'></i><span class="label">{{ __('words.login') }} للمسجلين سابقا </span><i class="bx bx-log-in icon-arrow after"></i></a>                  </div>

                  <div class="top-header-btn col-6  p-0">
                      <a href="{{ route('register') }}" class="default-btn"><i class="bx bx-log-in-circle icon-arrow before"></i><span class="label">{{ __('core.register') }}</span><i class="bx bx-log-in-circle icon-arrow  after"></i></a>
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
                          <!-- aaaaaaaaaaaa -->
                          <img src="{{asset('assets/img/logo2.png')}}" class="logo-img" style="max-height: 50px;" alt="logo">
                      </a>
                  </div>
              </div>
          </div>
      </div>

      <div class="raque-nav">
          <div class="container">
              <nav class="navbar navbar-expand-md navbar-light">

                  <a class="navbar-brand" href="{{ url(app()->getLocale()) }}">
                      <img src="{{asset('assets/img/logo2.png')}}" class="logo-img" alt="logo">
                  </a>

                  <div class="collapse navbar-collapse mean-menu">
                      <ul class="navbar-nav">
                          @if (isset($menu_header) )
            								@foreach ($menu_header as $item)
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
                                          <img src="{{asset('assets/new_front/img/'.$localeCode.'-flag.jpg')}}" class="shadow" alt="image">
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
                                              <img src="{{asset('assets/new_front/img/'.$localeCode.'-flag.jpg')}}" class="shadow-sm" alt="flag">
                                              <span>{{ $properties['native'] }}</span>
                                          </a>
                                      @endif
                                  @endforeach
                              </div>
                          </div>
                          <!--<div class="search-box d-inline-block">-->
                          <!--    <i class='bx bx-search'></i>-->
                          <!--</div>-->
                      </div>
                  </div>
              </nav>
          </div>
      </div>
  </div>
  <!-- End Navbar Area -->

  <!-- Start Sticky Navbar Area -->
  <div class="navbar-area header-sticky">
      <div class="raque-nav">
          <div class="container">
              <nav class="navbar navbar-expand-md navbar-light">
                  <a class="navbar-brand" href="{{ url(app()->getLocale()) }}">
                      <img src="{{asset('assets/img/logo2.png')}}" class="logo-img" alt="logo">
                  </a>

                  <div class="collapse navbar-collapse">
                      <ul class="navbar-nav">

                          @if (isset($menu_header) )
							@foreach ($menu_header as $item)
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
							@endforeach
					    @endif
                      </ul>

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
                                          <img src="{{asset('assets/new_front/img/'.$localeCode.'-flag.jpg')}}" class="shadow" alt="image">
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
                                              <img src="{{asset('assets/new_front/img/'.$localeCode.'-flag.jpg')}}" class="shadow-sm" alt="flag">
                                              <span>{{ $properties['native'] }}</span>
                                          </a>

                                      @endif
                                  @endforeach
                              </div>
                          </div>


                          <!--<div class="search-box d-inline-block">-->
                          <!--    <i class='bx bx-search'></i>-->
                          <!--</div>-->
                      </div>
                  </div>
              </nav>
          </div>
      </div>
  </div>
  <!-- End Sticky Navbar Area -->

</header>
<!-- End Header Area -->
