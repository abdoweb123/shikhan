@extends('front.layouts.new')

@section('content')
<style>

 .card.mt-2{

  height: 84% !important; overflow: !important;
}

</style>
    <div class="profile-content">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="profile">
                    @if(!empty(Auth::guard('web')->user()->avatar))
                        <div class="avatar">
                            <img src="{{ url(Auth::guard('web')->user()->avatar_path) }}" class="p-3 bg-light img-raised rounded-circle img-fluid" alt="{{ Auth::guard('web')->user()->name }}">
                        </div>
                    @else
                        <div class="p-5"></div>
                    @endif
                    <div class="name">
                        <h3 class="title"> @lang('meta.title.certificates') </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="description text-center">
            {{-- <p> {{ $site->description }} </p> --}}
        </div>
        @include('front.units.breadcrumb',['routes' => [['name' => __('meta.title.certificates')]]])
        @include('front.units.notify')
        <div class="row">
            @foreach ($result as $certificate)
                @php
                    $message = $certificate->course->translate($certificate['locale']);
                    // $content = view('emails.results', ['data' => $certificate,'subject' => $message->subject,'content' => $message->content]);
                @endphp
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body pt-1 px-3 pb-2 text-center">
                            <h4 class="card-title"> {{ $message->subject }} </h4>
                        </div>
                    </div>
                    <div class="card">
                        <style type="text/css">
                            @font-face {
                                font-family: "Custom-Font";
                                src: url("{{ asset('assets/fonts/lang/'.$certificate['locale']. ( $certificate['locale'] == 'am' ? '.ttc' : (in_array($certificate['locale'],['ar','bn']) ? '.otf' : '.ttf?u') )) }}");
                            }
                        </style>
                        {!! str_ireplace(['[name]','[degree]','[rate]'],[$certificate['name'],round($certificate['degree'],2),__('trans.rate.'.$certificate['rate'],[],$certificate['locale'])],$message->content) !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
