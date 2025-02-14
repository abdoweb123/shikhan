@php $languages = isset($languages) ? $languages : getLanguages() ; @endphp

<select name="locale" required class="form-control {{ $errors->has('locale') ? ' is-invalid ' : '' }}" style="width: 100%;">
  @foreach ($languages as $language)
  <option {{ old('locale') == $language->alies ? 'selected' : '' }} value="{{ $language->alies }}">{{ $language->name }}</option>
  @endforeach
</select>
