@extends('front.layouts.the-index')

@section('head')
<link href="{{ asset('assets/front/css/pre_video.css.css') }}" rel="stylesheet" />
<style type="text/css">
.col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
	float: right;
}
.faq-accordion.faq-accordion-style-two {
    margin-top: 15px;
    direction: ltr;
    text-align: left;
}
</style>

@if (LaravelLocalization::getCurrentLocaleDirection() =='rtl')
<style type="text/css">
    h1 a{
			font-size: 2.5rem;
		}
  h2 a{
		font-size: 2rem !important;
		color: #88c830 !important;
	}
	h2 a span{
		font-size: 2rem !important;
		color: #88c830 !important;
	}
	h2 a strong{
		font-size: 2rem !important;
		color: #88c830 !important;
	}
   .faq-accordion.faq-accordion-style-two {
        direction: rtl;
        text-align: right;
    }
    td {
        border: 1px solid #38292947;
    }
    td {
        border: 1px solid #38292947;
        padding-right: 5px !important;
        padding-left: 5px;
    }
   td a {
    color: #b98757;
    font-size: large;
    font-weight: 800;
}
td a:hover {
    color: #b98757;
    font-size: large;
    font-weight: 800;
}
</style>
@endif
@endsection

@section('content')

        <!-- Start Page Title Area -->
        <div class=" item-bg3 jarallax inner_banner" data-jarallax='{"speed": 0.3}' style="background-image: url({{ asset('assets/front/img/bg-img/bg1.jpg') }});">
            <div class="container">
                <div class="page-title-content" style="padding-top: 10px !important;">
										{{--
                    <ul>
                        <li><a href="{{ url('/'.app()->getlocale()) }}" >{{ __('words.home') }} </a></li>
                        <li>{{ $info->activeTranslation->first()->title }}</li>
                    </ul>
										--}}
                    <h2 class="inner_page_title">{{ $info->activeTranslation->first()->title }}</h2>
                </div>
            </div>
        </div>


				@include('front.content.auth.register_every_page')


        <!-- Start FAQ Area -->
        <section class="faq-area bg-f8e8e9 pb-100 " style="background-color: #fff;">
            <div class="container">
                <div class="row">

                    @foreach($partners as $partner)
											<div class="col-md-4" style="font-size: 24px; padding: 10px;text-align: center;">
												<img src="{{ $partner->LogoPath }}" style="max-width: 200px;border-radius: 15px;border: 1px solid #bbb;"><br>
												{{ $partner->title }}
											</div>
										@endforeach

                </div>
            </div>
        </section>
        <!-- End FAQ Area -->




@endsection
