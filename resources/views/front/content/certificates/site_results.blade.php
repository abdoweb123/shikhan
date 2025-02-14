@extends('front.layouts.the-index')
@section('head')
<style>
  @media only screen and (max-width: 767px){
      .hero-area {
        height: 260px !important;
      }
  }
  .courses-title {
      text-align: center;
      font-size: x-large;
      font-weight: 900;
  }
  .course-serial {
      color: black;
  }
  .courses-details-desc .courses-accordion .accordion .accordion-item .accordion-content .courses-lessons .single-lessons .lessons-info .duration {
      text-align: center;
      margin-right: 0;
      direction: ltr;
      margin-left: 10px;
  }
  .courses-details-image img {
      height: 250px;
  }
  .swal2-popup .swal2-select {
      display: none;
  }
  .courses-details-desc .courses-accordion .accordion .accordion-item .accordion-title:hover, .courses-details-desc .courses-accordion .accordion .accordion-item .accordion-title.active {
      background-color: #623c16;
      color: #ffffff;
      border: #964d04 !important;
      box-shadow: 1px 0px 7px 2px #7c6044;
  }
  li.accordion-item {
      background: #f0f8ff !important;
      color: white !important;
  }
  .accordion-content.show {
      background: aliceblue;
  }
  .btn-danger {
      color: #f70707 ;
      background-color: #ffffff00 ;
      border-color: #ffffff00;
  }
  .courses_done {
    color: white;background-color: #17c811;;padding: 5px;border-radius: 5px;
  }
  .courses_done_label{
    color: #17c811;border-radius: 5px;padding: 53px 0px 0px 0px;font-size: 30px;font-weight: bold;
  }
  .courses_less {
    color: white;background-color: #f04f4f;;padding: 5px;border-radius: 5px;margin-right: 10px;
  }
  .courses_less_label {
    color: #f04f4f;border-radius: 5px;margin-right: 10px;padding: 53px 0px 0px 0px;font-size: 30px;font-weight: bold;
  }










</style>

<style>

  .card {
      padding-top: 20px;
      margin: 10px 0 20px 0;
      background-color: rgba(214, 224, 226, 0.2);
      border-bottom-width: 2px;
      -webkit-border-radius: 6px;
      -moz-border-radius: 6px;
      border-radius: 6px;
      -webkit-box-shadow: none;
      -moz-box-shadow: none;
      box-shadow: none;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
  }

  .card .card-heading {
      padding: 0 20px;
      margin: 0;
  }

  .card .card-heading.simple {
      font-size: 20px;
      font-weight: 300;
      color: #777;
      border-bottom: 1px solid #e5e5e5;
  }

  .card .card-heading.image img {
      display: inline-block;
      width: 46px;
      height: 46px;
      margin-right: 15px;
      vertical-align: top;
      border: 0;
      -webkit-border-radius: 50%;
      -moz-border-radius: 50%;
      border-radius: 50%;
  }

  .card .card-heading.image .card-heading-header {
      display: inline-block;
      vertical-align: top;
  }

  .card .card-heading.image .card-heading-header h3 {
      margin: 0;
      font-size: 14px;
      line-height: 16px;
      color: #262626;
  }

  .card .card-heading.image .card-heading-header span {
      font-size: 12px;
      color: #999999;
  }

  .card .card-body {
      padding: 0 20px;
      margin-top: 20px;
  }

  .card .card-media {
      padding: 0 20px;
      margin: 0 -14px;
  }

  .card .card-media img {
      max-width: 100%;
      max-height: 100%;
  }

  .card .card-actions {
      min-height: 30px;
      padding: 0 20px 20px 20px;
      margin: 20px 0 0 0;
  }

  .card .card-comments {
      padding: 20px;
      margin: 0;
      background-color: #f8f8f8;
  }

  .card .card-comments .comments-collapse-toggle {
      padding: 0;
      margin: 0 20px 12px 20px;
  }

  .card .card-comments .comments-collapse-toggle a,
  .card .card-comments .comments-collapse-toggle span {
      padding-right: 5px;
      overflow: hidden;
      font-size: 12px;
      color: #999;
      text-overflow: ellipsis;
      white-space: nowrap;
  }

  .card-comments .media-heading {
      font-size: 13px;
      font-weight: bold;
  }

  .card.people {
      position: relative;
      display: inline-block;
      width: 170px;
      height: 300px;
      padding-top: 0;
      margin-left: 20px;
      overflow: hidden;
      vertical-align: top;
  }

  .card.people:first-child {
      margin-left: 0;
  }

  .card.people .card-top {
      position: absolute;
      top: 0;
      left: 0;
      display: inline-block;
      width: 170px;
      height: 150px;
      background-color: #ffffff;
  }

  .card.people .card-top.green {
      background-color: #53a93f;
  }

  .card.people .card-top.blue {
      background-color: #427fed;
  }

  .card.people .card-info {
      position: absolute;
      top: 150px;
      display: inline-block;
      width: 100%;
      height: 101px;
      overflow: hidden;
      background: #ffffff;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
  }

  .card.people .card-info .title {
      display: block;
      margin: 8px 14px 0 14px;
      overflow: hidden;
      font-size: 16px;
      font-weight: bold;
      line-height: 18px;
      color: #404040;
  }

  .card.people .card-info .desc {
      display: block;
      margin: 8px 14px 0 14px;
      overflow: hidden;
      font-size: 12px;
      line-height: 16px;
      color: #737373;
      text-overflow: ellipsis;
  }

  .card.people .card-bottom {
      position: absolute;
      bottom: 0;
      left: 0;
      display: inline-block;
      width: 100%;
      padding: 10px 20px;
      line-height: 29px;
      text-align: center;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
  }

  .card.hovercard {
      position: relative;
      padding-top: 0;
      overflow: hidden;
      text-align: center;
      background-color: rgba(214, 224, 226, 0.2);
  }

  .card.hovercard .cardheader {
      /* background: url("http://lorempixel.com/850/280/nature/4/"); */
      background-size: cover;
      height: 135px;
  }

  .card.hovercard .avatar {
      position: relative;
      top: -50px;
      margin-bottom: -50px;
  }

  .card.hovercard .avatar img {
      width: 100px;
      height: 100px;
      max-width: 100px;
      max-height: 100px;
      -webkit-border-radius: 50%;
      -moz-border-radius: 50%;
      border-radius: 50%;
      border: 5px solid rgba(255,255,255,0.5);
  }

  .card.hovercard .info {
      padding: 4px 8px 10px;
  }

  .card.hovercard .info .title {
      margin-bottom: 4px;
      font-size: 24px;
      line-height: 1;
      color: #262626;
      vertical-align: middle;
  }

  .card.hovercard .info .desc {
      overflow: hidden;
      font-size: 12px;
      line-height: 20px;
      color: #737373;
      text-overflow: ellipsis;
  }

  .card.hovercard .bottom {
      padding: 0 20px;
      margin-bottom: 17px;
  }




