<input type="{{ $type }}" class="form-control {{ $errors->has( $name ) ? ' is-invalid' : '' }} datepicker" maxlength="10" name="{{ $name }}"
placeholder="" data-date-format="dd/mm/yyyy" value="{{ old( $name , !empty($data) ? $data : '' ) }}"
>
