<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">
          {{ $title }}
          @isset($add_route)
            <a href="{{ $add_route }}" class="btn btn-block btn-outline-primary">+</a>
          @endisset
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6 text-right">
        <ol class="breadcrumb float-sm-right">
{{--          <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">{{ __('general.home') }}</a></li>--}}
          <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{ __('general.home') }}</a></li>
          @foreach ($routes as $route)
              @if (isset($route['header_route']))
                <li class="breadcrumb-item"><a href="{{ $route['header_route'] }}"> {{ $route['header_name'] }} </a></li>
              @else
                <li class="breadcrumb-item active"> {{ $route['header_name'] }}</li>
              @endif
          @endforeach
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
