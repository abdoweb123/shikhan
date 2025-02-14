{{-- Storage::url('support/register_new_student.mp4') --}}

@php $modalContent = null; @endphp

@switch(app()->getLocale())
    @case('sw')
      @if (isset($direct))
        <iframe style="height: 320px;width: 480px auto;" class="embed-responsive-item" src="https://www.youtube.com/embed/NbNufM7pWGY" title="YouTube video player" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      @else
        @php $modalContent = 'https://www.youtube.com/embed/NbNufM7pWGY'; @endphp
      @endif
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
