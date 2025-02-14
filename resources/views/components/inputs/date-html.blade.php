<input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
value="{{ old( $name , !empty($data) ?     date('Y-m-d\TH:i', strtotime($data))    : '' ) }}"
class="form-control {{ $errors->has( $name ) ? ' is-invalid' : '' }}">
