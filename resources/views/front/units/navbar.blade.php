<!-- ##### Header Area Start ##### -->
<header class="header-area">

    <!-- Top Header Area -->
    <div class="top-header-area d-flex justify-content-between align-items-center">
        <!-- Contact Info -->
        <div class="contact-info">
            <!-- <a href="#"><span>Phone:</span> +44 300 303 0266</a> -->
            <a ><span>Email:</span>info@courses.al-feqh.com </a>
        </div>
        <!-- Follow Us -->

        @if (! $SocialInfo->isEmpty())
        <div class="follow-us">
            <span>Follow us</span>
            @foreach ($SocialInfo as $Social)
                @if ($Social->social_id == 1)
                    <a href="{{ $Social->link }}"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                @endif
                @if ($Social->social_id == 2)
                    <a href="{{ $Social->link }}"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                @endif
                @if ($Social->social_id == 3)
                    <a href="{{ $Social->link }}"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                @endif
            @endforeach
        </div>
        @endif
    </div>

    <!-- Navbar Area -->
    <div class="clever-main-menu">
        <div class="classy-nav-container breakpoint-off">
            <!-- Menu -->
            <nav class="classy-navbar justify-content-between" id="cleverNav">

                <!-- Logo -->
                <a class="nav-brand" href="{{ route('home') }}">
                    <img src="img/core-img/logo.png" alt="">@lang('meta.title.home')
                </a>

                <!-- Navbar Toggler -->
                <div class="classy-navbar-toggler">
                    <span class="navbarToggler"><span></span><span></span><span></span></span>
                </div>

                <!-- Menu -->
                <div class="classy-menu">

                    <!-- Close Button -->
                    <div class="classycloseIcon">
                        <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                    </div>

                    <!-- Nav Start -->
                    <div class="classynav">
                        <!-- <ul>
                            <li><a href="index.html">Home</a></li>
                            <li><a href="#">Pages</a>
                                <ul class="dropdown">
                                    <li><a href="index.html">Home</a></li>
                                    <li><a href="courses.html">Courses</a></li>
                                    <li><a href="single-course.html">Single Courses</a></li>
                                    <li><a href="instructors.html">Instructors</a></li>
                                    <li><a href="blog.html">Blog</a></li>
                                    <li><a href="blog-details.html">Single Blog</a></li>
                                    <li><a href="regular-page.html">Regular Page</a></li>
                                    <li><a href="contact.html">Contact</a></li>
                                </ul>
                            </li>
                            <li><a href="courses.html">Courses</a></li>
                            <li><a href="instructors.html">Instructors</a></li>
                            <li><a href="blog.html">Blog</a></li>
                            <li><a href="contact.html">Contact</a></li>
                        </ul> -->

                        <!-- Search Button -->
                        <div class="search-area">
                            <!-- <form action="#" method="post">
                                <input type="search" name="search" id="search" placeholder="Search">
                                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </form> -->
                            @if(count(LaravelLocalization::getSupportedLanguagesKeys()) > 1)
                                <li class="nav-item dropdown my-auto" style="list-style-type: none;">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown"  rel="tooltip" title="" data-placement="bottom" data-original-title="{{ __('core.languages') }}">
                                        <i class="fa fa-language"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-secondary">
                                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                            @php
                                                $localizedUrl = empty(Route::current()->parameters())
                                                ? LaravelLocalization::getLocalizedURL($localeCode)
                                                : LaravelLocalization::getURLFromRouteNameTranslated($localeCode,'meta.alias.'.Route::currentRouteName(),event('routes.translation', [$localeCode, Route::current()->parameters()])[0])
                                            @endphp
                                            <a class="dropdown-item {{ $localeCode === App::getLocale() ? 'active' : '' }}" rel="alternate" hreflang="{{ $localeCode }}" href="{{ $localizedUrl }}">
                                                {{ $properties['native'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </li>
                            @endif
                        </div>

                        <!-- Register / Login -->
                        @if(Auth::guard('web')->check())
                            <div class="login-state d-flex align-items-center">
                                <div class="user-name mr-30">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle" href="#" role="button" id="userName" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::guard('web')->user()->name }}</a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userName">

                                            <a class="dropdown-item {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}">
                                                <i class="fa fa-id-card mx-1"></i>
                                                @lang('meta.title.profile')
                                            </a>
                                            <a class="dropdown-item {{ Route::currentRouteName() == 'certificates' ? 'active' : '' }}" href="{{ route('certificates') }}">
                                                <i class="fa fa-graduation-cap"></i>
                                                @lang('meta.title.certificates')
                                            </a>
                                            <a class="dropdown-item {{ Route::currentRouteName() == 'my_quizzes' ? 'active' : '' }}" href="{{ route('my_quizzes') }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                                @lang('meta.title.my_quizzes')
                                            </a>

                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                                <i class="fa fa-sign-out"></i>
                                                @lang('core.logout')
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>



                                    </div>
                                </div>
                                <div class="userthumb">
                                    @if(!empty(Auth::guard('web')->user()->avatar))
                                        <img src="{{ url(Auth::guard('web')->user()->avatar_path) }}" class="rounded-circle z-depth-0" title="{{ Auth::guard('web')->user()->name }}">
                                    @else
                                        <i class="fas fa-user"></i>
                                        <img src="img/bg-img/t1.png" alt="">
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="register-login-area">
                                <a href="{{ route('login') }}" class="btn">{{ __('meta.title.login') }}</a>
                                <a href="{{ route('register') }}" class="btn active">{{ __('meta.title.register') }}</a>

                                    {{--
                                    <a class="btn" href="{{ route('socialite.index','facebook') }}" data-original-title="Login with Twitter" rel="nofollow">
                                        <i class="fab fa-twitter"></i>
                                        <span class="d-md-inline-block d-lg-none"> Login with Twitter </span>
                                    </a>

                                    <a class="btn" href="{{ route('socialite.index','twitter') }}" data-original-title="Login with Facebook" rel="nofollow">
                                        <i class="fab fa-facebook-square"></i>
                                        <span class="d-md-inline-block d-lg-none"> Login with Facebook </span>
                                    </a>

                                    <a class="btn" href="{{ route('socialite.index','google') }}" data-original-title="Login with Google" rel="nofollow">
                                        <i class="fab fa-google"></i>
                                        <span class="d-md-inline-block d-lg-none"> Login with Google </span>
                                    </a>
                                    --}}

                            </div>
                        @endif

                    </div>
                    <!-- Nav End -->
                </div>
            </nav>
        </div>
    </div>
</header>
<!-- ##### Header Area End ##### -->
