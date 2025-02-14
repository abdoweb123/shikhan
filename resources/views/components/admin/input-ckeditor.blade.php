
<label class="col-sm-2 col-form-label">{{ __('general.description') }} <span class="required">*</span></label>
<div class="col-sm-10">
  <textarea
    id="CKEDITOR"
    name="{{ isset($name) ? $name : 'description' }}"
    class="form-control ckeditor {{ $errors->has( isset($name) ? $name : 'description' ) ? ' is-invalid' : '' }}"
    rows="{{ isset($rows) ? $rows : 10 }}"
    {{ isset($required) ? 'required' : '' }}>
    {!! old( isset($name) ? $name : 'description' , !empty($dataValue) ? $dataValue : '' ) !!}
  </textarea>
</div>



@push('js_pagelevel')
<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
<script type="text/javascript">
CKEDITOR.replace( 'message',
{
  height: '{{ isset($height) ? $height : "" }}',
});
</script>
@endpush
