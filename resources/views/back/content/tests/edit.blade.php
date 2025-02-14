@extends('back/layouts.app')


@section('content')

@component('components.admin.page-header', [
'title' => __('actions.edit') . ' - ' . $morphModel->title,
'routes' => [
      ['header_route' => route('dashboard.tests.index'), 'header_name' => 'Tests'],
      ['header_name' => __('actions.edit')]
    ]
])
@endcomponent




<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-primary">
          <!-- <div class="card-header">
            <h3 class="card-title">Quick Example</h3>
          </div> -->

          <x-admin.datatable.page-alert />



          <form method="post" action="{{ route('dashboard.tests.update', ['test' => $data->id, 'type' => request()->query('type'), 'id' => $morphModel->id ]) }}"  class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">

              <input name="locale" type="hidden" value="{{ request()->query('input_locale') }}">

              <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('general.title') }} <span class="required">*</span></label>
                <div class="col-sm-10">
                  <input type="text" name="title" maxlength="150" value="{{ old('title', $translation?->title) }}" required class="form-control {{ $errors->has('title') ? ' is-invalid ' : '' }}" placeholder="...">
                  <x-admin.datatable.label-input-error field='title' />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('general.alias') }} <span class="required">*</span></label>
                <div class="col-sm-10">
                  <input type="text" name="alies" maxlength="150" value="{{ old('alias', $translation?->alias) }}" required class="form-control {{ $errors->has('alias') ? ' is-invalid ' : '' }}" placeholder="...">
                  <x-admin.datatable.label-input-error field='alias' />
                </div>
              </div>


              <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('domain.teachers') }} <span class="required">*</span></label>
                <div class="col-sm-10">
                    <x-admin.datatable.teachers-dd :teachers='$teachers' dataValue="{{ $data->teacher_id }}"/>
                    <x-admin.datatable.label-input-error field='teacher_id' />
                </div>
              </div>


              <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('domain.test_type') }} <span class="required">*</span></label>
                <div class="col-sm-10">
                    <x-admin.test-types-dd :test_types='$testTypes' data_value="{{ $data->test_type_id }}" />
                    <x-admin.datatable.label-input-error field='test_type_id' />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('domain.get_questions') }} <span class="required">*</span></label>
                <div class="col-sm-10">
                      <x-admin.get_questions-dd :get_questions='$getQuestions' dataValue="{{ $data->get_questions }}"/>
                      <x-admin.datatable.label-input-error field='get_questions' />
                </div>
              </div>


              <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('domain.duration') }} {{ __('general.minutes') }}</label>
                <div class="col-sm-10">
                  <input type="number" name="duration" maxlength="6" value="{{ old('duration', $data?->duration) }}" class="form-control {{ $errors->has('duration') ? ' is-invalid ' : '' }}" placeholder="...">
                  <x-admin.datatable.label-input-error field='duration' />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('domain.show_count') }}</label>
                <div class="col-sm-10">
                  <input type="number" name="show_count" maxlength="2" value="{{ old('show_count', $data?->show_count) }}" class="form-control {{ $errors->has('show_count') ? ' is-invalid ' : '' }}" placeholder="">
                  <x-admin.datatable.label-input-error field='show_count' />
                </div>
              </div>


              <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('domain.percentage') }}</label>
                <div class="col-sm-10">
                  <input type="number" name="percentage" maxlength="4" value="{{ old('percentage', $data?->percentage) }}" class="form-control {{ $errors->has('percentage') ? ' is-invalid ' : '' }}" placeholder="...">
                  <x-admin.datatable.label-input-error field='percentage' />
                </div>
              </div>


              <div class="form-group row">
                  <label class="col-sm-2 col-form-label">{{ __('general.status') }} <span class="required">*</span></label>
                  <div class="col-sm-10">
                    <x-admin.dd-statuses :statuses='$statuses'  dataValue="{{ $data->status }}"/>
                    <x-admin.datatable.label-input-error field='status' />
                  </div>

              </div>

              <div class="form-group row">
                <label class="col-sm-2 col-form-label">{{ __('domain.lessons') }} <span class="required">*</span></label>
                <div class="col-sm-10">
                    <x-admin.lessons-chk :lessons='$lessons' dataValues="{{ $data->lessons->pluck('id') }}"/>
                    <x-admin.datatable.label-input-error field='lesson_ids' />
                </div>
              </div>

            </div>


            <div class="card-footer">
              <button type="submit" class="btn btn-info">{{ __('actions.save') }}</button>
              <!-- <button type="submit" class="btn btn-default float-right">Cancel</button> -->
            </div>
          </form>
        </div>



      </div>
    </div>
  </div>
</section>



@endsection
