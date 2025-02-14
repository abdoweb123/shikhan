{{-- Storage::url('support/get_cirt_diplom.mp4') --}}

@php $modalContent = null; @endphp

@switch(app()->getLocale())
    @case('ar')
        @php $modalContent = 'https://www.youtube.com/watch?v=jRo_wR7_QCo'; @endphp
        @break
    @case('ha')
        @php $modalContent = 'https://www.youtube.com/watch?v=jRo_wR7_QCo'; @endphp
        @break
@endswitch


@if ($modalContent)
  @include('front.include.support_videos.support_video', [
    'modalId' => 1,
    'modalContent' => 'https://www.youtube.com/watch?v=jRo_wR7_QCo',
    'type' => 'video',
    'title' => __('trans.get_course_certificate')
  ])
@endif
