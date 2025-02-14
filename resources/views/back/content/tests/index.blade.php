@extends('back/layouts.app')


@push('css_pagelevel')
  <x-admin.datatable.header-css />
@endpush

@section('content')


@component('components.admin.page-header', [
  'title' => __('actions.index') . ' ' . __('domain.tests'),
  'routes' => [
        ['header_route' => route('dashboard.tests.index'), 'header_name' =>  __('domain.tests')],
  ],
])
@endcomponent


<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- <div class="card-header">
            <h3 class="card-title">DataTable with minimal features & hover style</h3>
          </div> -->


          <div class="card-header">
            <form method="get" action="{{ route('dashboard.tests.index') }}" >
              <div class="row">
                <div class="col-md-4">
                    <div class="form-group d-flex">
                      <input type="text" name="title" maxlength="150" value="{{ old('title') }}" class="form-control {{ $errors->has('title') ? ' is-invalid ' : '' }}" placeholder="">
                      <x-admin.datatable.label-input-error field='title' />
                      <label class="ml-3">{{ __('general.name') }} </label>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <x-admin.datatable.but_submit butTitle="{{ __('general.search') }}" />
{{--                      <button type="button" class="btn btn-secondary" onclick="resetForm()">--}}
{{--                        {{ __('general.reset') }}--}}
{{--                      </button>--}}
                      <a type="button" class="btn btn-secondary" href="{{route('dashboard.tests.index')}}">
                        {{ __('general.reset') }}
                      </a>
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
                <th>{{ __('domain.teachers') }}</th>
                <th>{{ __('domain.test_type') }}</th>
                <th>{{ __('domain.course') }}</th>
                <th>{{ __('domain.lessons') }}</th>
                <th>{{ __('actions.edit') }}</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
                @php $languages = \App\Models\Language::where('status','1')->get(); @endphp
                @php $locale = request()->query('locale'); @endphp

                @foreach ($items as $item)
                <tr id="{{ $item->id }}">
                  <td value="{{ $item->id }}"></td>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->translateOrDefault($inputLocale)?->title }}</td>
                  <td>{{ $item->teacher?->translateOrDefault($inputLocale)?->title }}</td>
                  <td>{{ $item->test_type?->getTitle($inputLocale) }}</td>
                  <td>{{ $item->testable?->translateOrDefault($inputLocale)?->title }}</td>
                  <td>
                    @foreach ($item->lessons ?? [] as $lesson)
                    {{ $lesson->translateOrDefault($inputLocale)?->title }} {{ !$loop->last ? ', ' : ''}}
                    @endforeach
                  </td>
                  <td>
                    @foreach ($languages as $language)
                      <a href="{{ route('dashboard.tests.edit', ['test' => $item->id, 'type' => $item->testable_type , 'id' => $item->testable?->id , 'input_locale' => $language->alies ] )}}" class="btn btn-sm btn-warning">
                        {{ $language->alies }}
                      </a>
                    @endforeach
{{--                    <a href="{{ route('dashboard.questions_old.index', ['test' => $item->id ] )}}" class="btn btn-sm btn-warning">--}}
{{--                      questions_old--}}
{{--                    </a>--}}
                    <a href="{{route('dashboard.questions_test.index', ['test' => $item->id ])}}" class="btn btn-sm btn-warning">
                        questions
                    </a>
                  </td>
                  <td><x-admin.datatable.but_delete link="{{ route('dashboard.tests.destroy',['test' => $item->id]) }}" /></td>
                </tr>
                @endforeach

              </tbody>
            </table>

          </div>
          @if ($items->hasPages())
              <div class="pagination-wrapper">
                   {{ $items->links() }}
              </div>
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
    dt1_display_search_droplist_columns_values = [4];
    dt1_columnDefs_orderable_value = false;
    dt1_columnDefs_className_value = '';
    dt1_columnDefs_targets_value = [0,5,6,7,8];

  </script>

  <x-admin.datatable.footer-js />

@endpush
