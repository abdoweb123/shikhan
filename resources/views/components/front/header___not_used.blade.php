<header>
            <div class="row Master-header-row1">
              <div class="container-fluid">

                <div class="col-md-1 col-sm-3 col-xs-4 Master-header-colm2">
                  <img alt="" class="img-responsive" src="Images/Logo.png">
                </div>
                <div class="col-md-2 col-sm-6 col-xs-8 Master-header-colm3">
                  <h1>{{ $settings['app_title'] }}</h1>
                  <h2>تعليم العربية لغير الناطقين بها</h2>
                </div>
                        <div class="col-md-7 col-sm-6 col-md-offset-0 col-sm-offset-12 col-xs-8 Master-header-colm3">
        <div class="container">
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="padding-top: 11px;">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="fa fa-bars" aria-hidden="true"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('front.info.site_tour') }}" style=" padding-top: 10px;">{{ __('project.site_tour') }}</a></li>
                            <li><a href="{{ route('front.info.project_goals') }}" style="padding-top: 10px;">{{ __('project.project_goals') }}</a></li>
                            <li><a href="{{ route('front.info.contact_us') }}" style="padding-top: 10px;">{{ __('project.contact_us') }}</a></li>
                            <li><a href="{{ route('front.info.faqs') }}" style=" padding-top: 10px;">{{ __('words.faqs') }}</a></li>
                            <li><a href="{{ route('front.info.about_us') }}" style="  padding-top: 10px;">{{ __('words.about_us') }}</a></li>
                            <!-- <li><a href="TellFr.html" style="  padding-top: 10px;">اخبر صديق</a></li> -->
                            <!-- <li><a href="statistics.html" style="  padding-top: 10px;background-color: white;">احصائيات</a></li> -->
                        </ul>
                    </li>
                <!-- <li><a href="Skills.html">المهارات </a></li> -->
                {{--<li><a href="grammar.html">{{ __('project.grammer_contents') }}</a></li>--}}
                <!-- <li><a href="LecturesOnline.html">محاضرات أونلاين</a></li> -->
                <li><a href="{{ route('front.letters.index') }}">{{ __('project.alphabetic') }}</a></li>
                <li><a href="{{ route('front.dictionary.index') }}">{{ __('project.dictionary') }}</a></li>

                <li class="active"><a href="/">{{ __('words.home') }}<span class="sr-only">(current)</span></a></li>

              </ul>
            </div><!-- /.navbar-collapse -->
          </div>
                </div>
                <div class="col-md-2 col-sm-3 col-xs-12 Master-header-colm4">
                  @guest
                    <a href="{{ route('front.register') }}">
                        <div class="col-md-2 col-xs-5 right-icons icon2">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </div>
                    </a>
                    <a href="{{ route('front.login') }}">
                        <div class="col-md-2 col-xs-5 right-icons icon2">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </div>
                    </a>
                  @else
                    <span class="headerIcon-user">
                      <i aria-hidden="true" class="fa fa-user"></i></span>
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true" style="color: #337ab7; font-size: 26px"></i>
                    </button>
                    <div id="bs-example-navbar-collapse-1">

                        <x-front.menu-user/>

                    </div>
                  @endguest
                </div>
              </div>
            </div>
            <div class="row Master-header-row2">
              <div class="col-xs-10  Master-header-colm1">
                <div class="row Master-header-colm1-row1">
                  <span><i aria-hidden="true" class="fa fa-cog"></i></span>
                </div>
                <div class="row Master-header-colm1-row2">
                  <label>خطة التعلم</label> </div>
              </div>
            </div>
</header>
