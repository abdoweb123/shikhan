<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
<!--<meta name="author" content="3mr-Morry">-->
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="icon" href="{{asset('assets/img/logo-imameen.png')}}" type="image/x-icon">
@if( $page_name ?? '' == "الرئيسية")
<link rel="canonical" href="{{url()->current()}}" />
@endif

<!-- meta keywords and description -->
{{--@if( isset($seo_info))--}}
{{--   {!! $seo_info !!}--}}
{{--@elseif( $page_name ?? '' == "الرئيسية")--}}
{{--    <title itemprop='name'>@lang('core.app_name') | {{ __('core.home_header') }} </title>--}}
{{--    <meta name="title" content="{{ __('core.home_title') }}" />--}}
{{--    <meta name="keywords" content=" {{ __('core.home_keywords') }}" />--}}
{{--    <meta name="description" content="{{ __('core.home_description') }}" />--}}
{{--@else--}}
{{--    @isset($title_page)--}}
{{--        <title itemprop='name'>@lang('core.app_name') |  @isset($title_page) {{$title_page}} @endisset</title>--}}
{{--        <meta name="title" content="{{$title_page}}" />--}}
{{--        <meta name="keywords" content=" {{ __('core.home_keywords') }},{{$title_page}}" />--}}
{{--        <meta name="description" content="{{ __('core.home_description') }} , {{$title_page}}" />--}}
{{--    @else--}}
{{--        <title itemprop='name'>@lang('core.app_name') | {{ __('core.home_header') }} </title>--}}
{{--        <meta name="title" content="{{ __('core.home_title') }}" />--}}
{{--        <meta name="keywords" content=" {{ __('core.home_keywords') }}" />--}}
{{--        <meta name="description" content="{{ __('core.home_description') }}" />--}}
{{--    @endisset--}}
{{--@endif--}}


<title>{{ __('core.home_header') }}</title>


<link rel="dns-prefetch" href="https://www.baldatayiba.com">
<link rel="dns-prefetch" href="https://www.google.com">
<link rel="dns-prefetch" href="https://www.google-analytics.com">
<link rel="dns-prefetch" href="https://cse.google.com">
<link rel="dns-prefetch" href="https://kit.fontawesome.com">

{{--
<!-- force browser to get last data -->
<link defer rel="stylesheet" href="{{asset('assets/new_front/ver.css?version=1.0')}}">
--}}

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-G8GWXE4GYG"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-G8GWXE4GYG');
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-260352001-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-260352001-3');
</script>


@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <link rel="alternate" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" hreflang="{{ $properties['regional'] }}">
@endforeach

@if(isset($image))
    <meta name="twitter:image" itemprop="image" property="og:image" content="{{ $image }}" />
@else
    <meta name="twitter:image" itemprop="image" property="og:image" content="{{ asset('assets/img/logo2.png') }}" />
@endif

{{--
<!-- Global site tag (gtag.js) - Google Ads: 811767693 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-811767693"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-811767693');
</script>
--}}

{{--
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-199138328-1"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

 gtag('config', 'UA-199138328-1');
</script>
--}}

<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-629607c4563905d6"></script>


<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1169504857201093');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1169504857201093&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->

<meta name="facebook-domain-verification" content="jh6zfk635becoz3p920q8y3ufgjy7l" />

<!-- favicon -->
<link href="{{ asset('assets/img/logo/favicon.png') }}" rel="shortcut icon" type="image/png">

<!-- Styles -->
@include('front.layouts.design_3.css.bootstrap')
{{--<link rel="stylesheet" href="{{asset('assets/new_front/css/bootstrap.min.css')}}">--}}

@include('front.layouts.design_3.css.boxicons')
{{--<link defer rel="stylesheet" href="{{asset('assets/new_front/css/boxicons.min.css')}}">--}}

{{--
<!-- <link rel="stylesheet" href="{{asset('assets/new_front/css/flaticon.css')}}"> -->
--}}

@include('front.layouts.design_3.css.owl-carousel')
{{--<link rel="stylesheet" href="{{asset('assets/new_front/css/owl.carousel.min.css')}}">--}}

{{--
<!-- <link rel="stylesheet" href="{{asset('assets/new_front/css/odometer.min.css')}}"> -->
--}}

@include('front.layouts.design_3.css.meanmenu')
{{--<link rel="stylesheet" href="{{asset('assets/new_front/css/meanmenu.min.css')}}">--}}


@include('front.layouts.design_3.css.style')
{{--<link defer rel="stylesheet" href="{{asset('assets/new_front/css/style.min.css')}}">--}}

<!-- <link rel="stylesheet" href="{{asset('assets/new_front/css/my-style.css')}}"> -->

<!-- swal -->
{{--@include('front.layouts.design_3.css.sweetalert3')--}}
{{--<link defer href="{{ asset('assets/admin/vendors/general/sweetalert2/dist/sweetalert3.css') }}" rel="stylesheet" type="text/css" />--}}

@if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
  @include('front.layouts.design_3.css.rtl')
  {{--<link rel="stylesheet" href="{{asset('assets/new_front/css/rtl.min.css')}}">--}}
  @include('front.layouts.design_3.css.responsive')
  {{--<link rel="stylesheet" href="{{asset('assets/new_front/css/responsive.min.css')}}">--}}
@else
  @include('front.layouts.design_3.css.responsive')
  {{--<link rel="stylesheet" href="{{asset('assets/new_front/css/responsive.min.css')}}">--}}
@endif


<link defer href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">

{{--<link async href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">--}}

<!-- paybal -->
<script defer type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-622dd287f7191172"></script>

<style>
   a.whatsapp-share.icon_whatsapp {z-index: 1000;}
</style>
