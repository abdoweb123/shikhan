@if ( $prevResultsSameCourse->isnotEmpty() )
  <div class="alert alert-primary" style="background-color: white; border: none;" role="alert">{{ __('trans.previous_tests_results') }}</div>
  @foreach ($prevResultsSameCourse as $previousTest)
    <div class="{{ $previousTest->degree >= pointOfSuccess() ? 'alert alert-success' : 'alert alert-danger' }}" role="alert">
        @if ($previousTest->course)
          @if ($previousTest->course->sites->isNotEmpty())
            {{ $previousTest->course->sites->first()->name }}
          @endif
        @endif
        : <span style="font-size: 18px; font-weight: bold;">{{  $previousTest->degree }} % </span>
        <div >{{ $previousTest->created_at->format('Y-m-d') }}</div>
    </div>
  @endforeach
@endif
