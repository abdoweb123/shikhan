<form method="POST" class="row justify-content-center" action="{{ route('front.prizes.subscripe') }}">
    @csrf

    <div class="row" style="padding: 10px">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @if (session()->has('success'))
          <div class="alert alert-success">
              <ul>
                  <li>{!! session()->get('success') !!}</li>
              </ul>
          </div>
        @endif
    </div>


    <div class="row" style="padding: 30px;box-shadow: 0px 6px 13px #08175112;border-radius: 20px;border: 1px solid #ccf2cc;">

      <div class="col-12 col-lg-12" style="text-align: center;padding: 0px 0px 30px 0px;font-size: 22px;color: #1bd742;font-weight: bold;">
        @if($alreadySubscribed) انت مسجل إذا اكملت بياناتك @else التسجيل للترشح للجائزة @endif
      </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"  value="{{ old('name',auth()->user()->name) }}" required autocomplete="name" maxlength="50" style="color: black" autofocus placeholder="{{ __('field.name') }} *">
                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email',auth()->user()->email) }}" required maxlength="50" autocomplete="email" style="color: black" autofocus placeholder="{{ __('field.email') }} *">
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>


        <div class="col-12 col-lg-6">
            <div class="form-group">
              @isset($countries)
                <select  class="form-control" name="country_id" id="country">
                    <option value="" {{old('country_id') == null? 'selected':''}}>{{ __('field.country') }}</option>
                    @foreach($countries as $country)
                        <option value="{{$country->id }}"  attr-code="{{$country->phonecode}}"{{old('country_id', auth()->user()->country_id) == $country->id ? 'selected':''}}>{{ $country->name }}</option>
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
        <div class="col-12 col-lg-6">
            <div class="form-group">
                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone' , auth()->user()->phone ) == 1 ? null : old('phone' , auth()->user()->phone )}}"   placeholder="{{ __('field.phone') }}">
                @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>


        <div class="col-12 col-lg-6">
            <div class="form-group">
                <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                    @foreach(['0' => __('core.select') ,'1' =>  __('core.male')  ,'2' =>  __('core.female')  ] as $id => $title)
                        <option {{  old('gender' ,auth()->user()->gender) == $id ? 'selected' : '' }} value="{{ $id }}"> {{ $title }} </option>
                    @endforeach
                </select>
                @error('gender')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="form-group">
              <label  class="control-label" for="birthday">{{ __('field.birth_date') }}</label>
                <input id="birthday" type="date" class="form-control @error('birthday') is-invalid @enderror" name="birthday" value="{{ old('birthday' ,auth()->user()->birthday) }}"   placeholder="{{ __('field.birthday') }}">
                @error('birthday')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-12 col-lg-6">
              <input id="whats_app" class="form-control @error('whats_app') is-invalid @enderror" type="number" name="whats_app"  value="{{old('whats_app',auth()->user()->whats_app)}}" maxlength="20" required placeholder="{{ __('field.whats_app') }} *">
                @error('whats_app')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
        </div>


        <div class="col-12 col-lg-6">
            <div class="form-group">
                <input id="qualification" type="text" class="form-control @error('qualification') is-invalid @enderror" name="qualification" value="{{ old('qualification' , auth()->user()->qualification) }}"    placeholder="{{ __('field.qualification') }}">
                @error('qualification')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-12 col-lg-6">
              <input id="notes" class="form-control @error('notes') is-invalid @enderror" type="text" name="notes"  value="{{old('notes',auth()->user()->notes)}}" placeholder="{{ __('field.notes') }}">
                @error('notes')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
        </div>
        <div class="col-12" style="text-align: center;padding-top: 30px;">
            <button type="submit" class="btn clever-btn w-100" style="background-color: #25b981;color: white;">
              @if($alreadySubscribed) حدث بياناتك @else {{ __('core.subscribe_reg') }} @endif
            </button>
        </div>

    </div>
</form>
