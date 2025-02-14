
@extends('front.layouts.master-study')

@section('css_pagelevel')
<link rel="stylesheet" href="{{ asset('assets/front/css/lessons.css') }}">
<style type="text/css">
.col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
	float: right;
}
.courses-sidebar-information ul li span {
        width: auto !important;
}

.courses-details-desc {
    width: 100%;
    margin-top: 5px !important;
}
.courses-sidebar-information {
    margin-bottom: 0px !important;
}
div .fade {
    display: contents !important;
}

@media only screen and (max-width: 767px){
    .for_web {
        display: none !important;
    }
    .Exercise10 .Ex10-colmimgq img {
    width: 100%;
        max-height: 85px !important;
}



.Exercise10 .Ex10-colmimgq {

    margin-left: 2%;
    margin-right: 1%;
    padding: 1px !important;

    height: auto !important;

}
.navbar-nav {
    float: right;
     padding: 0px !important;
}
.Exercise10 ul li {
    width: 20px;

}

}
ul .col-xs-12 {

    padding: 0px !important;
}
.Exercise10 .Ex10ClassDivMaster {

    padding: 0px !important;}
    .open {
        display: block;
    }
    h3.w-100.text-center.lecture {
    padding: 15px 0;
    color: #0026af;
    font-weight: bold;
    font-size: x-large;
}
</style>
@endsection

@section('content')


	<div id="main" class="container">
    <div class="row ">
      <h3 class="w-100 text-center lecture">{{$lecture->title_general}}</h3>

    </div>
		<div class="row">
      <iframe style="width:100%; height:780px;" src="https://accept.paymobsolutions.com/api/acceptance/iframes/{{config('paymob.iframe_id')}}?payment_token={{$token}}"></iframe>
    </div>
</div>




@section('js_pagelevel')

@endsection

@endsection
