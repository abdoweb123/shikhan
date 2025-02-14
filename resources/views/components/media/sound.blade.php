<!-- 8 sound -->
@php $url_sound=""; @endphp

@if ( str_contains($value, 'google') )
    @php $value = explode('id=',$value);
      $value_split = $value[1];
    @endphp
    <audio controls="controls" class="col-lg-12 col-md-12 col-sm-12 col-12">
      @php $url_sound="https://docs.google.com/uc?export=open&id=$value_split";@endphp
      <source src="https://docs.google.com/uc?export=open&id={{$value_split}}">
    </audio>
@else
  @php
    if( substr( $value, 0, 4 ) === "https"){
      $url_sound = $value;
    } else {
      $url_sound = Storage::url($value);
    }
  @endphp
    <audio controls="controls" class="col-lg-12 col-md-12 col-sm-12 col-12">
        <source src={{$url_sound}}>
    </audio>
@endif
