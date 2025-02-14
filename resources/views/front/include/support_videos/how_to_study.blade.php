{{-- Storage::url('support/study_test.mp4') --}}

@php $modalContent = null; @endphp

@switch(app()->getLocale())
    @case('ar')
        @php $modalContent = 'https://www.youtube.com/watch?v=YFSmQ11kG5s'; @endphp
        @break
    {{-- @case('ha')
        @php $modalContent = 'https://www.youtube.com/watch?v=YFSmQ11kG5s'; @endphp
        @break --}}
@endswitch


@if ($modalContent)
  @include('front.include.support_videos.support_video', [
    'modalId' => 4,
    'modalContent' => 'https://www.youtube.com/watch?v=YFSmQ11kG5s',
    'type' => 'video',
    'title' =>  __('trans.how_to_study')
  ])
@endif
