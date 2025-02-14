@extends('front.layouts.app')
@section('content')
    <section class="profile text-center site-hero site-sm-hero" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row justify-content-center site-hero-sm-inner">
                <div class="col-md-3">
                    @include('front.units.aside.index')
                </div>
                <div class="col-md-9 card">
                    <h1 class="btn1 py-3 px-5 mx-0">  تصميم صفحات الويب التعليمية </h1>
                    <br>
                    <ul class=" bg-light">
                        @foreach($result as $row)
                            <li>
                                <a class="btn2 py-3 px-5" href="{{ route('lessons.show',$row['id']) }}"> {{ $row['name'] }} </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
