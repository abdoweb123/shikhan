@extends('front.layouts.new')
@section('head')
<style>
.row.justify-content-center {
    overflow-x: scroll;
}
th, td {
    text-align: center;
}
</style>
@endsection
@section('content')

<!-- ##### Hero Area Start ##### -->
<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 350px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12"style=" margin-top: 130px;">
                <!-- Hero Content -->
                <div class="hero-content text-center row">
                    <div class="col-4">
                        @if(!empty(Auth::guard('web')->user()->avatar))
                            <div class="avatar">
                                {{--dd(url(Auth::guard('web')->user()->avatar))--}}
                                <img src="{{ url(Auth::guard('web')->user()->avatar_path) }}" class="bg-light img-raised img-fluid" style="width: 80px;border-radius: 18px;" alt="{{ Auth::guard('web')->user()->name }}">
                            </div>
                        @else
                            <div class="p-5"></div>
                        @endif
                    </div>
                    <div class="col-8">
                        <h1 style="color: white;">@lang('meta.title.certificates')</h1>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- ##### Hero Area End ##### -->

    <div class="container">

        <div class="description text-center">
            {{-- <p> {{ $site->description }} </p> --}}
        </div>
        @include('front.units.notify')
        <div class="row justify-content-center">

            <table class="table table-striped mt-3">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">{{__('words.Category')}}</th>
                  <th scope="col">{{__('words.course')}}</th>
                  <th scope="col">{{__('words.rate')}}</th>
                  <th scope="col">{{__('words.degree')}}</th>
                  <th scope="col">{{__('words.douwnlod')}}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($result as $certificate)
                    <tr>
                      <th scope="row">{{$loop->iteration}}</th>
                      <td> {{$certificate->first()->title}}</td>
                      <td>{{$certificate->first()->course_title}}</td>
                      <td>{{__('trans.rate.'.$certificate->first()->rate)}}</td>
                      <td>{{round($certificate->first()->degree,2)}}</td>
                      <td><a href="{{ route('certificates-show',$certificate->first()->id) }}"  class="btn btn-primary"><i class="fa fa-download"></i>{{ __('core.press_to_douwnlod') }}</a></td>
                    </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
@endsection
