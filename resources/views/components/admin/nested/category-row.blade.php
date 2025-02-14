<tr>
  <td>
      <a style="padding-right: {{$data->depth * 30}}px;padding-left: {{$data->depth * 20}}px; font-weight: {{ $data->depth == 0 ?  600 : '400' }};"
        class="kt-userpic kt-userpic--circle kt-margin-r-5 kt-margin-t-5" data-toggle="kt-tooltip" data-placement="right">
        - <img src="{{ $data->image ? asset('storage/app/'.$data->image) : '' }}"> {{ $data->title ?? $data->title_general.' : '.'غير مترجم' }}
      </a>
     <!-- <span class="kt-widget11__sub">CRM System</span> -->
  </td>
  <td>
    <form id="frm_category_status" name="frm_category_status" action="{{ route('admin.categories.status',['id' => $data->id ]) }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}
      <input type="hidden" id="_method" name="_method" value="PUT">
        <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
          <label>
            <input type="checkbox"  {{ $data->is_active ? 'checked' : '' }}  onclick="submitForm(this);">
            <span></span>
          </label>
        </span>
    </form>
  </td>
  <td>

    @if($data->read_only === 0)
      <x-buttons.but_edit link="{{ route('admin.categories.edit',['id' => $data->id ]) }}"/>
    @endif
    @if($data->read_only === 1)
      <x-buttons.but_edit link="{{ route('admin.categories.edit_read_only',['id' => $data->id ]) }}"/>
    @endif
  </td>
  <td>
    @if($data->read_only === 0 && $data->parent_id !== 0)
      <x-buttons.but_delete_inline link='{{ route("admin.categories.destroy" , [ "id" => $data->id ] ) }}'/>
    @endif
  </td>
</tr>

@isset($data->children)
  @foreach($data->children as $child)
    <x-admin.nested.category-row :data="$child"/>
  @endforeach
@endisset
