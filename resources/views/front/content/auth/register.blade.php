<!-- for landing page ( route landing.index ) -->
<!-- if user enter site from face adv to landing1 we put a flag in session to display -->
<!-- register page but without the header menu -->

@php $extend = 'the-index'; @endphp

@if (Session::has('landing'))
  @php $extend = 'landing1'; @endphp
  {{ Session::forget('landing') }}
@endif
@extends('front.layouts.'.$extend)


@section('head')
    <!-- Styles -->
    @if(LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
    <link rel="stylesheet" href="{{ asset('assets/front/style_rtl.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('assets/front/style.css') }}">
    @endif

    <!-- select2 -->
    {{--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}
    <link rel="stylesheet" href="{{ asset('assets/select2/select2.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
 <style>
     .header-area {
        position: absolute !important;
     }
    .section-padding-100-0 {
        padding-top: 145px;
        padding-bottom: 0;
    }
    .register-now .register-contact-form .forms .form-control {
         color: rgb(0 0 0) !important;
    }
    select#country {
        display: block !important;
    }
    .nice-select.form-control {
      display: none !important;
    }
    input#code_country {
        direction: ltr;
        padding: 5px;
    }
    .ul-taps{
      width: 100%;
      display: inline-flex;
    }
    .ul-taps li{

      text-align: center;
      width: 50%;
    }
    .ul-taps li h4{
        margin-bottom: 15px !important;
          margin-top: -18px !important;
          border: 2px solid #e8bb8f;
          box-shadow: 1px 2px 12px 0px #eabe92;
          padding: 10px;
          border-radius: 5px;
          color: #00c1e8;
    }
    h4.unactive {
          background-color: #d89a5f;
          color: #fff !important;
    }
    h4.unactive:hover {
        background-color: #fff;
        color: #d89a5f !important;
    }
    button.btn.clever-btn.w-100 {
      color: #fff;
      background: #2266ae;
    }
    ul.ul-taps {
        margin-bottom: 25px;
    }
    .shadow_01{
      box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 2px, rgba(0, 0, 0, 0.07) 0px 2px 4px, rgba(0, 0, 0, 0.07) 0px 4px 8px, rgba(0, 0, 0, 0.07) 0px 8px 16px, rgba(0, 0, 0, 0.07) 0px 16px 32px, rgba(0, 0, 0, 0.07) 0px 32px 64px;
    }
    @media only screen and (max-width: 767px){
      .register-now .register-now-countdown {
          display: none !important;
        }
      .register-now .register-contact-form {
          margin-top: 45px !important;
          padding: 6px !important;
      }
      .ul-taps li h4 {
          font-size: 18px !important;
          padding: 4px;
          margin: 7px 2px !important;
          font-weight: 900;
      }
    }
 </style>
@endsection
@section('content')


<div class="col-12" style="text-align: center; padding: 15px 0px;background-color: #e2e8fc;">
  @include('front.include.support_videos.create_user')
</div>


<section class="register-now  justify-content-between align-items-center" style="padding-top: 10px;">


