<div class="nav_menu">
    <nav>
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    @auth('admin')
                        <img src="{{ url(Auth::guard('admin')->user()->image_path) }}" alt="">
                    @elseauth('teacher')
                        {{ Auth::guard('teacher')->user()->name }}
                    @endauth
                    <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="#"> Profile --</a></li>

                    @auth('admin')
                        <li>
                            <a class="fa fa-sign-out pull-right" href="{{ route('dashboard.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                {{ __('core.logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('dashboard.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @elseauth('teacher')
                        <li>
                            <a class="fa fa-sign-out pull-right" href="{{ route('logout.teacher') }}" onclick="event.preventDefault();document.getElementById('logout-form-teacher').submit();">
                                {{ __('core.logout') }}
                            </a>

                            <form id="logout-form-teacher" action="{{ route('logout.teacher') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endauth


                </ul>
            </li>
            @isset($site)
                <li class="">
                    <a href="{{ route('dashboard.courses.index',$site->id) }}" class="user-profile dropdown-toggle" style="border: 2px solid #ccc;">
                        <img src="{{ url($site->logo_path) }}" class="img-thumbnail" width="30" alt="{{ @$site->title }}">
                        {{ $site->title }}
                    </a>
                </li>
            @endisset
        </ul>
    </nav>
</div>
