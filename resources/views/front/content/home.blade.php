@extends('front.layouts.app')
@section('content')
    <section class="section topics" id="form-section">
        @include('front.units.notify')
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <h3 style="margin-bottom: 20px; font-weight: bold;text-align: center;">
                        <i class="fa fa-th" style="color: #000;padding: 10px;"></i>فضلاً اختر موقع
                    </h3>
                    <div class="text-center tiles col-xs-12">
                        @foreach ($result as $row)
                            <a href="{{ route('courses.index',$row->alias) }}">
                                <div class="tile bg-light bg-font-light">
                                    <div class="tile-body thumbnail">
                                        <img src="{{ url($row->logo_path) }}" data-toggle="tooltip" data-placement="top" class="card-img" alt="{{ $row->title }}" title="{{ $row->title }}">
                                        {{-- <i class="fa fa-tv"></i> 55 x 55 --}}
                                    </div>
                                    {{-- <div class="tile-object text-center bg-dark">
                                        <div class="">
                                            {{ $row->title }}
                                        </div>
                                    </div> --}}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
