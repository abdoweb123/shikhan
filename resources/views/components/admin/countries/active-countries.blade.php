
<label class="col-form-label col-lg-3 col-sm-12">الدولة</label>
<div class="col-lg-4 col-md-9 col-sm-12">
  <select class="form-control select_2 {{ $errors->has('country') ? ' is-invalid' : '' }}" name="country">
    @foreach ($countries as $key => $country)
      <option {{ old('country' , isset($dataValue) ? $dataValue : null ) == $country->id ? "selected" : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
    @endforeach
  </select>
  <!-- <span class="form-text text-muted">Please enter your full name</span> -->
  @if ($errors->has('country'))
      <span class="invalid-feedback">{{ $errors->first('country') }}</span>
  @endif
</div>
