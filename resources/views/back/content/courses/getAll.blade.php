@extends('back/layouts.app')

@push('css_pagelevel')
  <x-admin.datatable.header-css />
@endpush

@section('content')

@include('components.admin.page-header',[
  'title' => __('actions.index') . ' ' . __('domain.courses'),
  'routes' => [
        ['header_route' => route('dashboard.courses.getAll',['site'=>$site_id]), 'header_name' => __('domain.courses')],
        ['header_name' => __('domain.courses')]
  ]
])

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">

            <x-admin.datatable.page-alert />

            <div class="x_title">
                <a class="btn btn-success pull-right" href="{{ route('dashboard.courses.create',$site_id) }}"> @lang('core.add') </a>
                <div class="clearfix"></div>
            </div>

          <div class="card-header">
            <form method="get" action="{{ route('dashboard.courses.getAll',$site_id) }}">
              <div class="row">
                <div class="col-md-4">
                    <div class="form-group d-flex">
                      <input type="text" name="name" maxlength="150" value="{{ old('name') }}" class="form-control {{ $errors->has('name') ? ' is-invalid ' : '' }}" placeholder="">
                      <x-admin.datatable.label-input-error field='name' />
                      <label class="ml-3">{{ __('general.name') }} </label>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <x-admin.datatable.but_submit butTitle="{{ __('general.search') }}" />
                      <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        {{ __('general.reset') }}
                      </button>

                    </div>
                  </div>

              </div>
            </form>
          </div>

          <div class="card-body">
            <table id="dt_table_1" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th><input type="checkbox" name="select_all" class="dt-select-all" id="select_all"></th>
                <th>id</th>
                <th>{{ __('general.title') }}</th>
                <th>{{ __('general.date_at') }}</th>
{{--                <th>{{ __('general.study_hours') }}</th>--}}
{{--                <th>{{ __('general.study_hour_fee') }}</th>--}}
{{--                <th>{{ __('general.views_count') }}</th>--}}
{{--                <th>{{ __('general.likes_count') }}</th>--}}
                <th>{{ __('words.sort') }}</th>
                <th>{{ __('words.status') }}</th>
                <th>{{ __('actions.edit') }}</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
              </thead>
              <tbody>
                @php $languages = \App\Models\Language::where('status','1')->get(); @endphp
                @php $locale = request()->query('locale'); @endphp

                @foreach ($courses as $item)

                    <?php
                    $termTranslation = App\Translations\CourseTranslation::query()->where('locale',app()->getLocale())->where('course_id',$item->id)->first();
                    ?>

                <tr id="{{ $item->id }}">
                  <td value="{{ $item->id }}"></td>
                  <td>{{ $item->id }}</td>
                  <td>{{ isset($termTranslation) ? $termTranslation->name : $item->title }}</td>
                  <td>{{ $item->date_at }}</td>
{{--                  <td>{{ $item->study_hours }}</td>--}}
{{--                  <td>{{ $item->study_hour_fee }}</td>--}}
{{--                  <td>{{ $item->views_count }}</td>--}}
{{--                  <td>{{ $item->likes_count }}</td>--}}
                  <td>{{ $item->sort }}</td>
                  <td>
                    <div>{{ $item->tatus}}</div>
                  </td>
                  <td>
                      @foreach (getActiveLanguages() as $language)
                          <a href="{{ route('dashboard.courses.edit', ['site'=>$site_id,'course' => $item->id, 'language' => $language->alies ] )}}" class="btn btn-sm btn-warning">
                              {{ $language->alies }}
                          </a>
                      @endforeach
                  </td>
                  <td><x-admin.datatable.but_delete link="{{ route('dashboard.courses.destroy',['site'=>$site_id,'course' => $item->id]) }}" /></td>
                  <td>
{{--                    @if ($item->hasTrack())--}}
{{--                    <a href="{{route('dashboard.courses.track.edit', ['course_id' => $item->id])}}" class="btn btn-success">--}}
{{--                      {{__('actions.edit') . " " . __('domain.track')}}--}}
{{--                    </a>--}}
{{--                    @else--}}
{{--                    <a href="{{route('dashboard.courses.track.create', ['course_id' => $item->id])}}" class="btn btn-info">--}}
{{--                      {{__('actions.create') . " " . __('domain.track')}}--}}
{{--                    </a>--}}
{{--                    @endif--}}
                  </td>
                  <td>
                    <a href="{{route('dashboard.tests.create', ['site' => $site_id, 'course' => $item->id, 'id' => $item->id, 'type' => 'course'])}}" class="btn btn-info">
                        {{__('words.create_test')}}
                    </a>

                    <a href="{{route('dashboard.lessons.index', ['course' => $item->id])}}" class="btn btn-info">
                         {{ __('words.lessons') }}
                    </a>

                    <a href="{{route('dashboard.tests.index', ['id' => $item->id, 'type' => 'course'])}}" class="btn btn-info">
                        {{ __('words.tests') }}
                    </a>

{{--                    <a href="{{route('dashboard.track.editTrack', ['id' => $item->id])}}" class="btn btn-info">--}}
{{--                        {{ __('words.update_path') }}--}}
{{--                    </a>--}}

                      @if ($item->hasTrack())
                      <a href="{{route('dashboard.track.edit', ['course_id' => $item->id])}}" class="btn btn-success">
                        {{__('actions.edit') . " " . __('domain.track')}}
                      </a>
                      @else
                      <a href="{{route('dashboard.track.create', ['course_id' => $item->id])}}" class="btn btn-info">
                        {{__('actions.create') . " " . __('domain.track')}}
                      </a>
                      @endif


                  </td>

                </tr>
                @endforeach

              </tbody>
            </table>

          </div>
          @if($courses instanceof \Illuminate\Pagination\LengthAwarePaginator )
              @if ($courses->hasPages())
              <div class="pagination-wrapper">
                   {{ $courses->links() }}
              </div>
              @endif
          @endif
        </div>
      </div>
    </div>
  </div>

</section>

@endsection

@push('js_pagelevel')

  <script>
    // datatable settings
    dt1_display_search_input_columns_values = [2];
    dt1_display_search_droplist_columns_values = [];
    dt1_columnDefs_orderable_value = false;
    dt1_columnDefs_className_value = '';
    dt1_columnDefs_targets_value = [0,9,10,11,12,13];

    function resetForm() {
    window.location.href = "{{ route('dashboard.courses.getAll',$site_id) }}";
    }

  </script>

<x-admin.datatable.footer-js />

@endpush
