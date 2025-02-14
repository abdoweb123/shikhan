@extends('front.layouts.the-index')
@section('content')

<section class="bg-img bg-overlay-2by5 inner_banner" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );">
    <div class="container h-100">
        <div class="row h-100 align-items-center" style="text-align: center;color: white;">
          <div class="col-12 mt-5">
            @if (session()->has('degree'))
              <span class="inner_page_title">{{ __('core.valid_quiz_count' , ['degree'=>session('degree')]) }}</span>
              <h1 class="inner_page_title" style="font-size: 60px;">
                (%{{ session('degree')  }}){{__('words.rate_is')}}: {{ __('trans.rate.'.session('rate'))  }}
              </h1>
              @if(session()->has('attende')) <h3 class="inner_page_title">{{ __('trans.after_zoom_points')}}</h3>@endif
              <a href="{{ route('sites_certificates') }}" class="btn btn-success" style="margin: 10px;">{{ __('trans.download_certificate') }}</a>
            @endif

            @if(session('no_test') > 1)
              @if (session()->has('course_test_result_id'))
                <a href="{{ route('quiz_answers', ['id' => session('course_test_result_id') ]) }}" class="btn btn-success" style="margin: 10px;">{{ __('trans.view_score') }}</a>
              @endif
            @endif

          </div>
        </div>

    </div>
</section>

<!-- ron correctly but we will not dispaly it now -->

<div class="container pt-3">
    <div class="row">






    </div>
</div>





@endsection
