@extends('front.layouts.new')
@section('front-style')
<style>
    .video-div {
        margin: 2px;
        height: 300px;
    }
    .sound-div {
        margin-top: 15px;
    }
    .sound-div audio {
        width: 100%;
    }
    li.course-item-lp_lesson .index {
    display: inline-flex;
    width: 85%;
    }
    a.lesson-title {
        width: 85%;
        text-align: center;
        font-size: 16px;
        color: white;
    }
    a.lesson-title:hover {
        color: #5cc9df;
    }
    .card-img-overlay {
        position: absolute;
        top: 22px;
        right: auto;
        bottom: 0;
        left: 0;
        /* padding: 1.25rem; */
    }
    i.fa.fa-check-circle {
        color: #17a703 !important;
        font-size: 27px;
    }
    nav.thim-font-heading.learn-press-breadcrumb {
        box-shadow: 1px 0px 6px 2px #6c7a9fbf;
        background: #7684a8;
        color: white;
    }
    nav.thim-font-heading.learn-press-breadcrumb a  {
        color: white;
    }
    nav.thim-font-heading.learn-press-breadcrumb a:hover {
        color: #5cc9df;
    }
    .completed {
        margin: 5px;
    }
    .completed .btn-success:hover {
            color: #fff;
            background-color: #b5804dba;
            border-color: #a77646a8;
            box-shadow: 1px 2px 14px 1px #66390dc2;
        }

    .completed .btn-success,.completed .btn-success:not(:disabled):not(.disabled).active, .completed .btn-success:not(:disabled):not(.disabled):active, .show>.completed .btn-success.dropdown-toggle {
        color: #fff;
        background-color: #a36123;
        border-color: #582f08;
    }
    @media only screen and (max-width: 767px){
        section.hero-area.bg-img.bg-overlay-2by5 {
            height: 177px !important;
        }
    }
    .course-item-meta-live {
        font-size: 12px !important;
        width: 32%;
        font-weight: 600;
    }
</style>
@endsection
@section('content')
<!-- ##### Hero Area Start ##### -->
<section class="hero-area bg-img bg-overlay-2by5" style="background-color: #a0c9f5;height: 145px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center">

        </div>
    </div>
</section>
<!-- ##### Hero Area End ##### -->

    <div class="container p-0">

        <div class="row" style="
            margin-left: 0px;
            margin-right: 0px;
        ">
            <div class="text-center tiles col-md-9 ">

                <div class="row m-0 w-100">
                    <div class="col-md-3 card  text-white" style=" height: min-content;">
                        <h3 class=" card-title" style="text-align: center;color: #2064abcf;">
                            {{ $post->title }}
                        </h3>
                        <img class="card-img" src="{{ $post->logo_path }}" alt="{{ $post->title }}" rel="nofollow">
                            @if($post->iscompleted())
                                <div class="card-img-overlay">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                </div>

                            @else
                                <div class="completed">
                                   <a class="btn btn-success" href="{{route('complete_lesson', $post->id )}}" >{{ __('lesson.complete') }}</a>
                                </div>
                            @endif

                    </div>
                    <div class="col-md-9 ">
                        <div class="card video-div">
                            @if ($post->video)
    						    @if(str_replace('https://drive.google.com/uc?id=','https://drive.google.com/file/d/',$post->video))
    						        <iframe src="{{ str_replace('https://drive.google.com/uc?id=', 'https://drive.google.com/file/d/', $post->video ) }}/preview"  poster="{{$post->logo_path}}" width="100%" height="100%"></iframe>
    						    @else
    							   <iframe src="{{ asset('storage/app/public/'. $post->video)  }}"  poster="{{$post->logo_path}}" width="100%" height="100%"></iframe>
    						   @endif


						   @else
						   <img class="card-img h-100" src="{{ url(\Storage::url('/lessons/video.png'))}}" alt="{{ $post->title }}" rel="nofollow">

                            @endif
                        </div>


                        @if ( strpos($post->sound, 'google') ) {{-- google path --}}
                          @if (strpos($post->sound, 'id='))
                              @php $value= explode('id=',$post->sound);
                                  $value_split=$value[1];
                              @endphp

                              <div class="  sound-div">
                                <audio controls>
                                    @php    $url_sound="https://docs.google.com/uc?export=open&id=$value_split";@endphp
                                    <source src="https://docs.google.com/uc?export=open&id={{$value_split}}">
                                </audio>
                              </div>
                          @else
                            <div class="  sound-div">
                              <audio controls>

                                  <source src="{{$post->sound}}">
                              </audio>
                            </div>
                          @endif
                        @else    {{-- real path --}}
                            <div class="  sound-div" >
                              <audio controls>
                                  <source src="{{ asset('storage/app/public/'.$post->sound) }}">
                              </audio>
                            </div>
                        @endif


                    </div>
                </div>

                <div class="lesson-main  w-100">
        			<div class="lesson-top " style="text-align: right;">
            			<div class="courses-details-desc">
                            <div class="courses-accordion">
                                <ul class="accordion">
                                    @if( $post['html'] )
                                        @if( file_exists('storage/app/public/'.$post['html']) == true && file_get_contents('storage/app/public/'.$post['html']) != '' &&  file_get_contents('storage/app/public/'.$post['html']) != null )
                                            <li class="accordion-item">
                                                <a class="accordion-title active" href="javascript:void(0)">
                                                    <i class='bx bx-chevron-down'></i>
                                                    <span class="fa fa-file-text-o mr-1 ml-1"></span>
                                                     {{ __('lesson.html') }}
                                                </a>

                                                <div class="accordion-content " style="display: block;">
                                                    <ul class="courses-lessons">


                                                        <li class="single-lessons">

                                                            <div id="html" class="col-md-12 tab-pane "><br>
                                                                {!!  file_get_contents('storage/app/public/'.$post['html']) !!}
                                                            </div>

                                                        </li>


                                                    </ul>
                                                </div>
                                            </li>
                                        @endif
                                    @endif
                                    @if( $post->pdf )
                                        <li class="accordion-item">
                                            <a class="accordion-title active" href="javascript:void(0)">
                                                <i class='bx bx-chevron-down'></i>
                                                <span class="fa fa-file-pdf-o  mr-1 ml-1" aria-hidden="true" style="font-size: larger;color:red"></span>

                                                 {{ __('lesson.pdf') }}
                                            </a>

                                            <div class="accordion-content " style="display: block;">
                                                <ul class="courses-lessons">


                                                    <li class="single-lessons">


                                                         <iframe src="{{  $post->pdf }}" width="100%" height="500px"></iframe>


                                                    </li>


                                                </ul>
                                            </div>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center col-md-3" style="overflow-y: scroll;">
                @include('front.units.aside_lesson')
            </div>
        </div>
    </div>
@endsection