</style>

@endsection
@section('content')

<section class=" bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container ">
        <div class="row align-items-center">
            <div class="col-12">.
                <!-- Hero Content -->
                <div class="hero-content text-center">
                  <!-- <div class="p-5"></div> -->
                  <div class="name" style="display: flex;">
                      <h3 class="title sec-color inner_page_title">@lang('meta.title.my_courses_cirts')</h3>
                      <!-- <a href="#courses_done"><h4 class="title courses_done">@lang('meta.alias.my_courses')</h4></a>
                      <a href="#courses_less"><h4 class="title courses_less">  دورات لم يتم الإشتراك بها </h4></a> -->
                  </div>

                </div>
            </div>
        </div>
    </div>
</section>



<div class="profile-content">
  <div class="container" >
    <section class="courses-details-area">
      <div class="container">

        @include('front.include.global_alert')

        <br>
        @if(session()->has('message'))
          <div class="alert alert-success">
              {{ session()->get('message') }}
          </div>
        @endif

        <div class="row">
          @include('front.include.ads_slids', [
              'ads_data' => [
                  1 => [
                    'img' => '/images/icons/ads_f.jpg',
                    'link' => 'https://www.fatwagate.com'
                  ],
                  2 => [
                    'img' => '/images/icons/01.jpg',
                    'link' => 'https://www.al-feqh.com/en/category/zakah'
                  ],
                  3 => [
                    'img' => '/images/icons/02.jpg',
                    'link' => 'https://www.with-allah.com/'
                  ]
              ]
          ])
        </div>

        <div class="row" style="padding: 30px 0px;">


            <div class="col-12" style="text-align: center; padding: 15px 0px;">
              @include('front.include.support_videos.get_site_certificate')
            </div>


            <div class="col-12" style="text-align: center; padding: 15px 0px;">
              <div class="container">
                <div class="row">
                  <div class="col-lg-3 col-sm-12">

                          <div class="card hovercard">
                              <div class="cardheader" style="background: url('{{asset('assets/img/profil_back_ground.jpg')}}')">
                              </div>
                              <div class="avatar">
                                  @if(!empty(Auth::guard('web')->user()->avatar))
                                      <img src="{{ Auth::guard('web')->user()->avatar_path }}">
                                  @else
                                      <i class="fa fa-user"></i>
                                  @endif
                              </div>
                              <div class="info">
                                  <div class="title">
                                      <a>{{ Auth::guard('web')->user()->name }}</a>
                                  </div>
                                  <div class="desc">{{ Auth::guard('web')->user()->email }}</div>
                                  <br><br>
                              </div>
                              <!-- <div class="bottom">
                                  <a class="btn btn-primary btn-twitter btn-sm" href="https://twitter.com/webmaniac">
                                      <i class="fa fa-twitter"></i>
                                  </a>
                                  <a class="btn btn-danger btn-sm" rel="publisher"
                                     href="https://plus.google.com/+ahmshahnuralam">
                                      <i class="fa fa-google-plus"></i>
                                  </a>
                                  <a class="btn btn-primary btn-sm" rel="publisher"
                                     href="https://plus.google.com/shahnuralam">
                                      <i class="fa fa-facebook"></i>
                                  </a>
                                  <a class="btn btn-warning btn-sm" rel="publisher" href="https://plus.google.com/shahnuralam">
                                      <i class="fa fa-behance"></i>
                                  </a>
                              </div> -->
                          </div>

                  </div>

                  <div class="col-8" style="text-align: initial; padding: 15px 0px;">
                    @foreach($count_tests_in_all_ranges as $key => $count)
                      <button type="button" class="btn {{ 'rate_range_'.$key }}" style="border-radius: 30px;border: none;font-size: 19px;margin-bottom: 5px;">
                        {{ __('trans.rate.'.$key) }} <span class="badge text-bg-secondary" style="background: white;border-radius: 10px;">{{ $count }} {{ __('trans.course')}}</span>
                      </button><br>
                    @endforeach
                  </div>

                </div>
              </div>
            </div>



            <!-- root extra certificates -->
            @if (ourAuth())
              @foreach ($root_sites as $root_site)
                @foreach ($root_site->extra_certificates ?? [] as $extraCertificat)
                  @if ($extraCertificat->result['deserve'])
                  <div class="col-lg-12" style="font-size: 17px;font-weight: bold;color: #825932;box-shadow: 1px 1px 9px 1px #0000002e; border-radius: 11px; padding: 24px;">

                      <!-- <img class="img-fluid" src="{{ url($root_site->ImageDetailsPath) }}" style="border-radius: 7px;cursor: pointer;"> -->
                      <span >{{ $root_site->name }}</span>

                      <div class="col-lg-12" style="padding: 8px 0px;margin: 8px 0px;">
                        <span style="font-size: 19px;font-weight: normal;">{{ __('trans.'.$extraCertificat->download_translation) }} {{ $extraCertificat->getTitle() }}</dpan>
                        <a data-href="{{ route('download-site-extra-certificate-show', ['id' => $root_site->id, 'extra_certificate_id' => $extraCertificat->id, 'type' => 'jpg' ]) }}"
                          class="download_image btn btn-outline-success">
                          <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;{{ __('trans.image') }}&nbsp;</i>
                        </a>
                        <a data-href="{{ route('download-site-extra-certificate-show', ['id' => $root_site->id, 'extra_certificate_id' => $extraCertificat->id, 'type' => 'pdf' ]) }}"
                          class="download_image btn btn-outline-success">
                          <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;Pdf&nbsp;</i>
                        </a>
                      </div>

                  </div>
                  @endif
                @endforeach
              @endforeach
            @endif







            @foreach ($sites as $site)
              @if ($site->isUserSubscribedInSite)

                  <div class="col-lg-4">
                    <div class="col-lg-12" style="padding: 8px 8px;margin: 8px 0px;border-radius: 11px;border: 1px solid #e6e6e6;
                      {{--
                        @if( $site->site_not_completed )border: 1px solid #e6e6e6;
                        @else border: 1px solid rgba(0,0,0,.1);box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
                        @endif
                      --}}
                      ">
                      <div class="row">

                        <!-- image -->
                        <div class="col-lg-3" >
                            <!-- <img class="img-fluid" onclick='getMyCourses({{ $site->id }})' data-toggle="modal" data-target=".bd-example-modal-lg" src="{{ url($site->logo_path) }}" style="border-radius: 7px;cursor: pointer;"> -->
                            <a href="{{ route('courses_certificates', ['site_id'=>$site->id ] ) }}"><img class="img-fluid" data-toggle="modal" data-target=".bd-example-modal-lg" src="{{ url($site->ImageDetailsPath) }}" style="border-radius: 7px;cursor: pointer;"></a>
                        </div>

                        <!-- title -->
                        <!-- <div class="col-lg-9" onclick='getMyCourses({{ $site->id }})' data-toggle="modal" data-target=".bd-example-modal-lg" style="font-size: 17px;font-weight: bold;color: #825932;cursor: pointer;">{{ $site->title }}</div> -->
                        <div class="col-lg-9" style="font-size: 17px;font-weight: bold;color: #825932;cursor: pointer;">
                            <a href="{{ route('courses_certificates', ['site_id'=>$site->id ] ) }}">{{ $site->name }}</a>
                        </div>

                        <!-- finished courses -->
                        <div class="col-lg-6 text-center" style="font-size: 16px;font-weight: bold;color: #825932;padding-top: 15px;"><span class="prim-color">{{ __('trans.achived')}} : {{ $site->finished_courses_count }} {{ __('trans.course')}}</span><span  style="color: gray;"> / {{ $site->courses_count }}</span></div>

                        <!-- courses results details -->
                        <div class="col-lg-6 text-center" style="padding: 10px 0px;">
                          <!-- <button class="btn btn-outline-primary" onclick='getMyCourses({{ $site->id }})' data-toggle="modal" data-target=".bd-example-modal-lg" style="cursor: pointer;">تفاصيل النتائج</button> -->
                          <a href="{{ route('courses_certificates', ['site_id'=>$site->id ] ) }}" class="btn but-default-rounded">{{ __('meta.title.courses_cirts')}}</a>
                        </div>

                        @if( $site->site_completed )
                          @if ( $site->user_finished_site )
                            @if ($site->user_sucess)
                              @if ($site->less_than_70)
                                <div class="col-lg-6 text-center" style="font-size: 16px;font-weight: bold;color: #825932;padding-top: 15px;"><span  class="prim-color">{{ __('trans.rating') }} : {{ __('trans.rate.'.$site->user_site_rate) }}</span></div>
                                <div class="col-lg-6 text-center" style="padding: 10px 0px;"><button class="but-more" data-toggle="modal" data-target="#cirt_modal_{{ $site->id }}" style="cursor: pointer;">{{ __('trans.diploma_cirt') }}</button></div>
                              @endif
                            @endif
                          @endif
                        @endif
                      </div>

                      @include('front.include.site_progress', [ 'courses_count' => $site->courses_count, 'finished_courses_count' => $site->finished_courses_count ])

                    </div>
                  </div>


                  <!-- loop modals of download cirt -->
                  @if( $site->site_completed )
                    @if ( $site->user_finished_site )
                      @if ($site->user_sucess)
                        @if ($site->less_than_70)
                            <div id="cirt_modal_{{ $site->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content" style="text-align: center;">
                                    <div class="modal-header">
                                      <!-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> -->
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">عودة</span>
                                      </button>
                                    </div>

                                    <div class="modal-body">
                                      <div class="col-lg-12" style="padding: 8px 0px;margin: 8px 0px;">

                                                <div class="col-lg-12 loading_div" style="padding: 8px 0px;margin: 8px 0px;"></div>

                                                <div class="col-lg-12" id="error_div_{{$site->id}}" style="color: red;"></div>


                                                <div class="col-lg-12" style="padding: 8px 0px;margin: 8px 0px;">
                                                  <span style="font-size: 19px;font-weight: normal;">{{ __('trans.download_cirt') }}</span>
                                                  <a data-href="{{ route('site-certificate-show', ['id' => $site->id, 'type' => 'jpg' ]) }}" data-error-div="error_div_{{$site->id}}"
                                                    class="download_image btn btn-outline-success">
                                                    <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;{{ __('trans.image') }}&nbsp;</i>
                                                  </a>
                                                  <a data-href="{{ route('site-certificate-show', ['id' => $site->id, 'type' => 'pdf' ]) }}" data-error-div="error_div_{{$site->id}}"
                                                    class="download_image btn btn-outline-success">
                                                    <i class="fa fa-file-pdf" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;Pdf&nbsp;</i>
                                                  </a>
                                                </div>
                                                <div class="col-lg-12" style="padding: 8px 0px;margin: 8px 0px;">
                                                  <span style="font-size: 19px;font-weight: normal;">{{ __('trans.download_result_details') }}</dpan>
                                                  <a data-href="{{ route('download-site-courses-certificate-show', ['id' => $site->id, 'type' => 'jpg' ]) }}"
                                                    class="download_image btn btn-outline-success">
                                                    <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;{{ __('trans.image') }}&nbsp;</i>
                                                  </a>
                                                  <a data-href="{{ route('download-site-courses-certificate-show', ['id' => $site->id, 'type' => 'pdf' ]) }}"
                                                    class="download_image btn btn-outline-success">
                                                    <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;Pdf&nbsp;</i>
                                                  </a>
                                                </div>



                                                <!-- extra certificates -->
                                                @foreach ($site->extra_certificates ?? [] as $siteExtraCertificat)
                                                  @if ($siteExtraCertificat->result['deserve'])
                                                    <div class="col-lg-12" style="padding: 8px 0px;margin: 8px 0px;">
                                                      <span style="font-size: 19px;font-weight: normal;">تحميل {{ $siteExtraCertificat->getTitle() }}</dpan>
                                                      <a data-href="{{ route('download-site-extra-certificate-show', ['id' => $site->id, 'extra_certificate_id' => $siteExtraCertificat->id, 'type' => 'jpg' ]) }}"
                                                        class="download_image btn btn-outline-success">
                                                        <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;صورة&nbsp;</i>
                                                      </a>
                                                      <a data-href="{{ route('download-site-extra-certificate-show', ['id' => $site->id, 'extra_certificate_id' => $siteExtraCertificat->id, 'type' => 'pdf' ]) }}"
                                                        class="download_image btn btn-outline-success">
                                                        <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;بى دى اف&nbsp;</i>
                                                      </a>
                                                    </div>
                                                  @endif
                                                @endforeach



                                      </div>
                                    </div>

                                </div>
                              </div>
                            </div>
                        @endif
                      @endif
                    @endif
                  @endif

              @endif
            @endforeach

        </div>
      </div>
    </section>
  </div>
