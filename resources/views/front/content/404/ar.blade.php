@extends('front.layouts.app')
@section('content')
    <section class="profile text-center site-hero site-sm-hero" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row justify-content-center site-hero-sm-inner">
                <div class="col-md-3">
                    @include('front.units.aside.index')
                </div>
                <div class="col-md-9 card">
                    <div class="p-5 m-4">
                        <h1><strong>404</strong></h1>
                        <h2 class="mb-50">عفوًا ، حدث خطأ ما</h2>
                        <p class="mb-50">تعذر العثور على الصفحة التي طلبتها ، ربما تمت إزالة الصفحة.</p>
                        <a href="{{ route('home') }}" class="btn btn-default btn-lg"><i class="icon-arrow-left icon-position-left"></i><span class="spr-option-textedit-link">العودة إلى الصفحة الرئيسية</span></a>
                        <a href="{{ url()->previous() }}" class="btn btn-default btn-lg"><i class="icon-arrow-left icon-position-left"></i><span class="spr-option-textedit-link">عودة</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
