<!-- ##### Register Now Start ##### -->
<section class="d-flex justify-content-between align-items-center" style="background-color: #e2e9fc;padding: 20px 12px;border-radius: 10px;">
    <!-- Register Contact Form -->
    <div class="register-contact-form mb-100">
        <div class="container-fluid">
            @include('front.units.notify')
            <div class="row">
                <div class="col-12" style="text-align: center;">
                    <div class="forms">
                        @if (Auth::guard('web')->check())
                            @if (! Auth::guard('web')->user()->courses()->find($course->id))
                                <h4 style="color: #959595;">@lang('core.registr_in')</h4>
                                <h5 style="color: #2884b0">{{ isset( $course->name ) ? $course->name : ''}}</h5>
                                <p class="card-text"> @lang('core.notify_quizs') </p>
                            @endif
                        @endif



                            <div class="row" style="margin: auto;">
                                @if (Auth::guard('web')->check())
                                    @if (Auth::guard('web')->user()->courses()->find($course->id))
                                        <a href="{{ route('courses.unsubscription',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-danger" style="width: 100%;margin: 10px;"> @lang('core.unsubscribe') </a>
                                        <a href="{{ route('courses.quiz',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-success" style="width: 100%;margin: 10px;"> @lang('core.test_now') </a>
                                    @else
                                        <a href="{{ route('courses.subscription',['site' => $site->alias,'course' => $course->alias]) }}" class="btn btn-info" style="width: 100%;"> @lang('core.newsletter_submit') </a>
                                    @endif
                                @else
                                    <a class="btn clever-btn w-100" href="{{ route('login') }}"  style="width: 100%;">
                                        @lang('meta.title.login')
                                    </a>
                                @endif
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ##### Register Now End ##### -->
