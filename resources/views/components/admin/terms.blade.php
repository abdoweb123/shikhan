
  <label class="col-form-label col-lg-3 col-sm-12">الفصل الدراسى</label>
  <div class="col-lg-4 col-md-9 col-sm-12">
    <select class="form-control {{ $errors->has('term_id') ? ' is-invalid' : '' }}" name="term_id" required>
      @foreach ($terms as $item)
        <option {{ old('term_id' , isset($dataValue) ? $dataValue : null ) == $item->id ? "selected" : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
      @endforeach
    </select>
    <!-- <span class="form-text text-muted">Please enter your full name</span> -->
    @if ($errors->has('term_id'))
        <span class="invalid-feedback">{{ $errors->first('term_id') }}</span>
    @endif
  </div>
