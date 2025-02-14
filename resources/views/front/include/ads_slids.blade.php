
@if(Auth::check() && Auth::id() == 2)

<style>
  #ads_slideshow {
    position: relative;
    width: 100%;
    height: 165px;
    text-align: center;
  }

  #ads_slideshow > div {
    position: absolute;
    top: 10px;
    left: 10px;
    right: 10px;
    bottom: 10px;
  }
</style>

<div id="ads_slideshow">
  @foreach($ads_data as $adv)
  <div>
    <a href="{{ $adv['link'] }}"><img src="{{ $adv['img'] }}"></a>
  </div>
  @endforeach
</div>


@endif
