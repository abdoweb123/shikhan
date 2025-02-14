<select name="course_id" required class="form-control select2 {{ $errors->has('course_id') ? ' is-invalid ' : '' }}" style="width: 100%;">
  @foreach ( $courses as $course)
  <option {{ old('course_id', isset($dataValue) ? $dataValue : '') == $course->id ? 'selected' : '' }} value="{{ $course->id }}">{{ $course->title }}</option>
  @endforeach
</select>
