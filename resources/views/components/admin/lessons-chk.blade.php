
<div class="form-group {{ $errors->has('lesson_ids') ? ' is-invalid ' : '' }}">
    @foreach ($lessons as $lesson)
    <div class="form-check" style="width: 100%;">
      <input id="lesson-{{$lesson->id}}" class="form-check-input" name="lesson_ids[]" value="{{ $lesson->id }}" type="checkbox"
      {{
          in_array( $lesson->id , old('lesson_ids' , isset($dataValues) ? json_decode($dataValues, true) : []) )
          ? 'checked' : ''
      }}
      >
      <label for="lesson-{{$lesson->id}}" class="form-check-label">{{ $lesson->title }}</label>
    </div>
    @endforeach
</div>
