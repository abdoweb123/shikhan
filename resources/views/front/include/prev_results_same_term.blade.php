@if ( $prevResultsSameTerm->isnotEmpty() )
  @foreach ($prevResultsSameTerm as $previousTest)
  <div class="col-lg-3 col-12">
    <div class="{{ $previousTest->degree >= pointOfSuccess() ? 'alert alert-success' : 'alert alert-danger' }}" role="alert">
            {{ $previousTest->term?->site?->name }}
        : <span style="font-size: 18px; font-weight: bold;">{{  $previousTest->degree }} % </span>
        <div >{{ $previousTest->created_at->format('Y-m-d') }}</div>
    </div>
  </div>
  @endforeach
@endif
