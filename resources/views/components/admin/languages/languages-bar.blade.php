
<div class="col-lg-12">

    @php $parameters = request()->route()->parameters(); @endphp

    @foreach (getActiveLanguages() as $language)
      <a href="{{ route(request()->route()->getName(), $parameters + ['language' => $language->alies] )}}"
        class="{{ (request()->query('language') == $language->alies) ? 'btn btn-info' : 'btn btn-sm btn-warning' }}">
        {{ $language->name }}
      </a>
    @endforeach

</div>
