<!-- 3- pdf download -->

@php
  $type= explode(" ", $item->options->titleGeneral);
  if( substr( $item->value, 0, 4 ) === "http"){
    $file = $item->value;
  }
  else{
    $file= "download/pdf/".$item->value;}
@endphp
<a target="_blank" href="{{asset($file)}}" style="display: flex;font-size: 27px;padding: 15px 0px;">
  <img src="
    @if($item->options->icon != null)
      {{asset($item->options->icon)}}
    @else
      {{asset('images/default.jpg')}}
    @endif
    " width="50" style="margin: 10px;" title="{{ $item->options->option_info->first()->title }}" >
<span>{{-- $item->options->option_info->first()->title --}} {{ $post->title }}</span>
</a>

@php
$pdf = str_replace('/uc?id=','/file/d/',$file);
$pdf= str_replace(['/view?usp=sharing','&export','/preview'],'',$file);
@endphp

<iframe src="{{ $pdf }}/preview" width="100%" height="500px"></iframe>
