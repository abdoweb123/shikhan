@extends('front.layouts.the-index')

@section('content')

{{--<div class="main-banner inner-banner" id="top">--}}

{{--</div>--}}


<div class="container">
  <x-admin.datatable.page-alert />
</div>

<div class="container-fluid">
  <div class="row" style="margin: 30px;">
    <div class="col-lg-4 list">

        <div>
          <h2 style="color: white;padding-bottom: 15px;font-weight: normal;">
{{--            {{ $enrolled->enrolled_terms->first()->enrolled_term_courses->first()?->course->title }}--}}
              {{ $data['course']->title }}
          </h2>
        </div>


        <div style="max-height: 330px;overflow-x: hidden;">
            @forelse($data['course_track'] as $courseTrack)
                @isset($courseTrack->courseable)
                    <div class="list-item">
                        <div class="row">
                            <!-- <img src="assets/images/testimonial-author.jpg" alt=""> -->
                            <div class="col-10">

                                @if ($courseTrack->courseable_type === 'lessons')
                                    <i class="fas fa-film details-item-icon" style="color: #2da69a;"></i>
                                @endif
                                @if ($courseTrack->courseable_type === 'tests')
                                    <i class="fas fa-tasks details-item-icon" style="color: #8e1944;"></i>
                                @endif


                            <!-- عرض درس -->
                                @if ($courseTrack->courseable_type === 'lessons')
                                    <a style="font-size: 18px;cursor: pointer;color: #0b7c82;" onclick="ajaxLink(event,this,'div_lesson_content','div_lesson_content_error','');"
{{--                                       data-href="{{--}}
{{--                                                  route('front.enrolls.courses.lessons.show', [--}}
{{--                                                    'enrolled' => $enrolled->id,--}}
{{--                                                    'course' => $data['course']->id,--}}
{{--                                                    'lesson' => $courseTrack->courseable->id--}}
{{--                                                  ]) }}" --}}
                                       style="color: black;">
                                        {{ $courseTrack->courseable->title_general }}
                                    </a>
                                @endif


                            <!-- عرض اختبار -->
                                @if ($courseTrack->courseable_type === 'tests')
                                    <button type="button" style="font-size: 18px;cursor: pointer;color: #8e1944;border:none;background: none;" data-bs-toggle="modal" data-bs-target="#div_test_{{$courseTrack->courseable->id}}">
                                        {{ $courseTrack->courseable->name }} {{-- $courseTrack->courseable->percentage --}}
                                    </button>

                                    <div class="modal fade" id="div_test_{{$courseTrack->courseable->id}}" tabindex="-1" aria-labelledby="div_test_{{$courseTrack->courseable->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <!-- <h5 class="modal-title" id="div_test_{{$courseTrack->courseable->id}}Label">Modal title</h5> -->
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body" style="text-align: center;color: #9f0808;">
                                                    {!! __('domain.test_cant_repetead_open_now', ['var' => '(( ' . $courseTrack->courseable->title . ' ))' ]) !!}
                                                </div>
                                                <div class="modal-footer" style="text-align: center;justify-content: center;">
                                                    <a style="font-size: 18px;cursor: pointer;color: #9f0808;border: 1px solid #9f0808;border-radius: 50px;padding: 3px 29px;" data-bs-dismiss="modal" onclick="ajaxLink(event,this,'div_lesson_content','div_lesson_content_error','');"
{{--                                                       data-href="{{--}}
{{--                                                                route('front.enrolls.courses.tests.show', [--}}
{{--                                                                  'enrolled' => $enrolled->id,--}}
{{--                                                                  'course' => $data['course']->id,--}}
{{--                                                                  'test' => $courseTrack->courseable->id--}}
{{--                                                                ])}}" --}}
                                                       style="color: black;">
                                                        {{ __('general.agree') }}
                                                    </a>
                                                    <a style="font-size: 18px;cursor: pointer;color: #4f4f4f;border: 1px solid #777;border-radius: 50px;padding: 3px 29px;" data-bs-dismiss="modal">{{ __('general.reset') }}</a>
                                                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>


{{--                                    @include('front.content.study.show-test-results-content', [--}}
{{--                                        'student' => Auth::user(),--}}
{{--                                        'currentTest' => $courseTrack->courseable--}}
{{--                                    ])--}}
                                @endif

                            </div>




                            <div class="col-2" style="text-align: center;">
{{--                                @if ($seen)--}}
{{--                                    <i class="fas fa-eye" style="color: #79d7bc;font-size: 20px;"></i>--}}
{{--                                @else--}}
{{--                                    <i class="fas fa-eye-slash" style="color: #eeafcc;font-size: 20px;"></i>--}}
{{--                                @endif--}}
                            </div>


                            <span class="category"></span>

                        </div>
                        <p></p>
                    </div>
                @endisset
            @empty
            @endforelse
        </div>

    </div>

    <div class="col-lg-8">
      <div class="section-heading">
{{--        <h6>--}}
{{--          {{ $enrolled->faculty->title }} /--}}
{{--          {{ $enrolled->section->title }} /--}}
{{--          {{ $enrolled->certificate->title }} /--}}
{{--          {{ $enrolled->enrolled_terms->first()->term->title }}--}}
{{--        </h6>--}}
{{--        <div style="font-size: 18px;">--}}
{{--          @if ($enrolled->enrolled_research)--}}
{{--            <br>--}}
{{--            <span style="color: gray;">{{ __('domain.research_title') }} :</span> {{ $enrolled->enrolled_research->title }} /--}}
{{--            <span style="color: gray;">{{ __('domain.supervisor') }} :</span> {{ $enrolled->enrolled_research->teacher?->title }}--}}
{{--          @endif--}}
{{--        </div>--}}

        <div id="div_lesson_content_error"></div>

        <div id="div_lesson_content">
          <!-- <h2>اعلام درس 1</h2>
          <p>You can search free CSS templates on Google using different keywords such as templatemo portfolio, templatemo gallery, templatemo blue color, etc.</p> -->
        </div>

      </div>
    </div>
  </div>
</div>




@endsection


@push('js_pagelevel')
  <script>
    $('#reserve_extra_try_form').on('submit', function(event){
     event.preventDefault();

     var type=$(this).attr('method');
     var url=$(this).attr('action');

     formData = new FormData(this);
     var imageFile = $('#total_pay_image')[0].files[0];
     formData.append('total_pay_image', imageFile);

     $.ajax({
          url:url,
          method:type,
          data:formData,
          dataType:'JSON',
          contentType: false,
          cache: false,
          processData: false,
          success:function(data)
          {

              $("#reserve_extra_try_html_div_error").html('');

              if (data['htmlErrors']){
                  $("#reserve_extra_try_html_div_error").html(data['htmlErrors']);
              }

              if (data['redir']) {
                location.reload();
              }

          },
          error: function(response) {
              // console.log(response);
          }
     })
    });
  </script>
@endpush
