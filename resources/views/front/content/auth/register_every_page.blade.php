
@guest

<div class="container-fluid" style="padding-top: 10px;">
  <form method="POST" action="{{ route('register_every_page') }}">
        @include('front.include.page_alert')

        <div class="row justify-content-center">

            @csrf
            <input type="hidden" class="form-control" name="join_in" id="join_in"  value="register" ="name" >
            <div class="col-12 col-lg-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror input-default" name="name" maxlength="50" min="{{config('project.max_user_name_chr')}}" id="name" value="{{ old('name') }}" required autocomplete="name" style="color: black" autofocus placeholder="{{ __('trans.name_in_cirt')}}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <span class="invalid-feedback" role="alert" id="validate_error_div" style="font-size: 13px;font-weight: bold;">
                        <strong></strong>
                    </span>
            </div>


<div class="col-12 col-lg-3">
        <input id="phone" class="form-control @error('phone') is-invalid @enderror input-default" name="phone" value="{{ old('phone') }}" required style="color: black" autofocus
        placeholder="{{ __('field.phone') }}">
        @error('phone')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
</div>

            {{--
            <div class="col-12 col-lg-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror input-default" name="email" value="{{ old('email') }}" required autocomplete="email" style="color: black" autofocus placeholder="{{ __('field.email') }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
            </div>
            --}}


            <div class="col-12 col-lg-2" style="text-align: center;">
                <button type="submit" class="btn prim-back-color sec-color" style="padding: 11px 32px;">
                    {{ __('core.header_subscribe_title') }}
                </button>
            </div>


            
            
            
        </div>

  </form>

</div>

@endguest
