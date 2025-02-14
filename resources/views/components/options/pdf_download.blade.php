@isset($mainTitle) {{ $mainTitle }} @endisset

@php
  if( substr( $item->value, 0, 4 ) === "http"){
    $file = $item->value;
  } else {
    $file= "download/pdf/".$item->value;
  }
@endphp

@php
  $pdf = str_replace('/uc?id=','/file/d/',$file);
  $pdf = str_replace(['/view?usp=sharing','&export','/preview'],'',$file);
  $pdf = str_replace(['/view?usp=drive_link','&export','/preview'],'',$file);
@endphp

<a target="_blank" href="{{ $pdf }}/view" download style="display: flex;font-size: 27px;padding: 15px 0px;">
  <img style="width: 20px;" src="
    @isset($item->option->icon)
        @if($item->option->icon != null) {{asset($item->option->icon)}}
        @else {{ asset('assets/images/default/icons/pdf-download.png') }}
        @endif
    @endisset
    " width="50" style="margin: 10px;" title="{{ isset($mainTitle) ? $mainTitle : '' }}" >
    <span>{{ isset($mainTitle) ? $mainTitle : '' }}</span>
</a>

<iframe src="{{ $pdf }}/preview" width="100%" height="500px"></iframe>
