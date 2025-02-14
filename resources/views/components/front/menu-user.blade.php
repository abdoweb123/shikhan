
    <div class=" col-lg-4 profile-img-wrapper " style="display: flex">

        <div class="col-md-8 col-sm-4 col-xs-4" style="display: block">
            <a href="#" style="background-color: #5cc9df !important;padding: 5px 5px 0px;border-radius: 15px;font-size: 13px;color: #ffffff !important;text-decoration: none;" class="dropdown-toggle drop-down fa profile-img-name" data-toggle="dropdown" role="button" id="" aria-haspopup="true" aria-expanded="false">
             <span>{{ Auth::user()->user_name }}</span>
               @if(Auth::user()->image)
                  <img src="{{ Auth::user()->imagePath() }}" style="max-width: 40px;max-height: 40px;border-radius: 50%;" alt="" class="profile-img-img">
                @else
                  <i class="fa fa-user" style="font-size: 27px;"></i>
                @endif
            </a>
            <ul class="dropdown-menu">
                <!-- <li role="separator" class="divider"></li> -->
                @if ( app('userType') == 'student' )
                  <li><a href="{{ route('front.students.profile' , [ 'id' => Auth::user()->userable->id ] ) }}" style="class35;padding-top: 9px;color: #1d6abf !important;">{{ __('words.profile') }}</a></li>
                  <li><a href="{{ route('front.students.study' , [ 'id' => Auth::user()->userable->id ] ) }}" style="class35;padding-top: 9px;color: #1d6abf !important;">{{ __('words.study') }}</a></li>
                  @if (! app('member') )
                    {{--<li><a href="{{ route('front.members.show_code_form') }}" style="class35;padding-top: 9px;color: #1d6abf !important;">{{ __('words.have_code') }}</a></li>--}}
                  @endif
                @endif

                @if ( app('userType') == 'teachers' )
                  <li><a href="{{ route('front.teachers.profile' , [ 'id' => Auth::user()->userable->id ] ) }}" style="class35;padding-top: 9px;color: #1d6abf !important;">{{ __('words.profile') }}</a></li>
                  <li><a href="{{ route('front.teachers.study' , [ 'id' => Auth::user()->userable->id ] ) }}" style="class35;padding-top: 9px;color: #1d6abf !important;">{{ __('words.study') }}</a></li>
                @endif

                <li>
                  <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="class35;padding-top: 9px;color: #1d6abf !important;">{{ __('auth.logout') }}</a>
                  <form id="logout-form" action="{{ route('front.logout') }}" method="POST" style="display: none;">@csrf</form>
                </li>
            </ul>
        </div>
    </div>
