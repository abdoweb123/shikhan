<select name="lesson_study_type_id" required class="form-control {{ $errors->has('lesson_study_type_id') ? ' is-invalid ' : '' }}" style="width: 100%;">
  @foreach( $lessonStudyTypes as $lessonStudyType)
    <option {{ old('lesson_study_type_id', isset($dataValue) ? $dataValue : '') == $lessonStudyType->id ? 'selected' : '' }} value="{{ $lessonStudyType->id }}">{{ $lessonStudyType->getTitle() }}</option>
  @endforeach
</select>
