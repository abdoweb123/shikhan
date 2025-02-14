<!-- 7- doc read -->
<a href="">
  <img src="@if( $item->options->icon != null ){{ asset( $item->options->icon )}} @else {{ asset('images/default.jpg') }} @endif" width="50" title="{{$options["relations"]["options"]["relations"]["option_info"][0]["title"]}}" ></a>
<h6>{{ $item->options->option_info->first()->title }}</h6>
