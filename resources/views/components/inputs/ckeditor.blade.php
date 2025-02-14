<textarea
  id="CKEDITOR"
  name="{{ $name }}"
  class="form-control ckeditor {{ $errors->has( $name ) ? ' is-invalid' : '' }}"
  rows="{{ isset($rows) ? $rows : 10 }}"
  {{ isset($required) ? 'required' : '' }}>
  {!! old( $name , !empty($data) ? $data : '' ) !!}
</textarea>

{{--
<script type="text/javascript">
CKEDITOR.replace( 'editor1',
{
  height: '{{ isset($height) ? $height : "" }}',
});
</script>
--}}
