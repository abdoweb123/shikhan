@extends('front.layouts.new')

@section('head')

@endsection

@section('content')

<section class="courses-details-area pt-100 pb-70">
  <div class="container">
    <div class="row" style="padding: 15px;">
      <h3 class="w-100 text-center lecture">Title</h3>
    </div>
		<div class="row">
      <iframe style="width:100%; height:780px;border: none;" src="https://accept.paymobsolutions.com/api/acceptance/iframes/{{config('paymob.iframe_id')}}?payment_token={{$token}}"></iframe>
    </div>
  </div>
</section>

@endsection


@section('script')

@endsection
