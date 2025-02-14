<div class="form-group row">
  <label class="col-form-label col-lg-3 col-sm-12">{{ __('words.free_status') }} *</label>
  <div class="col-lg-4 col-md-9 col-sm-12">
    <select class="form-control kt-select2 {{ $errors->has('is_free') ? ' is-invalid' : '' }}" id="kt_select2_3" name="is_free">
      <option {{ old('is_free' , isset($dataValue) ? $dataValue : null ) == 1 ? 'selected' : '' }} value="1">{{ __('words.free') }}</option>
      <option {{ old('is_free' , isset($dataValue) ? $dataValue : null ) == 0 ? 'selected' : '' }} value="0">{{ __('words.not_free') }}</option>
    </select>
    <!-- <span class="form-text text-muted">Please enter your full name</span> -->
    @if ($errors->has('is_free'))
        <span class="invalid-feedback">{{ $errors->first('is_free') }}</span>
    @endif
  </div>
</div>
