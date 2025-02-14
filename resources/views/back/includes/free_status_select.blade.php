@php $name = isset($name) ? $name : 'free_status'; @endphp
<select name="{{ $name }}" class=" form-control col-md-12">
    @if (isset($select_none)) 
        <option {{ old($name) == 'all'  ? 'selected' : ''}} value="all">الكل</option>
    @endif
    @foreach($change_to_free_status ?? [] as $free_status)
        <option {{ old($name, isset($row) ? $row?->free_status : '') == $free_status['id'] ? 'selected' : '' }} value="{{ $free_status['id'] }}"> {{ $free_status['title'] }} </option>
    @endforeach
</select>    