</div>


  @if (ourAuth())


    <!-- <a data-href="{{ route('download-main-advanced-site-certificate-show', ['type' => 'jpg' ]) }}" class="download_image btn btn-outline-success">
        <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;شهادة دبلوم متقدم صورة&nbsp;</i>
    </a>

    <a data-href="{{ route('download-main-advanced-site-certificate-show', ['type' => 'pdf' ]) }}" class="download_image btn btn-outline-success">
        <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;شهادة دبلوم متقدم بى دى اف&nbsp;</i>
    </a>

    <a data-href="{{ route('download-details-advanced-site-certificate-show', ['type' => 'jpg' ]) }}" class="download_image btn btn-outline-success">
        <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;شهادة دبلوم متقدم تفاصيل صورة&nbsp;</i>
    </a>

    <a data-href="{{ route('download-details-advanced-site-certificate-show', ['type' => 'pdf' ]) }}" class="download_image btn btn-outline-success">
        <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;شهادة دبلوم متقدم تفاصيل بى دى اف&nbsp;</i>
    </a> -->

    <br>



  @endif












<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">عودة</span>
        </button>
      </div>
      <div class="modal-body" id="data_modal">
      </div>
    </div>
  </div>
</div>





<div id="div-download-img" style="width: 1px;height: 1px; position: absolute;z-index: -10;" >a</div>


