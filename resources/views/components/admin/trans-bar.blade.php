
@foreach ( $languages as $language )
<form class="kt-form" enctype="multipart/form-data" action="{{ $route }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name="translate" value="{{ $language->locale }}">
  <button type="submit"
    @if ($language->locale == $translate)
        class="btn btn-outline-dark"
    @else
        class="btn btn-outline-secondary"
    @endif
    >{{ $language->title }}
  </button>
</form>
@endforeach
