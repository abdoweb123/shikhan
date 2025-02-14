@php
    $slider = slider();
@endphp
<div class="gallery">
	<div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel" style="max-width:800px;">
		<ol class="carousel-indicators text-center pull-left">
			@foreach($slider as $k => $v)
				<li data-target="#carouselExampleIndicators" data-slide-to="{{ $k }}" {{ $loop->first ? 'class=active' : '' }}></li>
			@endforeach
		</ol>
		<div class="carousel-inner">
			@foreach($slider as $k => $v)
				<div class="carousel-item {{ $loop->first ? 'active' : '' }}">
					<img class="d-block w-100" src="{{ url(Storage::url('slider/'.$v['image'])) }}" alt="First slide">
					<div class="carousel-caption d-none d-md-block">
						<div style="text-shadow: 1px 1px 2px #000, 0px 0px 2px #000;color: cornflowerblue;"> {!! $v['description'] !!} </div>
						@if($v['url'])
							<a href="{{ $v['url'] }}" class="btn btn-default"><span>{{ $v['name'] }}</span><i class="icon-arrow-down icon-size-m icon-position-right"></i></a>
						@endif
					</div>
				</div>
			@endforeach
		</div>
		<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only"><{{ __('core.previous') }}</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only"><{{ __('core.next') }}</span>
		</a>
	</div>
</div>
