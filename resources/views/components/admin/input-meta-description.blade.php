<label class="col-sm-2 col-form-label">{{ __('general.meta_description') }}</label>
<div class="col-sm-10">
<textarea name="meta_description" maxlength="500" rows="2"  class="form-control {{ $errors->has('meta_description') ? ' is-invalid ' : '' }}">{{ old('meta_description', isset($dataValue) ? $dataValue : '') }}</textarea>
</div>
