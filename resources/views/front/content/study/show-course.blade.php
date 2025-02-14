@extends('front.layouts.the-index')

@section('content')


<div class="container">
  <x-admin.datatable.page-alert />
</div>

<div class="container-fluid">
  <div class="row" style="margin: 30px;">
    <div class="col-lg-4 list text-left">

        <div>
          <h2 style="padding-bottom: 15px;font-weight: normal;">
              {{ __('domain.track')}}  {{ $data['course']->title }}
          </h2>
        </div>
        @forelse($data['course_track'] as $courseTrack)
            @isset($courseTrack->courseable)

                @if($courseTrack->courseable_type == 'lessons')
                    <a onclick="ajaxLink(event,this,'div_lesson_content','div_lesson_content_error','');" class="d-block mb-2"
                       data-href="{{
                        route('courseTrack.getCourseTrackLesson', [
                         'course' => $data['course']->id,
                         'lesson_id' => $courseTrack->courseable->id,
                       ])}}" style="cursor:pointer">
                        {{$courseTrack->courseable->translation->where('locale',app()->getLocale())->first()->title?? $courseTrack->courseable->title_general}}
                    </a>
                @elseif($courseTrack->courseable_type == 'tests')
                    <a onclick="ajaxLink(event,this,'div_lesson_content','div_lesson_content_error','');" class="d-block mb-2"
                       data-href="{{
                        route('courseTrack.getCourseTrackTest', [
                         'course' => $data['course']->id,
                         'test_id' => $courseTrack->courseable->id,
                       ])}}" style="cursor:pointer">
                        {{$courseTrack->courseable->translation->where('locale',app()->getLocale())->first()->title?? $courseTrack->courseable->name}}

                    </a>
                @endif

            @endisset
        @empty
        @endforelse

    </div>

    <div class="col-lg-8" id="other_side_show_data">
      <div class="section-heading">

        <div id="div_lesson_content_error"></div>

        <div id="div_lesson_content">

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
