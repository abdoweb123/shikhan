
<label class="col-sm-2 col-form-label">{{ __('general.meta_header') }}</label>
<div class="col-sm-10">
<textarea name="header" maxlength="300" rows="2" class="form-control {{ $errors->has('header') ? ' is-invalid ' : '' }}">{{ old('header', isset($dataValue) ? $dataValue : '') }}</textarea>
</div>