<form method="POST" class="row justify-content-center" action="{{ route('register') }}" enctype="multipart/form-data">

  <div class="register-contact-form mb-50" style="flex: 0 0 60%;">
    <div class="container-fluid">
      @include('front.include.page_alert')
      <div class="row">
        <div class="col-12" style="text-align: center;">
            <div class="forms">


              <h4>{{ __('core.register') }}</h4>
              <!-- <div class="alert alert-success" role="alert" style="text-align: center;">
                  {{ __('trans.register_still_free') }}
              </div> -->

              @csrf
              <input type="hidden" class="form-control" name="join_in" id="join_in"  value="register" ="name" >
              <div class="row">



                {{--
                <div class="col-12" style="text-align: center;">
                    <a  href="{{ route('socialite.index','facebook') }}" class="btn btn-social btn-facebook"
                    style="color: #fff;background-color: #3b5998;border-radius: 10px;padding: 10px 30px;" rel="nofollow">
                      <i class="fa fa-facebook fa-fw"></i>
                    </a>
                </div>
                --}}


                <div class="col-12" style="text-align: center;padding: 10px 0px;">
                    <a  href="{{ route('socialite.index','google') }}" class="btn btn-social btn-google"
                    style="color: #fff;background-color: #ea4234;border-radius: 10px;padding: 10px 30px;" rel="nofollow">
                      <i class="fa fa-google fa-fw"></i>
                    </a>
                </div>


              {{--
              <div class="col-12" style="text-align: center;padding: 10px 0px;">
                  <a  href="{{ route('socialite.index.register','google') }}" class="btn btn-social btn-google"
                  style="color: #fff;background-color: #ea4234;border-radius: 10px;padding: 13px 7px;width: 220px;" rel="nofollow">
                    <i class="fa fa-google fa-fw"></i>التسجيل بحساب جوجل
                  </a>
              </div>

              <div class="col-12" style="text-align: center;">
                  <a  href="{{ route('socialite.index.register','facebook') }}" class="btn btn-social btn-facebook"
                  style="color: #fff;background-color: #3b5998;border-radius: 10px;padding: 13px 7px;width: 220px;" rel="nofollow">
                    <i class="fa fa-facebook fa-fw"></i>التسجيل بحساب الفيسبوك
                  </a>
              </div>

              <div class="col-12" style="text-align: center;padding: 10px 0px;">
                  <a  href="{{ route('socialite.index.register','twitter') }}" class="btn btn-social btn-google"
                  style="color: #fff;background-color: #55acee;border-radius: 10px;padding: 13px 7px;width: 220px;" rel="nofollow">
                    <i class="fa fa-twitter fa-fw"></i>التسجيل بحساب تويتير
                  </a>
              </div>

              <div class="col-12" style="text-align: center;padding: 10px 80px;">
                <hr style="border-color: #cecece;">
                <span>أو يمكنك التسجيل بالبريد الشخصى</span>
              </div>
              --}}

                      <div class="row shadow_01" style="text-align: center; padding-top: 25px;border-radius: 15px;">

