{{--
@extends ("emails.layout.master")
@section ("title")
    <b style="font-size:50px;">{{ $settings['title'] }}</b>
@stop
@section ("body")
    <br>email : {{ $settings['title'] }}
@stop
--}}

<html>
<body>

  @if (isset($settings['message']))
    {!! $settings['message'] !!}
  @else
    In The Name Of Gode
    BaldaTaiyba
  @endif

{{--
<table><tr><td>
      <img style="width:500px;height:400px;" src="{{ $message->embed('https://www.baldatayiba.com/storage/courses/cwEluKxgobqhOlNdAKOqCrUMx8e0U5UXfVdPzc1F.jpeg') }}">
</td></tr></table>
<a href="https://www.baldatayiba.com/storage/courses/cwEluKxgobqhOlNdAKOqCrUMx8e0U5UXfVdPzc1F.jpeg" download rel="noopener noreferrer" target="_blank">
download image
</a>
<img style="width:500px;height:400px;" src="{{ $message->embed('https://www.baldatayiba.com/storage/courses/cwEluKxgobqhOlNdAKOqCrUMx8e0U5UXfVdPzc1F.jpeg') }}">
--}}

</body>
</html>
