

  <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.active_status') }} *</label>
  <div class="col-lg-4 col-md-9 col-sm-12">
    <select class="form-control {{ $errors->has('is_active') ? ' is-invalid' : '' }}" name="is_active">
      <option {{ old('status' , isset($dataValue) ? $dataValue : null ) == 1 ? "selected" : '' }} value="1">{{ __('words.active') }}</option>
      <option {{ old('status' , isset($dataValue) ? $dataValue : null ) == 0 ? "selected" : '' }} value="0">{{ __('words.not_active') }}</option>
    </select>
    <!-- <span class="form-text text-muted">Please enter your full name</span> -->
    @if ($errors->has('status'))
        <span class="invalid-feedback">{{ $errors->first('status') }}</span>
    @endif
  </div>
