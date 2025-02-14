
@php $currentFieldName = isset($fieldName) ? $fieldName : 'status_id'; @endphp
<select name="{{ $currentFieldName }}" {{ isset($isRequired) ? 'required' : '' }} class="form-control {{ $errors->has('status') ? ' is-invalid ' : '' }}" style="width: 100%;">
  @if (isset($addSelectOption))
    <option {{ old($currentFieldName) == 0 ? 'selected' : '' }} value=""></option>
  @endif

  @foreach($statuses as $status)
  <option {{ old($currentFieldName, isset($dataValue) ? $dataValue : '') == $status->id ? "selected" : '' }} value="{{ $status->id }}">{{ $status->getTitle() }}</option>
  @endforeach
</select>
