<select name="test_type_id" required class="form-control {{ $errors->has('test_type_id') ? ' is-invalid ' : '' }}" style="width: 100%;">
  @foreach( $testTypes as $testType)
    <option {{ old('test_type_id', isset($dataValue) ? $dataValue : '') == $testType->id ? 'selected' : '' }} value="{{ $testType->id }}">{{ $testType->getTitle() }}</option>
  @endforeach
</select>
