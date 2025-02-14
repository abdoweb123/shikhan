@extends('back/layouts.app')

@section('content')

<div class="">
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-xs-12">
      <div class="x_panel">
        <div class="x_title">
            {{--
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                          @endforeach
                </ul>
              </div>
            @endif
            --}}
            @include('back.includes.page-alert')
            <h2><x-buttons.but_new link="{{ route('dashboard.pages.create') }}"/></h2>
              <div class="clearfix"></div>
            </div>





            <div class="x_content">
              <table id="datatable-buttons" class="table table-striped table-bordered">
                <tr>
                  <td style="width:50%"></td>
                  <!-- <td style="width:20%"></td> -->
                  <td style="width:15%"></td>
                  <td style="width:15%"></td>
                </tr>
                @foreach($data as $item)
                  <tr>
                    <td>
                        <a style="padding-right: {{$item->depth * 30}}px;padding-left: {{$item->depth * 20}}px; font-weight: {{ $item->depth == 0 ?  600 : '400' }};"
                          class="kt-userpic kt-userpic--circle kt-margin-r-5 kt-margin-t-5" data-toggle="kt-tooltip" data-placement="right">
                          - <img src="{{ optional(optional($item->page_info)->first())->imagePath() }}" class="img-thumbnail img-responsive" width="50">
                          {{ !$item->page_info->isEmpty() ? $item->page_info->first()->title : $item->title_general.' - '. __('words.not_translated') }}
                        </a>
                       <!-- <span class="kt-widget11__sub">CRM System</span> -->
                    </td>
                    <td>
                        @foreach (getActiveLanguages() as $language)
                          <a href="{{ route('dashboard.pages.edit', ['id' => $item->id, 'language' => $language->alies ] )}}" class="btn btn-sm btn-warning">
                            {{ $language->alies }}
                          </a>
                        @endforeach
                    </td>

                    <td>
                      <form id="frm_status" name="frm_status" action="{{ route('dashboard.pages.status',['id' => $item->id ]) }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" id="_method" name="_method" value="PUT">
                          <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                            <label>
                              <input type="checkbox"  {{ $item->is_active ? 'checked' : '' }}  onclick="submitForm(this);">
                              <span></span>
                            </label>
                          </span>
                      </form>
                    </td>

                    <td>{{--<x-buttons.but_delete_inline link='{{ route("admin.pages.destroy" , [ "id" => $item->id ] ) }}'/></td>--}}
                  </tr>
                @endforeach
              </table>
            </div>

      </div>
    </div>
  </div>
</div>





@section('js_pagelevel')
  <script>
  function submitForm(me)
  {
    $(me).closest("form").submit();
  }
  </script>
@endsection

@endsection
