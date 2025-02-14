@extends('front.layouts.the-index')
@section('head')
    <style>
        .single-blog-post .post-image img {
            -webkit-transition: all 2s cubic-bezier(0.2, 1, 0.22, 1);
            transition: all 2s cubic-bezier(0.2, 1, 0.22, 1);
            height: 380px !important;
        }
        .main-banner-content h1 , .main-banner-content span , .main-banner-content p , .main-banner-content.text-center .sub-title  {
            color: #1d5ea4;
        }
       .main-banner-content .default-btn .label  {
            color: white;
        }

        .single-instructor-member .social i {
            color: #f2b827;
            font-size: 16px;
            margin-right: -2px;
        }
        .single-instructor-member .member-image img {
            height: 300px;

        }

    </style>
@endsection
@section('content')

<!-- ##### Hero Area Start ##### -->
<section class="hero-area bg-img bg-overlay-2by5 inner_banner" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 100px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 "style=" margin-top: 20px;">
                <!-- Hero Content -->
                <div class="page-title-content" style="text-align: center;">
                    {{--
                    <ul>
                        <li><a href="{{url('/')}}">{{__('core.dashboard_title_n')}}</a></li>
                        <li>{{__('core.teachers')}}</li>
                    </ul>
                    --}}
                    <h2 class="inner_page_title">{{__('core.teachers_dis')}}</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ##### Hero Area End ##### -->

@include('front.content.auth.register_every_page')

@include('front.include.global_alert')

<!-- Start Team Area -->

@php
$teachers_random = $teachers->shuffle();
@endphp


<section class="team-area pt-100 pb-70">
    <div class="container">
        <div class="row">
            @foreach($teachers_random as $teacher)
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="single-instructor-member mb-30">
                        <a href="{{route('teachers.show', ['name' => $teacher->alias] )}}">
                            <div class="member-image" style="max-height: 350px;">
                                <img src="{{ url($teacher->LogoPath) }} " style="width: 100%;height: 350px;" alt="{{$teacher->title}}">
                            </div>
                        </a>
                        <div class="member-content">
                            <h3><a href="{{route('teachers.show', ['name' => $teacher->alias] )}}">{{$teacher->title}}</a></h3>
                            <span class="sec-color">{{__('core.app_name')}}</span>
                            {{--
                            <ul class="social">
                              <li>
                                  @for ($i = 0; $i < 5; $i++)
                                    <i class="fa fa-star{{ $teachers-> rated == $i + .5 ? '-half' : ''}}{{$teachers-> rated <= $i ? '-o' : ''}}" aria-hidden="true"></i>
                                  @endfor
                              </li>
                              <li>
                                  {{$teachers-> rated}} ({{$teachers-> number_rated}})
                              </li>
                            </ul>
                          --}}
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>

    <div id="particles-js-circle-bubble-3"></div>


    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="pagination-area text-center">
            {!! $teachers->render() !!}
        </div>
    </div>

</section>
<!-- End Team Area -->


@endsection
