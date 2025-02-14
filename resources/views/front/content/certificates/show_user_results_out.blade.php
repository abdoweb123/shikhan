@extends('front.layouts.new')
@section('head')
<style>

</style>
@endsection

@section('content')

<section class="hero-area bg-img bg-overlay-2by5" style="background-image: url( {{ asset('assets/front/img/bg-img/bg1.jpg') }} );height: 300px;">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">.
                <div class="hero-content text-center">
                  <div class="p-5"></div>
                  <div class="name mt-5" style="display: flex;">
                      <h4 class="title courses_done">التحقق من صحة شهادة طالب</h4>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="profile-content">
  <div class="container" >
    <section class="courses-details-area">
      <div class="container" style="padding: 20px 0px;" >

            @include('front.units.notify')

            <div class="form-group col-12">
              أدخل البريد الإلكتروني للطالب أو رقم تعريف الشهادة
            </div>

            <form class="form-inline row justify-content-md-center" method="POST" action="{{ route('front.get_user_results_out') }}"  style="padding: 20px 0px;"  autocomplete="false">
                @csrf

                  <div class="form-group col-2 col-xl-2 col-lg-2">
                    <select name="search_type">
                      <option value="diplom" {{ old('search_type') == 'diplom' ? 'selected' : '' }} >شهادة دبلوم</option>
                      <option value="course" {{ old('search_type') == 'course' ? 'selected' : '' }} >شهادة دورة</option>
                    </select>
                    @error('search_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>

                  <div class="form-group col-6 col-xl-6 col-lg-6">
                    <input type="text" class="form-control" id="staticEmail2" name="search" value="{{ old('search') }}" style="width: 100%;" required autofocus placeholder="البريد الإلكتروني للطالب أو رقم تعريف الشهادة">
                    @error('search')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>

                  <div class="form-group col-2">
                    <button type="submit" class="btn btn-primary mb-2">بحث</button>
                  </div>

            </form>




            <!-- search email -->
            @if (session('data'))
              <div class="row justify-content-center">
                <div class="col-lg-9">

                    @php $siteCount = 0; @endphp

                    @foreach (session('data') ?? [] as $site)
                      @if (! $site->site_not_completed && $site->user_finished_site && $site->user_sucess && $site->less_than_70 )
                      @php $siteCount++; @endphp
                      <div class="alert alert-success" role="alert">
                        <span style="font-size: 16px;font-weight: bold;padding-top: 15px;"> شهادة صحيحة<br><span>

                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;">رقم تعريف الشهادة :<span>
                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;"> {{ $site->certificateCode }}<br><span>

                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;">نؤكد نجاح الطالب / الطالبة : <span>
                        <span style="font-size: 16px;font-weight: bold;padding-top: 15px;">{{ session('user')->name }}<br><span>

                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;">فى دبلوم :<span>
                        <span style="font-size: 16px;font-weight: bold;padding-top: 15px;">{{ $site->title }}<br><span>

                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;">  بتقدير :<span>
                         <span style="font-size: 16px;font-weight: bold;padding-top: 15px;">{{ __('trans.rate.'.$site->user_site_rate) }}<span>
                      </div>
                      @endif
                    @endforeach



                    @if(isset(session('data')['course']))
                      @php $course = session('data')['course']; @endphp
                      <div class="alert alert-success" role="alert">
                        <span style="font-size: 16px;font-weight: bold;padding-top: 15px;"> شهادة صحيحة<br><span>

                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;">رقم تعريف الشهادة :<span>
                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;"> {{ session('data')['certificateCode'] }}<br><span>

                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;">نؤكد نجاح الطالب / الطالبة : <span>
                        <span style="font-size: 16px;font-weight: bold;padding-top: 15px;">{{ session('user')->name }}<br><span>

                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;">فى دورة :<span>
                        <span style="font-size: 16px;font-weight: bold;padding-top: 15px;">{{ $course->title }}<br><span>

                        <span style="font-size: 16px;font-weight: normal;padding-top: 15px;">التاريخ :<span>
                        <span style="font-size: 16px;font-weight: bold;padding-top: 15px;">{{ $course->date_hijri }} هـ   .   {{ $course->date }} م<br><span>

                      </div>
                    @endif


                    @if(! $siteCount && ! isset(session('data')['course']))
                    <div class="alert alert-danger" role="alert">
                      لا توجد نتائج
                    </div>
                    @endif

                </div>
              </div>
            @endif




      </div>
    </section>
  </div>
</div>

@endsection

@section('script')
<script>
</script>
@endsection