{{--                          <div class="col-12 col-lg-6">--}}
{{--                              <div class="form-group">--}}
{{--                                  <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" maxlength="50" min="{{config('project.max_user_name_chr')}}" id="name"--}}
{{--                                    value="{{ old('name', $register_every_page['name']) }}" required autocomplete="name" style="color: black"  placeholder="{{ __('trans.name_in_cirt') }}">--}}
{{--                                  @error('name')--}}
{{--                                  <span class="invalid-feedback" role="alert">--}}
{{--                                      <strong>{{ $message }}</strong>--}}
{{--                                  </span>--}}
{{--                                  @enderror--}}
{{--                                  <span class="invalid-feedback" role="alert" id="validate_error_div" style="font-size: 13px;font-weight: bold;">--}}
{{--                                      <strong></strong>--}}
{{--                                  </span>--}}
{{--                              </div>--}}
{{--                          </div>--}}


                          <div class="col-12 col-lg-12">
                              <div class="form-group">
                                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                  value="{{ old('email', $register_every_page['email']) }}" autocomplete="email" style="color: black" required placeholder="{{ __('trans.email') }}">
                                  @error('email')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>
                          </div>


{{--                          <div class="col-12 col-lg-6">--}}
{{--                              <div class="form-group" style="display: flex;">--}}
{{--                                  <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"--}}
{{--                                  value="{{ old('phone', $register_every_page['phone']) }}" required  style="color: black" maxlength="15" placeholder="{{ __('field.whats_app') }}">--}}
{{--                                  @error('phone')--}}
{{--                                  <span class="invalid-feedback" role="alert">--}}
{{--                                      <strong>{{ $message }}</strong>--}}
{{--                                  </span>--}}
{{--                                  @enderror--}}
{{--                              </div>--}}
{{--                          </div>--}}

{{--                          <div class="col-12 col-lg-6">--}}
{{--                              <div class="form-group">--}}
{{--                                <select class="form-control @error('phone_code_id') is-invalid @enderror" required name="phone_code_id">--}}
{{--                                    <option value="0" attr-code="0" {{old('phone_code_id') == 0 ? 'selected':''}}>{{ __('words.phone_code') }} </option>--}}
{{--                                    @foreach($countries as $country)--}}
{{--                                        <option value="{{$country->id }}" attr-code="{{$country->phonecode}}" {{old('phone_code_id') == $country->id ? 'selected':''}}>{{ $country->name }} - {{ $country->phonecode }}+</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}

{{--                                @error('phone_code_id')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                    <strong>{{ $message }}</strong>--}}
{{--                                </span>--}}
{{--                                @enderror--}}
{{--                              </div>--}}
{{--                          </div>--}}



                          {{--
                          <div class="col-12 col-lg-6">
                              <div class="form-group">
                                  <input id="whats_app" type="text" class="form-control @error('whats_app') is-invalid @enderror" name="whats_app"
                                  value="{{ old('whats_app') }}" required style="color: black" maxlength="15" placeholder="{{ __('field.whats_app') }}">
                                  @error('whats_app')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>
                          </div>
                          --}}




{{--                          <div class="col-12 col-lg-6">--}}
{{--                            <div class="form-group">--}}
{{--                              @isset($countries)--}}
{{--                                <select  class="form-control @error('country_id') is-invalid @enderror" required name="country_id" id="country">--}}
{{--                                    @foreach($allowedCountries as $country)--}}
{{--                                        <option value="{{$country->id }}"  attr-code="{{$country->phonecode}}" {{old('country_id') == $country->id ? 'selected':''}}>{{ $country->name }}</option>--}}
{{--                                    @endforeach--}}
{{--                                    <option value="" {{old('country_id') == null ? 'selected':''}}>{{ __('words.other') }}</option>--}}
{{--                                </select>--}}
{{--                                @error('country_id')--}}
{{--                                <span class="invalid-feedback" role="alert">--}}
{{--                                    <strong>{{ $message }}</strong>--}}
{{--                                </span>--}}
{{--                                @enderror--}}
{{--                              @endisset--}}
{{--                            </div>--}}
{{--                          </div>--}}

{{--                          <div class="col-12 col-lg-6">--}}

{{--                          </div>--}}

                            {{--
                            <div class="form-group" id='country_name_div'>
                                <input id="country_name" type="text" class="form-control @error('country_name') is-invalid @enderror" name="country_name" maxlength="100"
                                value="{{ old('country_name') }}" style="color: black"  placeholder="{{ __('words.country') }}">
                                @error('country_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                          </div>
                          --}}


                          {{--
                          <div class="col-12 col-lg-6">
                              <div class="form-group">
                                  <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" maxlength="100"
                                  value="{{ old('city') }}" required style="color: black"  placeholder="{{ __('field.city') }}">
                                  @error('city')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                  @enderror
                              </div>
                          </div>
                          --}}




                          <div class="col-12 col-lg-6">
                              <div class="form-group">
                                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" style="color: black" placeholder="{{ __('trans.password') }}">
                                  @error('password')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                          </div>

                          <div class="col-12 col-lg-6">
                              <div class="form-group">
                                  <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" style="color: black" required autocomplete="current-password" placeholder="{{ __('trans.password-confirm') }}">
                                  @error('password_confirmation')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                          </div>

                          {{--
                          <div class="col-12 col-lg-6">
                              <div class="form-group">
                                  <input type="text" value="{{ old('code') }}" class="form-control @error('code') is-invalid @enderror" name="code" style="color: black" maxlength="10" placeholder="{{ __('trans.enter_code_if_you_have') }}">
                                  @error('code')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                          </div>
                          --}}




                          <div class="col-12" style="visibility: collapse; display: none">
                            <div class="row">

                                <div class="col-12" style="visibility: collapse;">
                                  Vipi nitalipa ada ya masomo (fees)?
                                  Unaweza lipa kupitia:
                                  Au Mpesa namba: +245790305852
                                  Ni ngapi  gharama ya masomo?
                                  Gharama ya masomo ni dolari hamsini (50 dollar) au Kenya shilling: 6500,  Tanzania shilling:117,000
                                  Na kuna punguzu la kushajiisha kwa wanafunzi  wale wanaojisajili mapema40%
                                </div>



                                <div style="visibility: collapse;">
                                  {{ __('trans.enter_amount_if_you_paied') }}
                                  <div class="col-12 col-lg-6">
                                      <div class="form-group">
                                          <input type="number" step="0.01" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" name="amount" style="color: black" maxlength="10" placeholder="{{ __('trans.amount') }}">
                                          @error('amount')
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                              </span>
                                          @enderror
                                      </div>
                                  </div>

                                  <div class="col-12 col-lg-6">
                                      <div class="form-group">
                                          <input type="file" class="form-control @error('pay_image') is-invalid @enderror" name="pay_image" placeholder="{{ __('trans.pay_image') }}">
                                          @error('pay_image')
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>{{ $message }}</strong>
                                              </span>
                                          @enderror
                                      </div>
                                  </div>
                                </div>

                            </div>
                          </div>



                          <!-- Diploms -->
{{--                          <div class="col-12" style="text-align: center;">--}}
{{--                              <h6 style="@error('diplome_ids') color: red @enderror">{{ __('trans.select_diplomas_you_want_subscrib') }}</h6>--}}
{{--                              <div class="col-12" style="text-align: center;">--}}
{{--                                <select class="diplome_ids_01" name="diplome_ids[]" multiple="multiple" style="width: 100%;">--}}
{{--                                  @foreach ($sites as $item)--}}
{{--                                      <option value="{{ $item->id }}">{{ $item->name }}</option>--}}
{{--                                  @endforeach--}}
{{--                                </select>--}}
{{--                              </div>--}}
{{--                          </div>--}}
{{--                          @error('diplome_ids')--}}
{{--                              <span class="invalid-feedback" role="alert">--}}
{{--                                  <strong>{{ $message }}</strong>--}}
{{--                              </span>--}}
{{--                          @enderror--}}



                          <div class="col-12" style="text-align: center;padding: 15px 80px;">
                            <button type="submit" class="btn but-login" style="width: 225px;padding: 13px 0px;">
                                {{ __('core.register') }}
                            </button>
                          </div>

                      </div>



                      <div class="col-12" style="text-align: center;padding: 10px 80px;">
                        <span>{{ __('trans.if_you_have_problem')}}</span>
                      </div>


                      <div class="col-12" style="text-align: center;padding: 15px 80px;">
                        <a href="https://t.me/+SJpu57FxUpthNGI8" class="btn but-login" style="width: 225px;padding: 13px 0px;background-color: #ff4b4b !important;">
                            {{ __('trans.i_have_problem') }}
                        </a>
                      </div>


                    </div>


            </div>
        </div>
      </div>
    </div>
  </div>




  </form>

<div>



</div>

</section>

@endsection
@section('script')
<!-- select2 -->
{{--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
<script src="{{asset('assets/select2/select2.full.min.js')}}"></script>

<script>
  $(document).ready(function() {
      $('.diplome_ids').select2({
        placeholder: '{{ __("trans.select") }}'
      });

      $('.diplome_ids_01').select2({
        placeholder: '{{ __("trans.select") }}'
      });

      $('.diplome_ids_02').select2({
        placeholder: '{{ __("trans.select") }}'
      });
  });
</script>

{{--
<script>
  $( "#country" )
    .change(function() {
      var str = "";
      $( "#country option:selected" ).each(function() {
        str = '+' + $( this ).attr('attr-code') ;
      });
      if(str !='+undefined'){
        $('input[name=code_country]#code_country').attr('value',str);
      }
    })
  .trigger( "change" );
</script>
--}}

<script>
document.getElementById('country').addEventListener('change', function () {
  // console.log(this.value);
    var style = this.value == '' ? 'block' : 'none';
    document.getElementById('country_name_div').style.display = style;
});
</script>


<script>
  $("#name").blur(function() {
      $("#validate_error_div").attr("style", "display:none");
      $("#validate_error_div").html( '' );

      let _name = $(this).val();
      let _max_chr = "{{config('project.max_user_name_chr')}}";
      if (_name.length < _max_chr){
        $("#validate_error_div").attr("style", "display:block");
        $("#validate_error_div").html( '{{ __("words.name_at_lest") }}' + _max_chr );
        // return;
      }

      let _token = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
       url: "{{ route('validate_name') }}",
       type:"POST",
       data:{ name: _name, _token: _token},
       success:function(response){
         // console.log(response);
       },
       error: function(error) {
           var response = JSON.parse(error.responseText);
           // var errorString = '<ul>';
           // $.each( response.errors, function( key, value) {
           //     errorString += '<li>' + value + '</li>';
           // });
           errorString = '';
           $.each( response.errors, function( key, value) {
               errorString +=  value + ' ';
           });
           $("#validate_error_div").attr("style", "display:block");
           $("#validate_error_div").html( $("#validate_error_div").html() + ' , ' + errorString );
           // console.log(errorString);
       }
      });
  });
</script>

@endsection
