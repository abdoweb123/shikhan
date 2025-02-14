@php $name = isset($name) ? $name : 'partner_id'; @endphp
<select name="{{ $name }}" class=" form-control col-md-12">
    @if (isset($select_none)) 
        <option {{ old($name) == 'all'  ? 'selected' : ''}} value="all">---</option>
    @endif
    @foreach($partners ?? [] as $partner)
        <option {{ old($name, isset($row) ? $row?->partner_id : '') == $partner->id ? 'selected' : '' }} value="{{$partner->id}}"> {{ $partner->name }} </option>
    @endforeach
</select> 