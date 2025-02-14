<a href="">
  <img style="width: 20px;" src="@if( $item->option->icon != null )
        {{ asset( $item->option->icon )}}
      @else
        {{ asset('assets/images/default/icons/pdf-download.png') }}
       @endif"
    width="50" title="{{ isset($mainTitle) ? $mainTitle : '' }}" >
</a>
<h6>{{ isset($mainTitle) ? $mainTitle : '' }}</h6>
