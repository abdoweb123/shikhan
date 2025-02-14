@extends('front.layouts.new')
@section('content')

<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 350px auto;">
    <div class="container h-100">
        <div class="row h-100 align-items-center" style="text-align: center;color: white;">
          <div class="col-12 mt-5" style="margin-top: 190px !important;">

              {{ __('core.valid_quiz_count' , ['degree'=> $courseTestResult->degree]) }}
              <h1 style="color: white; font-size: 70px;">(%{{ $courseTestResult->degree  }}){{__('words.rate_is')}}: {{ __('trans.rate.'.$courseTestResult->rate )  }}</h1>
              <a href="{{ route('certificates') }}" class="btn btn-success" style="margin: 10px;">{{ __('trans.download_certificate') }}</a>

          </div>
        </div>

    </div>
</section>

<!-- ron correctly but we will not dispaly it now -->

<div class="container pt-3">
    <div class="row">

            @foreach ($userTestResultAnswers as $answers)
              

              @php
                $correctAnswer = \Illuminate\Support\Str::between( $answers->first()->correct_answer , ':', '}');
                $correctAnswer = str_replace('"', "", $correctAnswer);
              @endphp

              <div class="col-md-6" style="padding-bottom: 20px;">
                <div class="card bg-light" style="border: 1px solid #ead9a8;box-shadow: 0 3px 20px rgba(0, 0, 0, 0.15);padding: 5px 5px;border-radius: 14px;">
                  <div class="card-body" style="@if (LaravelLocalization::getCurrentLocaleDirection() =='rtl') text-align: right; @endif ">
                      <h4 class="card-title text-dark" style="font-size: 17px;line-height: 2;"> {{$loop->iteration}} - {{ $answers->first()->question_title }} </h4>
                      <div class="" style="margin-right: 25px;">
                        <div class="form-check">

                          @foreach ($answers as $answer)
                            <!-- dont mark the correct answer to the user -->
                            {{--<!-- <div style="@if($correctAnswer == $answer->course_answer_id) color: green; @else color: black; @endif "> -->--}}
                            <div style="color: black;">
                              <div style="padding: 3px;">
                                {{ $answer->answers_title }}
                                @if( $correctAnswer == $answer->user_answer_id && $answer->course_answer_id == $answer->user_answer_id)
                                  <i class="fas fa-check" style="color: green;"></i>
                                @endif
                                @if( $answer->course_answer_id == $answer->user_answer_id && $correctAnswer != $answer->user_answer_id)
                                  <i class="fas fa-times" style="color: red;"></i>
                                @endif
                              </div>
                            </div>
                          @endforeach
                        </div>

                      </div>
                  </div>
                </div>
              </div>

            @endforeach

    </div>
</div>





@endsection
