@extends('front.layouts.the-index')

@section('head')
<style type="text/css">
	.col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
		float: right;
	}
	.nice-select.form-control {
	   padding: 15px;text-align: right !important; direction: rtl;
	}
	span.current {
	    margin: 0px 18px;
	}

	.with-errors strong {
	    color: #dc3545;
	}
	.contact-info-box:hover .icon a {
	    background-color: #5cc9df;
	    color: #ffffff;
	    border-color: #5cc9df;
	}
	.contact-info-box .icon  a{
	    display: inline-block;
	    width: 70px;
	    height: 70px;
	    line-height: 70px;
	    background: #f5f5f5;
	    border-radius: 50%;
	    font-size: 35px;
	    color: #5cc9df;
	    -webkit-transition: 0.5s;
	    transition: 0.5s;
	    margin-bottom: 15px;
	    position: relative;
	}
	.contact-info-box .icon i {
	    padding: 16px 0px;
	    border-radius: 50%;
	}
	.contact-info-box .icon i {
	    padding: 16px 0px;
	    border-radius: 50%;
	}

	.contact-info-box:hover .icon a {
	    background-color: #0000;
	}
	.contact-info-box:hover .icon {
	    background-color: #5cc9df00;
	}
	.contact-info-box .icon i.bx.bx-envelope {
	    background: #dd4b39;
	  color: white;
	}
	.contact-info-box .icon:hover i.bx.bx-envelope {
	    background: white;
	  color: #dd4b39;
	  box-shadow: 0 0 6px 0px #dd4b39;
	}
	.contact-info-box .icon:hover i.fa-whatsapp {
	    color: #5cd335;
	    background:white;
	  box-shadow: 0 0 6px 0px #5cd335;
	}
	.contact-info-box {
	    -webkit-box-shadow: none;
	    box-shadow: none;
	    background: #0000 !important;
	    padding: 0 !important;
	    margin: 10px 7px 0px;
	}
	.contact-info-box .icon {
	    margin: 0 !important;
	    background: #0000 !important;
	}
	.m-s-175 {
	        text-align: center;
	        margin: 0 10px;
	    }
	@media only screen and (min-width: 780px) {
	 .m-s-175 {
	        text-align: center;
	        margin: 0 175px;
	    }
	}
	.section-title .sub-title ,.section-title p {

	    color: #834500 !important;
	    font-weight: 900 !important;
	    font-size: 25px !important;
	    /* margin-bottom: 10px; */
	}
	.section-title h2 {
	    color: #834500a3;
	}
	::-webkit-input-placeholder { /* WebKit, Blink, Edge */
	    color:    #542c00  !important;
	}
	:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
	   color:    #542c00  !important;
	   opacity:  1;
	}
	::-moz-placeholder { /* Mozilla Firefox 19+ */
	   color:    #542c00  !important;
	   opacity:  1;
	}
	:-ms-input-placeholder { /* Internet Explorer 10-11 */
	   color:    #542c00  !important;
	}
	::-ms-input-placeholder { /* Microsoft Edge */
	   color:    #542c00  !important;
	}

	::placeholder { /* Most modern browsers support this now. */
	   color:    #542c00  !important;
	}
	.contact-form form .form-control {

	    padding: 5px 10px;
	    color: rgb(69 37 0);
	}
</style>
@endsection

@section('content')

        <!-- Start Page Title Area -->
        <div class="item-bg3 jarallax inner_banner" data-jarallax='{"speed": 0.3}' style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }});">
            <div class="container">
                <div class="page-title-content" style="padding-top: 10px;">
                    <h1 class="inner_page_title">{{ $translation->title }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Title Area -->

        <!-- Start Contact Info Area -->
        <section class="contact-info-area ">
            <div class="container">

            </div>
            <div id="particles-js-circle-bubble-2"></div>
        </section>
        <!-- End Contact Info Area -->

        <!-- Start Contact Area -->

        <section class="contact-area pb-100">
            <div class="container">
                <div class="section-title">
                    <h3 style="padding: 40px;color: gray;">{{ __('words.contact_us_1') }}</h3>
                    <div class="row m-s-175">
						@isset($social)
						     @foreach($social as $item_social)
                                <div class="contact-info-box">
                                    <div class="icon">
                                        <a href="{{$item_social->link}}" class="d-block" target="_blank">{!! $item_social->icon !!}</a>
                                    </div>
                                </div>
                            @endforeach
						@endisset
                    </div>
                </div>


                <div class=" col-md-12">
                    <div class="faq-accordion faq-accordion-style-two">
{{--                        @if(file_exists('storage/'.$translation->description) == true)--}}
{{--                                {!!  file_get_contents('storage/'.$translation->description) !!}--}}
{{--                        @endif--}}
                        {!! $translation?->description ?
                      \Illuminate\Support\Facades\Storage::exists($translation?->description) ?
                        \Illuminate\Support\Facades\Storage::get($translation?->description)
                        : ''
                      : ''
                    !!}

                         <img src="{{ $translation->imagePath() }}">
                    </div>
                </div>


                @include('front.content.auth.register_every_page')
            </div>

        </section>

        <!-- End Contact Area -->





@endsection


@section('script')
<x-recaptcha.js/>
@endsection
