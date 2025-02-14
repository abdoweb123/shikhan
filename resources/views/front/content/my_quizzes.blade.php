@extends('front.layouts.auth_app')
@section('content')

  <!-- ##### Hero Area Start ##### -->
  <section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 300px;">
      <div class="container h-100">
          <div class="row h-100 align-items-center">
              <div class="col-12">
                  <!-- Hero Content -->
                  <div class="hero-content text-center">
                    @if(!empty(Auth::guard('web')->user()->avatar))
                        <div class="avatar">
                            <img src="{{ url(Auth::guard('web')->user()->avatar_path) }}" class="p-3 bg-light img-raised rounded-circle img-fluid" alt="{{ Auth::guard('web')->user()->name }}" style="width: 170px;border-radius: 18px;">
                        </div>
                    @else
                        <div class="p-5"></div>
                    @endif
                    <div class="name">
                        <h3 class="title" style="color: white;"> @lang('meta.title.my_quizzes') </h3>
                    </div>
                  </div>
              </div>
          </div>
      </div>
  </section>
  <!-- ##### Hero Area End ##### -->


    <div class="profile-content">

        <div class="description text-center">
            {{-- <p> {{ $site->description }} </p> --}}
        </div>

        {{--@include('front.units.breadcrumb',['routes' => [['name' => __('meta.title.my_quizzes')]]])--}}
        <div class="container" style="padding-top: 90px;">
          @include('front.units.notify')
          <div class="row">
              <div class="col-md-12">
                  <table class="table">
                      <thead style="background-color: #d0c384;text-align: center;">
                          <tr>
                              <th class="text-center">#</th>
                              <th class="text-center"> @lang('field.logo') </th>
                              <th> @lang('field.course_name') </th>
                              <th class="text-center"> @lang('field.degree') </th>
                              <th class="text-center"> @lang('field.rate') </th>
                              <th class="text-center"> @lang('core.language') </th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($result?? [] as $test)
                              <tr>
                                  <td class="text-center">
                                    @if ($test->degree > 70)
                                      <a href="{{ route('certificates-show' , [ 'id' => $test->id ] ) }}"> {{ $test->id }} </a>
                                      @else
                                        {{ $test->id }}
                                    @endif
                                  </td>
                                  <td class="text-center">
                                    @if ($test->degree >= 70)
                                      <a href="{{ route('certificates-show' , [ 'id' => $test->id ] ) }}">
                                        <img src="{{ url($test->course->logo_path) }}" class="img-thumbnail img-raised rounded img-fluid" width="35" alt="{{ $test->course->name }}">
                                      </a>
                                    @else
                                      <img src="{{ url($test->course->logo_path) }}" class="img-thumbnail img-raised rounded img-fluid" width="35" alt="{{ $test->course->name }}">
                                    @endif
                                  </td>
                                  <td>
                                    @if ($test->degree >= 70)
                                      <a href="{{ route('certificates-show' , [ 'id' => $test->id ] ) }}">
                                        <p class="title my-2" style="text-align: center;">{{ $test->course->name }} </p>
                                      </a>
                                    @else
                                      <p class="title my-2" style="text-align: center;">{{ $test->course->name }} </p>
                                    @endif
                                   </td>
                                  <td class="text-center"> <span class="badge badge-{{ $test->rate == '4' ? 'primary' : ($test->rate == '3' ? 'info' : ($test->rate == '2' ? 'success' : ($test->rate == '1' ? 'warning' : 'danger')))  }}"> {{ $test->degree }} </span> </td>
                                  <td class="text-center"> <span class="badge badge-{{ $test->rate == '4' ? 'primary' : ($test->rate == '3' ? 'info' : ($test->rate == '2' ? 'success' : ($test->rate == '1' ? 'warning' : 'danger')))  }}"> {{ __('trans.rate.'.$test->rate) }} </span> </td>
                                  <td class="text-center"> <span class="badge badge-default"> {{ config('localization.'.$test->locale.'.native') }} </span> </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
          </div>
        </div>
    </div>
@endsection
