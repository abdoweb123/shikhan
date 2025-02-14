@if ($errors->has($field))
  <p class="text-danger">{{ $errors->first($field) }}</p>
@endif
