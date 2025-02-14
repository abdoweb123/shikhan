<!-- general meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui">
<meta name="author" content="3mr-Morry">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title itemprop='name'>@lang('core.app_name') | {{ $page_name }}</title>

<!-- meta keywords and description -->
<meta name="title" content="{{ $page_name }}" />
<meta name="keywords" content="{{ config('app.meta.keywords') }}" />
<meta name="description" content="{{ config('app.meta.description') }}" />

@foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <link rel="alternate" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" hreflang="{{ $properties['regional'] }}">
@endforeach

<!-- Schema.org markup for Google+ -->
<meta itemprop="title" content="{{ $page_name }}" />
<meta itemprop="description" name="description" content="{{ config('app.meta.description') }}" />
<meta itemprop="keywords" content="{{ config('app.meta.keywords') }}" />

<!-- Open Graph data -->
<meta property="og:title" content="@lang('core.app_name') | {{ $page_name }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->full() }}">
<meta property="og:description" content="{{ config('app.meta.description') }}" />
<meta property="og:site_name" content="@lang('core.app_name')">

<meta name="robots" content="index, follow">

<!-- Twitter Card data -->
<meta name="twitter:card" content="@lang('core.app_name')">
<meta name="twitter:site" content="@lang('core.app_name')">
<meta name="twitter:title" content="@lang('core.app_name') | {{ $page_name }}">
<meta name="twitter:description" content="{{ config('app.meta.description') }}">
<meta name="twitter:creator" content="@author_handle">
<meta name="twitter:image:alt" content="@lang('core.app_name')">

<!-- Image for twitter ,Google+ and Open Graph data -->
@if(isset($image))
    <meta name="twitter:image" itemprop="image" property="og:image" content="{{ $image }}" />
@else
    <meta name="twitter:image" itemprop="image" property="og:image" content="{{ asset('assets/img/logo.png') }}" />
@endif


<script type="application/ld+json">
    {
        "@context" : "http://schema.org",
        "@type" : "Organization",
        "name" : "@lang('core.app_name')",
        "url" : "{{ route('home') }}",
        "sameAs" : [{{ @join(config('app.settings.social_media'),',') }}],
        "potentialAction" :
        {
            "@type" : "SearchAction",
            "target" : "{{ route('home') }}?term={search_term_string}",
            "query-input" : "required name=search_term_string"
        }
    }
</script>

<!-- favicon -->
<link href="{{ asset('assets/img/logo/favicon.png') }}" rel="shortcut icon" type="image/png">

<!-- Styles -->
@if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
<link rel="stylesheet" href="{{ asset('assets/front/style_rtl.css') }}">
@else
<link rel="stylesheet" href="{{ asset('assets/front/style.css') }}">
@endif

 <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
 <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
