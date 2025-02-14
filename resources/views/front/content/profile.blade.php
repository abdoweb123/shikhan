@extends('front.layouts.the-index')
@section('head')
  <style>
    select#country ,select#gender {
        display: block !important;
    }
    .nice-select.form-control {
      display: none !important;
      }
      input#code_country {
      direction: ltr;
      padding: 5px;
      }
      .register-now .register-contact-form .forms .form-control {
        color: #000000bf !important;
      }
  </style>
@endsection
@section('content')


<div class="mx-auto" style="width: 1px;display: block;"></div>

@if ( Session::has('photo_deleted'))
<div class="alert alert-success" style="text-align: center;" role="alert">
  {{ Session::get('photo_deleted') }}
</div>
@endif


    {{--@include('front.units.breadcrumb',['routes' => [['name' => __('meta.title.profile')]]])--}}
    {{--@include('front.units.notify')--}}


    @include('front.include.global_alert')


    @if(session()->has('user_doesnt_have_id_to_get_cirt'))
      <div class="container" style="text-align: center;">
        <div class="alert alert-danger" role="alert">
          {{ session()->get('user_doesnt_have_id_to_get_cirt') }}
        </div>
      </div>
    @endif





<div class="container" style="padding-top: 30px;">
<div class="row flex-lg-nowrap">

  <div class="col">
    <div class="row">
      <div class="col mb-3">
        <div class="card" style="border-radius: 20px;">
          <div class="card-body">
            <div class="e-profile">
              <form method="POST" action="{{ route('profile') }}" enctype="multipart/form-data" autocomplete="false">
                @csrf
                <div class="row">

                  <!-- image and details -->
                  <div class="col-12 col-sm-auto mb-3">
                    <div class="mx-auto" style="width: 140px;border-radius: 10px;overflow: hidden;">
                      <div class="d-flex justify-content-center align-items-center rounded" style="height: 140px; background-color: rgb(233, 236, 239);">
                          <img src="data:image/jpeg;base64,{{ $fields->AvatarPath64 }}" style="width: 140px;">
                          {{--<img src="{{ $fields->AvatarPath }}" style="width: 140px;">--}}
                      </div>
                    </div>
                  </div>
                  <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                    <div class="text-center text-sm-right mb-2 mb-sm-0">
                      <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">{{ __(@$fields['name']) }}</h4>
                      <p class="mb-0">{{ __(@$fields['email']) }}</p>
                      <div class="text-muted"><small>id: {{ getAuthId() }}</small></div>
                      <div class="mt-2">
                        <input type="file" id="input-file-now-custom-1" name="avatar" />
                        <br>
                        <span style="font-size: 14px;color: #994040;">الصورة لا تزيد عن 1 ميجا و بامتداد jpg , jpeg, png</span>
                      </div>
                    </div>
                  </div>

                </div>
                <ul class="nav nav-tabs">
                  <!-- <li class="nav-item"><a href="" class="active nav-link">Settings</a></li> -->
                </ul>
                <div class="tab-content pt-3">
                  <div class="tab-pane active">
                    <div class="col-md-12" style="text-align: center;">
                      @include('front.units.notify')
                    </div>

                      <div class="row" style="text-align: right;">
                        <div class="col">
                          <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>الإسم</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror input-default" name="name" id="name"
                                  {{ 1==1 ? 'disabled' : '' }} maxlength="50" value="{{ old('name' , $fields['name']) }}" required autocomplete="name" autofocus placeholder="{{ __('field.name') }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>الإسم بلغة أخرى</label>
                                <input type="text" class="form-control @error('name_lang') is-invalid @enderror input-default" name="name_lang" id="name_lang" maxlength="50" value="{{ old('name_lang' , $fields['name_lang']) }}"  autocomplete="name_lang"  placeholder="{{ __('field.name_lang') }}">
                                @error('name_lang')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>البريد الإلكترونى</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror input-default" name="email" value="{{ old('email' , $fields['email']) }}" required autocomplete="email" autofocus placeholder="{{ __('field.email') }}">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>تاريخ الميلاد</label>
                                <input id="birthday" type="date" class="form-control @error('birthday') is-invalid @enderror input-default" name="birthday" value="{{ $fields['birthday'] }}"   placeholder="{{ __('field.birthday') }}">
                                @error('birthday')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>النوع</label>
                                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror input-default">
                                    @foreach(['0' => __('core.select') ,'1' =>  __('core.male')  ,'2' =>  __('core.female')  ] as $id => $title)
                                        <option {{ $fields['gender'] == $id ? 'selected' : '' }} value="{{ $id }}"> {{ $title }} </option>
                                    @endforeach
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>المؤهل العلمى</label>
                                <input id="qualification" type="text" class="form-control @error('qualification') is-invalid @enderror input-default" name="qualification" value="{{ old('qualification' , $fields['qualification']) }}"    placeholder="{{ __('field.qualification') }}">
                                @error('qualification')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>الجوال</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror  input-default" name="phone" value="{{ old('phone' , $fields['phone']) }}"   placeholder="{{ __('field.phone') }}">
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>الدول</label>
                                @isset($countries)
                                  <select  class="form-control  input-default" name="country_id" id="country">
                                      @foreach($countries as $country)
                                          <option value="{{$country->id }}"  attr-code="{{$country->phonecode}}"{{old('country_id', $fields['country_id']) == $country->id ? 'selected':''}}>{{ $country->name }}</option>
                                      @endforeach
                                  </select>
                                  @error('country_id')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                                @endisset
                              </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label></label>

                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>تعديل كلمة المرور</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror  input-default" name="password"  autocomplete="new-password" placeholder="{{ __('field.password') }}">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label>تأكيد كلمة المرور</label>
                                <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror  input-default" autocomplete="off" name="password_confirmation"  placeholder="{{ __('field.confirm_password') }}">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                              <div class="form-group">
                                <label></label>

                              </div>
                            </div>
                          </div>
                          <div class="row" style="margin-top: 15px;">
                            <div class="col-12 col-sm-auto mb-3">
                              <div class="mx-auto" style="width: 200px;">
                                <div class="d-flex justify-content-center align-items-center" style="height: 140px;width: 200px; background-color: rgb(233, 236, 239);overflow: hidden;border-radius: 10px;">
                                    <img src="data:image/jpeg;base64,{{ $fields->IdImagePath64 }}" style="width: 200px;">
                                    {{--<img src="{{ $fields->IdImagePath }}" style="width: 200px;">--}}
                                </div>
                              </div>
                            </div>

                            <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                              <div class="text-center text-sm-right mb-2 mb-sm-0">
                                <div class="form-group row">
                                  <label>رقم الهوية</label>
                                  <input id="id_number" type="text" class="form-control @error('id_number') is-invalid @enderror  input-default" name="id_number" maxlength="25" value="{{ old('id_number' , $fields['id_number']) }}">
                                </div>
                                <div class="mt-2" style="text-align: right;">
                                      <label>صورة الهوية (البطاقة الشخصية أو الباسبور) : </label><br>
                                  <input type="file" id="input-file-now-custom-2" name="id_image" class="file-upload" />
                                  <br>
                                  <span style="font-size: 14px;color: #994040;">الصورة لا تزيد عن 1 ميجا و بامتداد jpg , jpeg, png</span>
                                </div>
                              </div>
                            </div>

                          </div>

                        </div>
                      </div>

                      <div class="row">
                        <div class="col d-flex justify-content-end">
                          <button class="btn prim-back-color sec-color sec-color-border" style="padding: 10px 50px;font-weight: bold;" type="submit">{{ __('core.save') }}</button>
                        </div>
                      </div>


                  </div>
                </div>
              </form>

              <div class="row" style="border-top: 1px solid #d0d0d0;margin: 15px 3px;padding: 14px 0px;">
                <form method="POST" action="{{ route('clear_my_photo') }}" enctype="multipart/form-data">
                  @csrf
                    <button type="submit" class="btn but-more" style="background-color: #e15c5c;">
                        حذف الصورة الشخصية
                    </button>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>



      <!-- <div class="col-12 col-md-3 mb-3">
        <div class="card mb-3">
          <div class="card-body">
            <div class="px-xl-3">
              <button class="btn btn-block btn-secondary">
                <i class="fa fa-sign-out"></i>
                <span>Logout</span>
              </button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <h6 class="card-title font-weight-bold">Support</h6>
            <p class="card-text">Get fast, free help from our friendly assistants.</p>
            <button type="button" class="btn btn-primary">Contact Us</button>
          </div>
        </div>
      </div> -->


    </div>

  </div>
</div>
</div>




<div style="padding-bottom: 1px;padding-top: 1px;">
  <div style="text-align: center;width: 100%;text-align: right;">
        <a class="btn btn-danger" style="cursor: auto;background-color: white;color: gray;border: white;" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            {{--<i class="fas fa-sign-out-alt"></i>
            @lang('core.logout')--}}.
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
  </div>
</div>

@endsection
