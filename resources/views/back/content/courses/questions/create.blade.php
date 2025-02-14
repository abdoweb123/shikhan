@extends('back/layouts.app')

@section('content')


<div class="">
  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12">
      <div class="x_panel">
        <div class="x_title">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h2> {{ $course->name.' | '.__('meta.title.questions_old') }} </h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @if(session()->has('success'))
                <div class="alert alert-success text-center">
                    {{ session()->get('success') }}
                </div>
            @endif
            @include('back.includes.breadcrumb',['routes' => [
                ['slug' => route('dashboard.courses.index',$site->alias),'name' => $site->name],
                ['name' => $course->name.' | '.__('meta.title.questions_old')]]
            ])

            <div class="col-md-12 row">

                <form method="post" action="{{ route('dashboard.courses.questions_old.store',['site' => $site->alias,'course' => $course->id]) }}">
                  @csrf

                    <div class="col-md-12">
                        <table class="table table-dark table-striped table-bordered">
                            <tbody id="questions_container"></tbody>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-success">اضافة</button>
                </form>

          </div>
      </div>
    </div>
  </div>
</div>
@stop
