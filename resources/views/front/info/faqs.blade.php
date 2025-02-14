@extends('front.layouts.new')

@section('head')
<link href="{{ asset('assets/front/css/pre_video.css.css') }}" rel="stylesheet" />
<style type="text/css">
.col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
	float: right;
}
.tt-title-subpages {
    text-align: center;
    direction: rtl;
    margin-bottom: 10px;
    box-shadow: 1px 1px 1px 1px #f5eecf7a;
    padding: 5px;
}

</style>
@if (LaravelLocalization::getCurrentLocaleDirection() =='rtl')
<style type="text/css">

    .faq-accordion .accordion .accordion-content p {
    line-height: 1.8;
    margin-right: 10px;
}
</style>
@endif
@endsection


@section('content')
        <!-- Start Page Title Area -->
        <div class="page-title-area page-title-style-three item-bg3 jarallax inner_banner" data-jarallax='{"speed": 0.3}' style="background-image: url({{ asset('assets/front/img/bg-img/bg1.jpg') }});">
            <div class="container">
                <div class="page-title-content">
                    <ul>
                        <li><a href="{{ url('/') }}" >{{ __('words.home') }} </a></li>
                        <li>{{ trans('words.faqs') }}</li>
                    </ul>
                    <h2 class="inner_page_title">{{ trans('words.faqs') }}</h2>
                </div>
            </div>
        </div>



        <!-- End Page Title Area -->
   <section class="faq-area bg-f8e8e9 pb-100 pt-100" style="background-color: #cccccc12;">
            <div class="container">
                <div class="row">
                  {{--  <div class="col-lg-6 col-md-12">
                        <div class="faq-video">
                            <img src="https://www.arabiceasily.com/assets/img/business-coaching/faq.jpg" alt="image">

                            <a href="#" class="video-btn popup-youtube"><i class='bx bx-play'></i></a>
                        </div>
                    </div> --}}

                    <div class="col-lg-6 col-md-12">
                        <h1 class="tt-title-subpages">{{ trans('words.faqs') }}</h1>
                        <div class="faq-accordion faq-accordion-style-two">

                            <ul class="accordion">
                                @foreach($Faqs as $k=>$q)
                                    <li class="accordion-item">
                                        <a class="accordion-title {{$k==0?'active':''}}" href="javascript:void(0)">
                                            <i class='bx bx-chevron-down'></i>
                                            {{ $q->question }}
                                        </a>

                                        <div class="accordion-content  " style="margin: 0px 10px; {{$k==0?'display: block;':''}}">
                                            <p>{{ $q->answer  }}</p>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
