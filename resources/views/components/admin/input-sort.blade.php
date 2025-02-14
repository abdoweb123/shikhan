
<label class="col-sm-2 col-form-label">{{ __('general.sort') }}</label>
<div class="col-sm-10">
<input type="text" name="sort" maxlength="5" value="{{ old('sort', isset($dataValue) ? $dataValue : '' ) }}"  class="form-control {{ $errors->has('sort') ? ' is-invalid ' : '' }}" placeholder="">
</div>
