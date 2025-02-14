
  <label class="col-form-label col-lg-3 col-sm-12">ينتمى الى</label>
  <div class="col-lg-4 col-md-9 col-sm-12">
    <select class="form-control {{ $errors->has('parent_id') ? ' is-invalid' : '' }}" name="parent_id" required>
      <option {{ old('parent_id') == 0 ? "selected" : '' }} value="0">تصنيف رئيسي</option>
      @foreach ($tree as $item)
        <option {{ old('parent_id' , isset($dataValue) ? $dataValue : null ) == $item->id ? "selected" : '' }} value="{{ $item->id }}">{{ str_repeat("....", $item->depth) }} {{ $item->name }}</option>
      @endforeach
    </select>
    <!-- <span class="form-text text-muted">Please enter your full name</span> -->
    @if ($errors->has('parent_id'))
        <span class="invalid-feedback">{{ $errors->first('parent_id') }}</span>
    @endif
  </div>
