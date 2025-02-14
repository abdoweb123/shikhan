@extends('front.layouts.app')
@section('content')
    <section class="profile text-center site-hero site-sm-hero" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row justify-content-center site-hero-sm-inner">
                <div class="col-md-3">
                    @include('front.units.aside.index')
                </div>
                <div class="col-md-9 card">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="btn1 py-3 px-5 mx-0"> {{ $row['name'] }} </h1>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            {!! $row->dictionary !!}
                        </div>
                        <div class="col-md-12">
                            <div class="text-center">
                                @include('front.units.actions')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
