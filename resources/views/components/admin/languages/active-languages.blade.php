
  <label class="col-form-label col-lg-3 col-sm-12">اللغة</label>
  <div class="col-lg-4 col-md-9 col-sm-12">
    <select class="form-control {{ $errors->has('language') ? ' is-invalid' : '' }}" name="language">
      @foreach (getActiveLanguages() as $language)
        <option {{ old('language' , isset($dataValue) ? $dataValue : null ) == $language->id ? "selected" : '' }} value="{{ $language->alies }}">{{ $language->name }}</option>
      @endforeach
    </select>
    <!-- <span class="form-text text-muted">Please enter your full name</span> -->
    @if ($errors->has('language'))
        <span class="invalid-feedback">{{ $errors->first('language') }}</span>
    @endif
  </div>
