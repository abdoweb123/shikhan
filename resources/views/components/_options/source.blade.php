<!-- 10 source -->
{{$source['title']}}:
<span>
   @foreach($post_data['relations']['options'] as $index => $view)
        @if($view->option_id == 10)
            @if(strpos($view->value, '.com') !== false)
                @php $source=explode("com",$view->value)@endphp
                {{$source[0]."com"}}
            @elseif(strpos($view->value, '.net') !== false)
                @php $source=explode("net",$view->value)@endphp
                {{$source[0]."net"}}
            @else
                {{$view->value}}
            @endif
        @endif
    @endforeach
  </span>
