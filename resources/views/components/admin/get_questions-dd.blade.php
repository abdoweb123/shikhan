<select name="get_questions" required class="form-control {{ $errors->has('get_questions') ? ' is-invalid ' : '' }}" style="width: 100%;">
  @foreach( $getQuestions as $getQuestion)
  <option {{ old('get_questions', isset($dataValue) ? $dataValue : '') == $getQuestion->id ? "selected" : '' }} value="{{ $getQuestion->id }}">{{ $getQuestion->getTitle() }}</option>
  @endforeach
</select>
