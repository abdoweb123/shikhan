{{-- Storage::url('support/get_cirt_diplom.mp4') --}}

@php $modalContent = null; @endphp

@switch(app()->getLocale())
    @case('ar')
        @php $modalContent = 'https://www.youtube.com/watch?v=V0E1iKegvx0'; @endphp
        @break
    @case('ha')
        @php $modalContent = 'https://www.youtube.com/watch?v=V0E1iKegvx0'; @endphp
        @break
@endswitch


@if ($modalContent)
  @include('front.include.support_videos.support_video', [
    'modalId' => 2,
    'modalContent' => 'https://www.youtube.com/watch?v=V0E1iKegvx0',
    'type' => 'video',
    'title' =>   __('trans.get_site_certificate')
  ])
@endif
