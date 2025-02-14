@if ( strpos($file, 'google') ) {{-- google path --}}
    @php $value= explode('id=',$file);
        $value_split=$value[1];
    @endphp
    <audio controls="controls" class="a-tran">
        @php    $url_sound="https://docs.google.com/uc?export=open&id=$value_split";@endphp
        <source src="https://docs.google.com/uc?export=open&id={{$value_split}}">
    </audio>
@else    {{-- real path --}}
    <audio controls="controls" class="a-tran">
        <source src={{ $file }}>
    </audio>
@endif