@endsection

@section('script')
<script>
  function getMyCourses(site_id)
  {
      var dataModal = $('#data_modal');
      dataModal.html('');

      $.ajax({
          url: "{{ route('my_courses_data') }}",
          type: "get",
          data : { 'site_id': site_id },
          success: function (data) {
               // console.log(data['body']);
               dataModal.html(data['body']);

           },error:function(data){
               console.log(data.responseJSON);
           }
       });
  }

  $('.download_image').click(function(){

    // var oldHtml = $(this).html();
    // $(this).html(
    //   `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ` + oldHtml
    // );

    $('.loading_div').html(
      `<span class="spinner-border spinner-border-md" role="status" aria-hidden="true"></span> `
    );

    document.getElementById('div-download-img').innerHTML = '';
    var url = $(this).attr('data-href') ;
    var error_div = $(this).attr('data-error-div');
    $('#'+error_div).html('');

    $.ajax({
        url: url,
        type: "GET",
        data:{},
        success: function(result){

            // from RedirectIfNotVerified middleware
            if (result.redirect !== undefined){
              location.href = result.redirect;
            }

            if (result.errors != '') {
              $('#'+error_div).html(result.errors);
              $('.loading_div').html('');
              return;
            }

            $("#div-download-img").append(result.data);
            document.getElementById('div-download-img').innerHTML = '';
            $('.loading_div').html('');
        },error:function(error){
            console.log(error);
        }
    });
  });
</script>
@endsection
