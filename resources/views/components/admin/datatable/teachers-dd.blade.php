<select name="teacher_id" required class="form-control select2 {{ $errors->has('teacher_id') ? ' is-invalid ' : '' }}" style="width: 100%;">
  @foreach ( $teachers as $teacher)
  <option {{ old('teacher_id', isset($dataValue) ? $dataValue : '') == $teacher->id ? 'selected' : '' }} value="{{ $teacher->id }}">{{ $teacher->title }}</option>
  @endforeach
</select>
