@extends('front.layouts.the-index')
@section('head')
<style>
.single-blog-post .post-image img{-webkit-transition:all 2s cubic-bezier(.2,1,.22,1);transition:all 2s cubic-bezier(.2,1,.22,1)}.main-banner-content h1,.main-banner-content p,.main-banner-content span,.main-banner-content.text-center .sub-title{color:#1d5ea4}.main-banner-content .default-btn .label{color:#fff}.single-instructor-member .social i{color:#f2b827;font-size:16px;margin-right:-2px}.single-instructor-member .member-image img{height:300px}.faq-accordion.faq-accordion-style-two{background-color:#fff;border-radius:15px;padding:10px}
.single-blog-post .post-image::before {display: none;}
.single-blog-post .post-image::after {display: none;}
</style>

@endsection
@section('content')

    <div class="mx-auto" style="width: 1px;display: block;height: 10px;"></div>

    @include('front.content.auth.register_every_page')

    @include('front.include.global_alert')

    <!-- videos -->
    <div class="col-12" style="text-align: center; padding: 15px 0px;">
        @if(Auth::check())
          @include('front.include.support_videos.how_to_study')
        @else
          @include('front.include.support_videos.create_user')
        @endif
    </div>





    <!-- 01 -->
    <section class="faq-area bg-f8e8e9 pb-100 pt-3" style="padding-bottom: 10px;">
      <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12 home-video-bg">

                    <?php
                      $src = null;

                      $raw_link = $home?->video;
                      if ($raw_link){
                        $link_without_details = preg_replace('/&(.*)/','',$raw_link);
                        $t = str_replace('watch?v=', 'embed/', $link_without_details);
                        $src=$t;
                      }
                    ?>
                    @if ($src)
                      <iframe id="video_iframe" style="height: 420px;width: 100%;" class="embed-responsive-item" src="{{$src}}"></iframe>
                      <span style="font-size: 28px; color: #5c69a8;">{{ __('trans.video_intro') }}</span>
                    @endif
            </div>


            <div class="col-lg-6 col-md-12">
              <div class="col-lg-12">
                <div class="card-colorfull features main-features wow fadeInUp card-colorfull-bg-2" data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeInUp;">
                  <div class="text-left">
                    <h4 class="card-colorfull-title-color-2"><i class="fas fa-info-circle card-colorfull-icon-color-2"></i>{{ __('trans.why_us') }}</h4>
                    <p class="mb-0">{!! __('trans.why_us_text') !!}</p>
                  </div>
                </div>
              </div>
            </div>


        </div>
      </div>
    </section>

    <div id="scroll"></div>
    <div id="home_invisible_part"></div>

@endsection



@section('script')

{{--
<script>
  $(document).ready(function() {
      gatAnyData('home_invisible_part','','get','{{ route("home_invisible_part", ["lang" => "ar"] ) }}','');
  });
</script>
--}}




<script>
$(function() {
    var check='false';
    var oTop =$('#scroll').offset().top;

    if ($(window).scrollTop() == 0){
        gatAnyData('home_invisible_part','','get','{{ route("home_invisible_part", ["lang" => app()->getLocale() ] ) }}','');
        check='true';
    }

    $(window).scroll(function(){
        var pTop =  $(window).scrollTop() + $(window).height();
        if ((pTop > oTop) && (check == 'false')){

  			    gatAnyData('home_invisible_part','','get','{{ route("home_invisible_part", ["lang" => app()->getLocale() ] ) }}','');

            check='true';
        }
    });
});
</script>

@endsection
