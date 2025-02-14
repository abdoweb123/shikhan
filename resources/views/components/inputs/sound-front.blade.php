@if ( strpos($file, 'google') ) {{-- google path --}}
    @php $value= explode('id=',$file);
        $value_split=$value[1];
    @endphp
    <div style="display: none;">
      <audio id="{{ $currentId }}">
          @php    $url_sound="https://docs.google.com/uc?export=open&id=$value_split";@endphp
          <source src="https://docs.google.com/uc?export=open&id={{$value_split}}">
      </audio>
    </div>
@else    {{-- real path --}}
    <div style="display: none;">
      <audio id="{{ $currentId }}">
          <source src={{ $file }}>
      </audio>
    </div>
@endif
