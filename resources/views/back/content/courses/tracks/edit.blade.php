@extends('back/layouts.app')

@section('content')

    @include('components.admin.page-header',[
      'title' => __('actions.edit') .' '. __('domain.track'),
      'routes' => [
            ['header_route' => route('dashboard.courses.getAll',$site_id), 'header_name' => __('domain.courses')],
            ['header_name' => __('domain.tracks')]
      ]
    ])

    <section class="content">
        <div class="container-fluid">
            @include('components.admin.datatable.page-alert')

            <!-- form start -->
            <form method="post" action="{{ route('dashboard.track.update',['site'=>$site_id]) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="site_id" value="{{$site_id}}">
                <div class="card-body">
                    <div class="raw">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" id="course_id" value="{{$course->name}}" disabled/>
                                <input type="text" hidden name="course_id" value="{{$course->id}}"/>
                                <label for="course_id">{{__('domain.course')}}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <x-admin.multi-selection name="lesson_ids" arabicLabel="ترتيب درس "
                                                     :title="__('domain.lessons')" :items="$lessons"
                                                     :childs="$course->trackLessons" :edit="true" required="true"/>
                        </div>
                        <div class="col-sm-4">
                            @if ($tests)
                                <x-admin.multi-selection name="test_ids" arabicLabel="ترتيب اختبار "
                                                         :title="__('domain.tests')" :items="$tests"
                                                         :childs="$course->trackTests" :edit="true" required="false"/>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="form-group card-footer">
                    <button type="submit" class="btn btn-primary">{{__('actions.edit')}}</button>
                </div>
            </form>

        </div>
    </section>
@endsection
