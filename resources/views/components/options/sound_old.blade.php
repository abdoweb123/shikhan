
@php $url_sound=""; @endphp

@isset($mainTitle) {{ $mainTitle }} @endisset

@if ( strpos($item->value, 'google') ) {{-- google path --}}
    <a href="{{ $item->value }}" style="font-size: 22px;" target="_blank"><i class="fas fa-volume-up" style="padding: 0px 5px;"></i><span>{{ __('domain.sound_link')}}</span></a>
    {{--
    @if (strpos($item->value, 'google.com/uc?id='))
        @php
          $value= explode('id=',$item->value);
          $value_split=$value[1];
        @endphp

        <div class="sound-div" style="width: 100%;">
          <audio controls>
              @php  $url_sound="https://docs.google.com/uc?export=open&id=$value_split";@endphp
              <source src="https://docs.google.com/uc?export=open&id={{$value_split}}">
          </audio>
        </div>
    @else
        <!-- https://drive.google.com/file/d/1ktAGJC-TOCTQ2eRtsKdRnJXyzJbBOhPI/view?usp=sharing -->
        <!-- https://drive.google.com/file/d/15PDMl6GGYbOLDTktMaG2zto-YmcRCf4Z/view?usp=drive_link -->
        @php
          $item->value = str_replace('/file/d/','/uc?id=',$item->value);
          $item->value = str_replace('/view?usp=sharing','&export',$item->value);
          $item->value = str_replace('/view?usp=drive_link','&export',$item->value);
        @endphp

          <div class="  sound-div" style="width: 100%;">
            <audio controls>
                <source src="{{$item->value}}">
            </audio>
          </div>

      @endif
      --}}
@else    {{-- real path --}}
    <div class="sound-div" style="width: 100%;">
      <audio controls>
          <source src="{{ asset('storage/app/public/'.$item->value) }}">
      </audio>
    </div>
@endif
