@if(isset($icon))
<a href="{{ $link }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View" style="background: #0195a2;padding: 5px 10px 2px;"><i class="fa fa-pencil-square-o" style="color: #ffffff;font-size: 17px;font-weight: 500;"></i></a>
@else
<a href="{{ $link }}" type="button" class="btn btn-warning"><i class="flaticon2-edit"></i>{{ __('words.edit') }}</a>
@endif
