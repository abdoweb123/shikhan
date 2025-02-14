@extends('front.layouts.the-index')
@section('head')

<style>

</style>

@endsection
@section('content')


<!-- ##### Hero Area Start ##### -->
<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100" style="padding: 10px 0px;">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <!-- Hero Content -->
                <div class="hero-content text-center row">
                    <div class="col-md-12 d-flex">
                        <img src="{{ url($site->logo_path) }}" alt="{{ $site->title }}" class="bg-light img-raised img-fluid" style="width: 100px;border-radius: 18px;"> <!-- class="p-3 bg-light img-raised rounded-circle img-fluid" -->
                        <h1 class="sec-color" style="border-bottom: 1px solid black;">
                          <a href="{{ route('courses.index',$site->slug) }}">{{ $site->name }}</a>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ##### Hero Area End ##### -->

    @include('front.units.notify')

    <div class="profile-content">

        <div class="container" style="padding: 0px 20px 0px 20px;">
            <div class="row">

                  @if($site->new_flag == 0)
                    <h3 style="text-align: initial;padding-top: 30px;">{{ __('trans.diplome_successed_users') }}
                    <span style="font-weight: bold;border-bottom: 1px solid black;"><a href="{{ route('courses.index',$site->slug) }}">{{ $site->name }}</a></span>
                    <span class="label label-default"> {{ __('trans.until_today') }} {{ $successedUsersInEachCountryDate }}</span>
                    <span style="font-weight: bold;">( {{ $successedUsersInEachCountry->sum('count_success') }} {{ __('trans.student') }})</span>
                    </h3>                    
                  @else
                    <h3 style="text-align: initial;padding-top: 30px;">اسماء الطلاب الذين اجتازوا الدورات المقدمة في
                    <span style="font-weight: bold;border-bottom: 1px solid black;"><a href="{{ route('courses.index',$site->slug) }}">{{ $site->title }}</a></span>
                    <span class="label label-default"> حتى يوم  {{ $successedUsersInEachCountryDate }}</span>
                    <br>
                    {{--<span style="font-weight: bold;">( {{ $successedUsersInEachCountry->sum('count_success') }} طالب)</span>--}}
                    </h3>
                  @endif

                <br>

                <div class="row">
                  @foreach($successedUsersInEachCountry as $country)
                  <div style="padding: 5px;">
                    <a href="{{ route('successed_users_country_site' , ['site' => $site->slug , 'country' => $country->nicename ]) }}" class="btn btn-primary"
                        @if ( $currentCountry && $currentCountry->id == $country->id)
                          style="color: white;background-color: green;border-radius: 50px;border: 1px solid #c6c6c6;"
                        @else
                          style="color: black;background-color: white;border-radius: 50px;border: 1px solid #c6c6c6;"
                        @endif
                      >
                      {{ $country->flag }} {{ $country->nicename }}
                      <span class="badge badge-light" style="background-color: #79be1b;border-radius: 50px;">{{ $country->count_success }}</span>
                      <span class="sr-only">unread messages</span>
                    </a>
                  </div>
                  @endforeach
                </div>
            </div>
        </div>

        <div class="container" style="padding: 0px 20px 0px 20px;text-align: right;">
          <div id="div_new_data" class="col-12" style="padding: 20px;"></div>
        </div>

        <div class="d-flex justify-content-center" padding-bottom="25px;">
          <div id="loading" class="" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>


    </div>
@endsection


@section('script')

<x-subscripe-in-site/>



@if($currentCountry)
  <script>
  		var click=0;
      var fullLoadded = false;
      loadMore();

  		$(function() {
  		    $(window).scroll(function(){
          if (fullLoadded == true){
            return;
          }

  				var oTop = $('#div_new_data').offset().top;
  		      var pTop = $(window).scrollTop() + $(window).height();
  					if( pTop > oTop && loaded == true ){
  						loadMore();
  					}
  		    });
  		});

  		function loadMore() {

        $("#loading").addClass('spinner-border');

  			loaded = false;
  			click+=1;
  			$.ajax({
  				url: "/{{ app()->getlocale() }}/render_successed_users_country_site/{{ $site->id }}/{{ $currentCountry->id }}/?page="+click,
  				method:'get',
  				datatype:'json',
  				data:{'click':click},
  				success:function (data) {

  						if(loaded == false){
  							$("#div_new_data").append(data['htmlMore'])
                $("#loading").removeClass('spinner-border');

                fullLoadded = data['fullLoaded'];
                // console.log(fullLoadded);
  						  loaded=true;
  					}

  				},
  				error: function (data) {
            loaded=false;
          }
  			})
  		}
  </script>
@endif


@endsection
