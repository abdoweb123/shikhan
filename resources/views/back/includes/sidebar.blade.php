<div class="left_col scroll-view them_back_color">
    <div class="navbar nav_title them_title" style="border: 0;">
        <a href="{{ route('dashboard.index') }}" class="site_title"><i class="fa fa-paw"></i> <span>{{ config('app.name') }}</span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile">
        <div class="profile_pic">
            @auth('admin')
            <img src="{{ url(Auth::guard('admin')->user()->image_path) }}" alt="{{ Auth::guard('admin')->user()->name }}" class="img-circle profile_img">
            @endauth
        </div>
        <div class="profile_info">
            <span>Welcome</span>
            @auth('admin')
            <h2>{{ Auth::guard('admin')->user()->name }}</h2>
             @endauth
        </div>
    </div>
    <!-- /menu profile quick info -->

    <br />


    {{--
    @php
        $target = '/home/fadamedia/newacademy/storage/app/public';
        $shortcut = '/home/fadamedia/newacademy/public/storage';
        symlink($target, $shortcut);
    @endphp
    --}}



    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu ">
        <div class="menu_section">
            <h3>General</h3>
            @auth('admin')
                @if (Auth::guard('admin')->user()->type_id == 2)
                    <ul class="nav side-menu">
                        <li><a href="{{route('dashboard.pay.review')}}"><i class="fa fa-users" aria-hidden="true"></i>Pay Review</a></li>
                    </ul>
                @else
                  <ul class="nav side-menu">
                      <li>
                          <a><i class="fa fa-bookmark-o"></i> Study Year <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" >
                              <li><a href="{{ route('dashboard.diplomas.index') }}">List</a></li>
                              <li><a href="{{ route('dashboard.diplomas.create') }}">Create Study Year</a></li>
                          </ul>
                      </li>
                      <li>
                          <a><i class="fa fa-bookmark-o"></i> Terms <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" >
                              <li><a href="{{ route('dashboard.terms.index') }}">List</a></li>
                              <li><a href="{{ route('dashboard.terms.create') }}">Create Term</a></li>
                          </ul>
                      </li>
                      <li {{ isset($site->alias) ? 'class=active' : '' }} >
                          <a><i class="fa fa-certificate"></i> Courses <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" style='display:block;'>
                              @foreach ($sitesTree ?? [] as $row)
                                  <li>
                                      <a href="{{route('dashboard.courses.getAll',['site'=>$row->id])}}">
                                          List
                                      </a>
                                  </li>
                                  <li {{ @$site->id == $row->id ? 'class=current-page' : '' }}>
    {{--                                  <a href="{{ route('dashboard.courses.index',$row->id) }}">--}}
                                      <a href="#">
                                          <img src="{{ url($row->ImageDetailsPath) }}" class="img-thumbnail" width="30" alt="{{ $row->title }}">
                                          {{ $row->title }}
                                      </a>
                                  </li>
                                  <div class="nav" style="color: white; margin-left: 10px; display:block !important;">
                                      @foreach($row->terms as $term)
                                          <li><a href="{{ route('dashboard.courses.index',['site'=>$row->id,'term'=>$term->id]) }}" style="color:#d0c9c9; padding:8px 10px 0px;">
                                                  {{$term->title}}
                                              </a>
                                          </li>
                                      @endforeach
                                  </div>
                              @endforeach
                              <li><a href="{{ route('dashboard.courses.to_assign_index') }}"><i class="fa fa-users" aria-hidden="true"></i> تخصيص الدورات للفصول الدراسية  </a></li>
                          </ul>
                      </li>
                      <li>
                          <a><i class="fa fa-bookmark-o"></i> Tests <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" >
                              <li><a href="{{ route('dashboard.tests.index') }}">List</a></li>
{{--                              <li><a href="{{ route('dashboard.tests.create')}}">Create Test</a></li>--}}
                          </ul>
                      </li>

                      <li>
                          <a><i class="fa fa-book"></i> Lessons <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" >
                              <li >
                                  <a href="{{ route('dashboard.lessons.index') }}">
                                      List
                                  </a>
                              </li>
                              <li >
                                  <a href="{{ route('dashboard.lessons.create') }}">
                                      Add lesson
                                  </a>
                              </li>
                          </ul>
                      </li>

                      {{--<li><a href="{{route('dashboard.members.index')}}"><i class="fa fa-users" aria-hidden="true"></i> Members </a></li>--}}
                      <li><a href="{{route('dashboard.users.info')}}"><i class="fa fa-users" aria-hidden="true"></i> Members Info </a></li>
                      <li><a href="{{route('dashboard.send_emails.index')}}"><i class="fa fa-users" aria-hidden="true"></i>Send Emails </a></li>
                      <li><a href="{{route('dashboard.send_emails.edit')}}"><i class="fa fa-users" aria-hidden="true"></i>Send Emails Status</a></li>
                      <li><a href="{{route('dashboard.send_notifications.inner.index')}}"><i class="fa fa-users" aria-hidden="true"></i>Send Notifications </a></li>
                      <li><a href="{{route('dashboard.send_notifications.inner.edit_template')}}"><i class="fa fa-users" aria-hidden="true"></i>After Registeration Notification Template</a></li>

                      <li><a href="{{route('dashboard.courses.certificates.templates')}}"><i class="fa fa-users" aria-hidden="true"></i>Course Templates</a></li>
                      <li><a href="{{route('dashboard.translations.index')}}"><i class="fa fa-users" aria-hidden="true"></i>Translations Words</a></li>
                      <li><a href="{{route('dashboard.statistics.daily_registerd')}}"><i class="fa fa-users" aria-hidden="true"></i>Daily Registerd</a></li>


                      <li><a href="{{route('dashboard.prize.index')}}"><i  class="fa fa-bookmark-o"></i> نتائج المسابقة </a></li>

                      <li>
                          <a><i class="fa fa-user"></i> Teachers <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" >
                                  <li ><a href="{{ route('dashboard.teachers.index') }}">List</a></li>
                                   <li ><a href="{{ route('dashboard.teachers.create') }}">Add Teacher</a></li>
                          </ul>
                      </li>

                        <li>
                          <a><i class="fa fa-user"></i> Pay <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" >
                                  <li ><a href="{{ route('dashboard.pay.review') }}">List</a>
                          </ul>
                      </li>

                      <li>
                          <a><i class="fa fa-user"></i> Partners <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" >
                                  <li ><a href="{{ route('dashboard.partners.index') }}">List</a></li>
                                   <li ><a href="{{ route('dashboard.partners.create') }}">Add Partner</a></li>
                          </ul>
                      </li>
                      {{--<li><a href="{{route('dashboard.PrevUrl')}}"><i class="fa fa-users" aria-hidden="true"></i> PrevUrl </a></li>--}}

                      <li>
                          <a><i class="fa fa-columns" aria-hidden="true"></i> Pages <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" >
                                  <li >
                                      <a href="{{ route('dashboard.pages.index') }}">
                                          List
                                      </a>
                                  </li>
                          </ul>
                      </li>
                      <li>
                          <a><i class="fa fa-sliders"></i> social <span class="fa fa-chevron-down"></span> </a>
                          <ul class="nav child_menu" >
                                  <li >
                                      <a href="{{ route('dashboard.social.index') }}">
                                          List
                                      </a>
                                  </li>
                                   <li >
                                      <a href="{{ route('dashboard.social.create') }}">
                                          Add social
                                      </a>
                                  </li>
                          </ul>
                      </li>


                      {{-- <li><a href="{{route('dashboard.statistics.daily')}}"><i  class="fa fa-bookmark-o"></i>احصائيات</a></li> --}}

                  </ul>
                @endif
            @elseauth('teacher')
                <ul class="nav side-menu">
                    <li style="margin-top: 20px"><a href="{{route('dashboard.users.info')}}"><i class="fa fa-users" aria-hidden="true"></i> Members Info </a></li>
                </ul>
            @endauth
        </div>
    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
        {{-- <a data-toggle="tooltip" data-placement="top" title="Settings">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
        </a>
        <a data-toggle="tooltip" data-placement="top" title="Lock">
            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
        </a> --}}
        {{-- <a data-toggle="tooltip" data-placement="top" title="Logout">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a> --}}
    </div>
    <!-- /menu footer buttons -->
</div>
