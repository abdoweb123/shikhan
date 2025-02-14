@extends('front.layouts.the-index')
@section('head')

<!-- swal -->
@include('front.layouts.new_design.css.sweetalert3')

<style>
  .row.justify-content-center {
      overflow-x: scroll;
  }
  th, td {
      text-align: center;
  }
</style>
@endsection
@section('content')


<section class=" bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <!-- Hero Content -->
                <div class="hero-content text-center row">
                    <div class="col-2">
                        @if(!empty(Auth::guard('web')->user()->avatar))
                            <div class="avatar">
                                {{--dd(url(Auth::guard('web')->user()->avatar))--}}
                                <img src="{{ url(Auth::guard('web')->user()->avatar_path) }}" class="bg-light img-raised img-fluid" style="width: 80px;border-radius: 18px;" alt="{{ Auth::guard('web')->user()->name }}">
                            </div>
                        @else
                            <div class="p-5"></div>
                        @endif
                    </div>
                    <div class="col-10" style="text-align: right;padding: 13px 0px;">
                        <h1 class="sec-color inner_page_title">{{ __('trans.result_details') }}</h1>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="description text-center">
        {{-- <p> {{ $site->description }} </p> --}}
    </div>
    @include('front.units.notify')

    @include('front.content.certificates.site_courses_results_content', ['results'=> $results ])

  <div id="div-download-img" style="width: 1px;height: 1px; position: absolute;z-index: -10;" >a</div>
</div>
@endsection



@section('script')
<x-subscripe-unsubscripe-ajax-js/>

<script type='text/javascript'>
    $('.download_image').click(function(){

        var id = $(this).attr('data-id');

        $('.loading_div_'+id).html(
          `<span class="spinner-border spinner-border-md" role="status" aria-hidden="true"></span> `
        );

        document.getElementById('div-download-img').innerHTML = '';
        var url = $(this).attr('data-href') ;

        $.ajax({
            url: url,
            type: "GET",
            data:{},
            success: function(result){
                // console.log(result);

                // from RedirectIfNotVerified middleware
                if (result.redirect !== undefined){
                  location.href = result.redirect;
                }

                $("#div-download-img").append(result.data);
                document.getElementById('div-download-img').innerHTML = '';
                $('.loading_div_'+id).html('');
            },error:function(error){
                console.log(error);
            }
        });
    });
</script>




{{--
<!-- @include('front.layouts.new_design.js.sweetalert3-min')
<script>
  $( '.v_q_alert' ).click(function(e) {
       e.preventDefault();
       var url = $(this).attr('url');

       Swal.fire({
         title: '{{__("words.q_title")}}',
         icon: 'question',
         html: "{{__('words.q_alert1')}} <br/> {{__('words.q_alert2')}}",

         confirmButtonText: '{{__("words.q_Yes")}}',
         cancelButtonText: '{{__("words.no")}}',
         showCancelButton: true,
         showCloseButton: true
       }).then((result) => {
         if (result.value) {
            window.location.href = url
         }
       })
  });
</script> -->
--}}

@endsection
