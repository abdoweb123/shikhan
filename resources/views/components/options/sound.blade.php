<!-- 8 sound -->
@php $url_sound=""; @endphp

@isset($main_title) {{ $main_title }} @endisset

@if ( strpos($item->value, 'drive.google') )
  @php
    $item->value = str_replace('/view?usp=sharing','/preview',$item->value);
  @endphp
  <div style="width: 100%">
    <iframe src="{{$item->value}}" width="100%" height="450px" allow="autoplay"></iframe>
  </div>
@endif


{{--
@if ( strpos($item->value, 'google') )
      @if (strpos($item->value, 'google.com/uc?id='))
          @php $value= explode('id=',$item->value);
              $value_split=$value[1];
          @endphp
          <div class="  sound-div">
            <audio controls>
                @php  $url_sound="https://docs.google.com/uc?export=open&id=$value_split";@endphp
                <source src="https://docs.google.com/uc?export=open&id={{$value_split}}">
            </audio>
          </div>
      @else

          @php
            $item->value = str_replace('/file/d/','/uc?id=',$item->value);
            $item->value = str_replace('/view?usp=sharing','&export',$item->value);
          @endphp

          <div class="  sound-div">
            <audio controls>
                <source src="{{$item->value}}">
            </audio>
          </div>

      @endif
@else
    <div class="  sound-div" >
      <audio controls>
          <source src="{{ asset('storage/app/public/'.$item->value) }}">
      </audio>
    </div>
@endif
--}}





{{--
@if ( str_contains($item->value, 'google') )

    @php $value = explode('/d/',$item->value);
      $value_split = $value[1];
    @endphp

    <audio controls="controls" class="col-lg-12 col-md-12 col-sm-12 col-12">

      @php $url_sound="https://docs.google.com/uc?export=open&id=$value_split";@endphp
      <source src="https://docs.google.com/uc?export=open&id={{$value_split}}">
    </audio>
@else
    <audio controls="controls" class="col-lg-12 col-md-12 col-sm-12 col-12">
        @php $url_sound=$item->value; @endphp
        <source src={{$item->value}}>
    </audio>
@endif
--}}
