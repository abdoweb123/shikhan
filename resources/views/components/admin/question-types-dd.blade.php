<select id="question_types" name="question_type_id" class="form-control">
  @foreach ( $questionsTypes as $key => $type)
    <option value="{{ $key }}">{{ $type }}</option>
  @endforeach
</select>
