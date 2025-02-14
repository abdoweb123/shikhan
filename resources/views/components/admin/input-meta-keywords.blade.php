<label class="col-sm-2 col-form-label">{{ __('general.meta_keywords') }}</label>
<div class="col-sm-10">
<textarea name="meta_keywords" maxlength="500" rows="2" class="form-control {{ $errors->has('meta_keywords') ? ' is-invalid ' : '' }}">{{ old('meta_keywords', isset($dataValue) ? $dataValue : '') }}</textarea>
</div>
