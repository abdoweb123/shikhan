@if(Auth::guard('web')->check() && Auth::guard('web')->user()->hasVerifiedEmail())
    @include('front.units.aside.'.$track_type)
@else
    <ul class="navbar card p-3">
        <li class="w-100"> <a href="{{ route('verification.notice') }}" class=" btn m-0 py-3 px-5 w-100"> @lang('core.Verify_email') </a> </li>
    </ul>
@endif

<ul class="navbar card p-3">
    <li class="w-100"> <a href="https://www.facebook.com/groups/840854533008970/" target="_blank" class=" btn m-0 py-3 px-5 w-100"> @lang('title.chatting') </a> </li>
    <li class="w-100 mt-2 {{ Route::currentRouteName() == 'profile' ? 'active' : '' }}">
        <a href="{{ route('profile') }}" class=" btn py-3 px-5 w-100 "> @lang('core.profile') </a>
    </li>
    <li class="w-100 mt-2">
        <a class="btn py-3 px-5 w-100" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            @lang('core.logout')
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>
</ul>
