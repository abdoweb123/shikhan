<nav aria-label="breadcrumb" role="navigation">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('index') }}"> @lang('meta.title.home') </a></li>
        @foreach ($routes as $route)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page"> {{ $route['name'] }} </li>
            @else
                <li class="breadcrumb-item"><a href="{{ $route['slug'] }}"> {{ $route['name'] }} </a></li>
            @endif
        @endforeach
    </ol>
</nav>
