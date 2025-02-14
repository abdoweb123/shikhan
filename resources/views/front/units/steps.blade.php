<div class="row justify-content-lg-center">
        @if (! auth()->id())
        <div class="">
            <a class="btn clever-btn" href="{{ route('register') }}"  style="margin: 0px;padding: 13px 28px;">{{ __('trans.subscribe_in_diploma') }}</a>
            {{-- @lang('meta.title.login') --}}
        </div>
        @endif
        {{--<div class="col-lg-3 col-sm-6" style="margin: 0px;color: white;font-size: 19px;font-family: inherit;">
            <span style="padding: 0px 7px;font-size: 30px;color: #2165ad;">
                <i class="fa fa-television" aria-hidden="true"></i></span>@lang('core.header_feature_01')
        </div>
        <div class="col-lg-3 col-sm-6" style="margin: 0px;color: white;font-size: 19px;font-family: inherit;">
            <span style="padding: 0px 7px;font-size: 30px;color: #ff985a;">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>@lang('core.header_feature_02')
        </div>
        <div class="col-lg-3 col-sm-6" style="margin: 0px;color: white;font-size: 19px;font-family: inherit;">
            <span style="padding: 0px 7px;font-size: 30px;color: #3dd070;"><i class="fa fa-graduation-cap" aria-hidden="true"></i></span>@lang('core.header_feature_03')
        </div>--}}

</div>
