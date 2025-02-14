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
                            @foreach($lessons as $lesson)
                                @php
                                   $lesson_sort = \App\Models\CourseTrack::where('course_id',$course->id)->where('courseable_type','lessons')->where('courseable_id',$lesson->id)->first();
                                @endphp
                                <div class="form-group">
                                    <input type="hidden" value="{{$lesson->id}}" class="form-control" name="lesson_ids[{{$loop->index}}][id]" />
                                    <label> {{'ترتيب درس  : ' . $lesson->translation->where('locale',app()->getLocale())->first()->title . $lesson->title_genaral}}</label>
                                    <input type="number" min="0" value="{{$lesson_sort->sort?? ''}}" class="form-control" name="lesson_ids[{{$loop->index}}][sort]"/>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-4">
                            @foreach($tests as $test)
                                @php
                                    $test_sort = \App\Models\CourseTrack::where('course_id',$course->id)->where('courseable_type','tests')->where('courseable_id',$test->id)->first();
                                @endphp
                                <div class="form-group">
                                    <input type="hidden" value="{{$test->id}}" class="form-control" name="test_ids[{{$loop->index}}][id]" />
                                    <label> {{'ترتيب اختبار  : ' . $test->translation->where('locale',app()->getLocale())->first()->title . $lesson->name}}</label>
                                    <input type="number" min="0" value="{{$test_sort->sort?? ''}}" class="form-control" name="test_ids[{{$loop->index}}][sort]"/>
                                </div>
                            @endforeach
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
