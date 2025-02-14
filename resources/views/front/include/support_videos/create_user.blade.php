{{-- Storage::url('support/register_new_student.mp4') --}}

@php $modalContent = null; @endphp

@switch(app()->getLocale())
    @case('ar')
        @php $modalContent = 'https://www.youtube.com/watch?v=144n3-mVeps'; @endphp
        @break
    {{--@case('ha')
        @php $modalContent = 'https://www.youtube.com/watch?v=144n3-mVeps'; @endphp
        @break
        --}}
@endswitch


@if ($modalContent)
  @include('front.include.support_videos.support_video', [
    'modalId' => 4,
    'modalContent' => '',
    'type' => 'video',
    'title' =>  'طريقة التسجيل كطالب جديد'
  ])
@endif
