

        <!-- Start Header Area -->
        <header class="header-area p-relative pt-0 mt-0 mb-0">
            <!-- header top -->

            <div class="top-header top-header-style-four">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-8">
                            <ul class="top-header-contact-info">
                                <li>
                                    <h2 class="logo-title" style="color: #fff;">{{ $settings['app_title'] }}</h2>
			                        <h6 class="logo-title" style="color: #fff;">{{ $settings['app_slogan'] }}</h6>
                                    <!--Call: -->
                                    <!--<a href="tel:502464674">+502 464 674</a>-->
                                </li>
                            </ul>

                            <div class="top-header-social" style="padding: 21px 21px 0px 21px;">

                                <span>{{ __('user.FollowUs') }}:</span>
                                <a href="https://www.facebook.com/ArabicEasily-109348914542006"  target="_blank"><i class='bx bxl-facebook'></i></a>
                                <a href="https://twitter.com/EasilyArabic" target="_blank"><i class='bx bxl-twitter'></i></a>
                                <a href="https://www.instagram.com/arabiceasily/"target="_blank"><i class='bx bxl-instagram'></i></a>
                                <a href="https://www.youtube.com/channel/UCljuZQ-0MYB85UdvCYebfKw"  target="_blank"><i class='bx bxl-youtube'></i></a>

                            </div>
                        </div>


                            	@guest
                            	 <div class="col-lg-4 col-md-4">
                                	<ul class="top-header-login-register">
                                        <li><a href="{{ route('front.login') }}"><i class='bx bx-log-in'></i> {{ __('auth.login') }}</a></li>
                                        <li><a href="{{ route('front.register') }}"><i class="fa fa-user-plus" aria-hidden="true"></i> {{ __('auth.register') }}</a></li>
                                    </ul>
							    @else
							     <div class="col-lg-4 col-4 top-m-user">
									<x-front.menu-user/>
 							@endguest

                        </div>
                    </div>
                </div>
            </div>
            <!-- End header top -->

            <div class="navbar-area navbar-style-three">
                 <div class="raque-responsive-nav">
                    <div class="container">
                        <div class="raque-responsive-menu">
                            <div class="logo">
                                 <x-logos.logo/>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="raque-nav">
                    <div class="container">
                        <nav class="navbar navbar-expand-md navbar-light">
                            <a class="navbar-brand" href="{{url('/')}}">
                                <x-logos.logo/>
                                <!--<img src="assets/img/black-logo.png" alt="logo">-->
                            </a>

                            <div class="collapse navbar-collapse mean-menu">
                                <ul class="navbar-nav">
                                        @if (isset($menu_header) )
											@foreach ($menu_header as $item)
    											<li class="nav-item">
    												@if (! isset($item->children) )
    													<a href="{{ route($item->route) }}" class="nav-link">{!!  $item->image !!} {{ $item->title }}</a>
    												@else
    													<a href="#" class="nav-link">{{ __('index.Others') }} <i class='bx bx-chevron-down'></i></a>
    													<ul class="dropdown-menu">
    														@foreach ($item->children as $subItem)
			                                                    <li class="nav-item"><a href="{{ route($subItem->route , json_decode($subItem->params,true) ) }}" class="nav-link">{{ $subItem->title }}</a></li>
    														@endforeach
    													</ul>
    												@endif
    											</li>
    										@endforeach
									    @endif
    									    @guest
            									<li class="nav-item"><a href="{{ route('front.login') }}" class="nav-link to-smile-show"><i class='bx bx-log-in'></i> {{ __('auth.login') }}</a></li>
                                                <li class="nav-item"><a href="{{ route('front.register') }}" class="nav-link to-smile-show"><i class="fa fa-user-plus" aria-hidden="true"></i> {{ __('auth.register') }}</a></li>

            							    @else
            							    <li class="nav-item  to-smile-show">
            							     <a href="#" class="nav-link">{{ Auth::user()->user_name }} @if(Auth::user()->image)<img src="{{ Auth::user()->imagePath() }}" style="max-width: 40px;max-height: 40px;border-radius: 50%;" alt="" class="profile-img-img"> @else<i class="fa fa-user" style="font-size: 27px;"></i> <i class='bx bx-chevron-down'></i> @endif </a>
													<ul class="dropdown-menu">
    													@if ( app('userType') == 'student' )
                                                          <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.students.profile' , [ 'id' => Auth::user()->userable->id ] ) }}">{{ __('words.profile') }}</a></li>
                                                          <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.students.study' , [ 'id' => Auth::user()->userable->id ] ) }}" >{{ __('words.study') }}</a></li>
                                                          @if (! app('member') )
                                                            <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.members.show_code_form') }}" >{{ __('words.have_code') }}</a></li>
                                                          @endif
                                                        @endif

                                                        @if ( app('userType') == 'teachers' )
                                                          <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.teachers.profile' , [ 'id' => Auth::user()->userable->id ] ) }}" >{{ __('words.profile') }}</a></li>
                                                          <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.teachers.study' , [ 'id' => Auth::user()->userable->id ] ) }}" >{{ __('words.study') }}</a></li>
                                                        @endif

                                                        <li class="nav-item">
                                                          <a class="nav-link to-smile-show"  href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" >{{ __('auth.logout') }}</a>
                                                          <form id="logout-form" action="{{ route('front.logout') }}" method="POST" style="display: none;">@csrf</form>
                                                        </li>
													</ul>
            									</li>
             							@endguest
             							<div class="nav-item to-smile-show">
                                            <x-front.language-bar :translations="$translations"/>



                                    <div class="search-box d-inline-block">
                                        {{-- <i class='bx bx-search'></i> --}}
                                    </div>
                                </div>
                                </ul>
                                <div class="nav-item for_web " style="margin-top: -10px;">
                                            <x-front.language-bar :translations="$translations"/>



                                    <div class="search-box d-inline-block">
                                        {{-- <i class='bx bx-search'></i> --}}
                                    </div>
                                </div>


                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- End Navbar Area -->

            <!-- Start Sticky Navbar Area -->
            <div class="navbar-area navbar-style-three header-sticky">
                <div class="raque-nav">
                    <div class="container">
                        <nav class="navbar navbar-expand-md navbar-light">

                            <a class="navbar-brand" href="{{ route('front.index') }}">
                                <x-logos.logo/>
                                <!--<img src="assets/img/black-logo.png" alt="logo">-->
                            </a>

                             <div class="collapse navbar-collapse mean-menu">
                                <ul class="navbar-nav">
                                        @if (isset($menu_header) )
											@foreach ($menu_header as $item)
    											<li class="nav-item">
    												@if (! isset($item->children) )
    													<a href="{{ route($item->route) }}" class="nav-link">{!!  $item->image !!} {{ $item->title }}</a>
    												@else
    													<a href="#" class="nav-link">{{ __('index.Others') }}<i class='bx bx-chevron-down'></i></a>
    													<ul class="dropdown-menu">
    														@foreach ($item->children as $subItem)
			                                                    <li class="nav-item"><a href="{{ route($subItem->route , json_decode($subItem->params,true) ) }}" class="nav-link">{{ $subItem->title }}</a></li>
    														@endforeach
    													</ul>
    												@endif
    											</li>
    										@endforeach
    									@endif
    									@guest
    									<li class="nav-item"><a href="{{ route('front.login') }}" class="nav-link to-smile-show"><i class='bx bx-log-in'></i> Login</a></li>
                                        <li class="nav-item"><a href="{{ route('front.register') }}" class="nav-link to-smile-show"><i class='bx bx-log-in-circle '></i> Register</a></li>

            							    @else
            							    <li class="nav-item  to-smile-show">
            							     <a href="#" class="nav-link">{{ Auth::user()->user_name }} @if(Auth::user()->image)<img src="{{ Auth::user()->imagePath() }}" style="max-width: 40px;max-height: 40px;border-radius: 50%;" alt="" class="profile-img-img"> @else<i class="fa fa-user" style="font-size: 27px;"></i> <i class='bx bx-chevron-down'></i> @endif </a>
													<ul class="dropdown-menu">
    													@if ( app('userType') == 'student' )
                                                          <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.students.profile' , [ 'id' => Auth::user()->userable->id ] ) }}">{{ __('words.profile') }}</a></li>
                                                          <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.students.study' , [ 'id' => Auth::user()->userable->id ] ) }}" >{{ __('words.study') }}</a></li>
                                                          @if (! app('member') )
                                                            <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.members.show_code_form') }}" >{{ __('words.have_code') }}</a></li>
                                                          @endif
                                                        @endif

                                                        @if ( app('userType') == 'teachers' )
                                                          <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.teachers.profile' , [ 'id' => Auth::user()->userable->id ] ) }}" >{{ __('words.profile') }}</a></li>
                                                          <li class="nav-item"><a class="nav-link to-smile-show" href="{{ route('front.teachers.study' , [ 'id' => Auth::user()->userable->id ] ) }}" >{{ __('words.study') }}</a></li>
                                                        @endif

                                                        <li class="nav-item">
                                                          <a class="nav-link to-smile-show"  href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" >{{ __('auth.logout') }}</a>
                                                          <form id="logout-form" action="{{ route('front.logout') }}" method="POST" style="display: none;">@csrf</form>
                                                        </li>
													</ul>
            									</li>
             							@endguest
             							<div class="nav-item to-smile-show">
                                            <x-front.language-bar :translations="$translations"/>



                                    <div class="search-box d-inline-block">
                                        {{-- <i class='bx bx-search'></i> --}}
                                    </div>
                                </div>
                                </ul>
                                <div class="nav-item for_web " style="margin-top: -10px;">
                                            <x-front.language-bar :translations="$translations"/>



                                    <div class="search-box d-inline-block">
                                        {{-- <i class='bx bx-search'></i> --}}
                                    </div>
                                </div>


                            </div>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- End Sticky Navbar Area -->

        </header>
        <!-- End Header Area -->

        <!-- Search Box Layout -->
        <div class="search-overlay">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="search-overlay-layer"></div>
                    <div class="search-overlay-layer"></div>
                    <div class="search-overlay-layer"></div>

                    <div class="search-overlay-close">
                        <span class="search-overlay-close-line"></span>
                        <span class="search-overlay-close-line"></span>
                    </div>

                    <div class="search-overlay-form">
                        <form>
                            <input type="text" class="input-search" placeholder="Search here...">
                            <button type="submit"><i class='bx bx-search-alt'></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <a href="#"  onclick="window.history.back();" class="btn Back-icon" title="Back" ><i class="fa fa-arrow-left" style="font-size: 15px;"></i></a>
        <!-- End Search Box Layout -->
