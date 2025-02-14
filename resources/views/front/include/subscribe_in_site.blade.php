

@inject('globalService', 'App\Services\GlobalService')

<!-- condition2: null means this property doesn't exixts in the site, if there is this property means this site has spacific sites to finish before subscribe -->
@if ($siteToSubscribe->userSiteConditionsDetails->condition2Status->isNotEmpty())
      @if ($siteToSubscribe->userSiteConditionsDetails->userFinishDependents === true ) <!-- user finished depndents site -->
            <div id="div_sub_{{ $siteToSubscribe->id }}" class="{{! $siteToSubscribe->isUserSubscribedInSite ? 'show_div' : 'hide_div' }}">
              <button att-id="{{ $siteToSubscribe->id }}" att-URL="{{ route('diplomas.subscribers', ['site'=>$siteToSubscribe->slug] ) }}"
                  class="btn btn-success subscribe_in_site but-more" style="padding: 10px 30px;">{{ __('trans.subscribe_in_diploma') }}
              </button>
            </div>
            <div class="row justify-content-md-center">
              <div id="div_already_sub_{{ $siteToSubscribe->id }}" class="{{ $siteToSubscribe->isUserSubscribedInSite ? 'show_div' : 'hide_div' }} alert alert-success"  style="border-color: #b5b5b5;"><i class="fas fa-users" style="font-size: 22px;"></i>{{ __('trans.subscribed') }}</div>
            </div>
      @else
            <div class="row justify-content-md-center">
                <div class="col-md-6 alert alert-danger">التسجيل فقط لمن أنهى {{ $siteToSubscribe->userSiteConditionsDetails->condition2Status->siteDependentsTitles }}</div>
            </div>
      @endif


<!-- condition1:  must fifnish at least one diplome to subscribe -->
@elseif ($siteToSubscribe->userSiteConditionsDetails->condition1Status->isNotEmpty())
      @if ($siteToSubscribe->userSiteConditionsDetails->condition1Status->userFinishedAtLeastOneSite)
          <div id="div_sub_{{ $siteToSubscribe->id }}" class="{{! $siteToSubscribe->isUserSubscribedInSite ? 'show_div' : 'hide_div' }}">
            <button att-id="{{ $siteToSubscribe->id }}" att-URL="{{ route('diplomas.subscribers', ['site'=>$siteToSubscribe->slug] ) }}" @if(isset($reloadpage)) att-reloadpage="true" @endif
                class="btn btn-success subscribe_in_site but-more" style="padding: 10px 30px;font-weight: bold;">{{ __('trans.subscribe_in_diploma') }}
            </button>
          </div>
          @php $userTestsCountInSite = Auth::guard('web')->user()->testsCount($siteToSubscribe->id); @endphp
          @if ( $userTestsCountInSite == 0 )
              <div id="div_cancel_sub_{{ $siteToSubscribe->id }}" class="{{ $siteToSubscribe->isUserSubscribedInSite ? 'show_div' : 'hide_div' }}" style="padding: 4px 14px;">
                <button att-id="{{ $siteToSubscribe->id }}" att-URL="{{ route('diplomas.unsubscribers', ['site'=>$siteToSubscribe->slug] ) }}" @if(isset($reloadpage)) att-reloadpage="true" @endif
                    class="btn unsubscribe_in_site" style="border: none;padding: 10px 30px;font-weight: bold;color: red;">{{ __('trans.cancel_subscribe') }}
                </button>
              </div>
          @else
              <div class="row justify-content-md-center">
                  <div id="div_already_sub_{{ $siteToSubscribe->id }}" class="{{ $siteToSubscribe->isUserSubscribedInSite ? 'show_div' : 'hide_div' }} alert alert-success"  style="border-color: #b5b5b5;"><i class="fas fa-users" style="font-size: 22px;"></i>
                     {{ __('trans.subscribed') }}  - {{ $userTestsCountInSite ? __('trans.tested').' '.$userTestsCountInSite.' '.__('trans.test')  : '' }}</div>
              </div>
          @endif
      @else
          <div class="row justify-content-md-center">
              <div class="col-md-6 alert alert-danger">التسجيل فقط لمن أنهى دبلوم واحد على الأقل من الدبلومات الجاهزة</div>
          </div>
      @endif


<!-- no conditions -->
@else
      <div id="div_sub_{{ $siteToSubscribe->id }}" class="{{! $siteToSubscribe->isUserSubscribedInSite ? 'show_div' : 'hide_div' }} div_sub_{{ $siteToSubscribe->id }}">
          <button att-id="{{ $siteToSubscribe->id }}" att-URL="{{ route('diplomas.subscribers', ['site'=>$siteToSubscribe->slug] ) }}"
              @if(isset($reloadpage)) att-reloadpage="true" @endif
              class="btn btn-success subscribe_in_site but-more" style="padding: 10px 30px;font-weight: bold;">{{ __('trans.subscribe_in_diploma') }}
          </button>
      </div>
      @php $userTestsCountInSite = Auth::guard('web')->user()->testsCount($siteToSubscribe->id); @endphp
      @if ( $userTestsCountInSite == 0 )
          <div class="{{ $siteToSubscribe->isUserSubscribedInSite ? 'show_div' : 'hide_div' }} div_cancel_sub_{{ $siteToSubscribe->id }}" style="padding: 4px 14px;">
              <button att-id="{{ $siteToSubscribe->id }}" att-URL="{{ route('diplomas.unsubscribers', ['site'=>$siteToSubscribe->slug] ) }}"
                  @if(isset($reloadpage)) att-reloadpage="true" @endif
                  class="btn unsubscribe_in_site" style="border: none;padding: 10px 30px;font-weight: bold;color: red;">{{ __('trans.cancel_subscribe') }}
              </button>
          </div>
      @else
          <div class="row justify-content-md-center">
              <div class="{{ $siteToSubscribe->isUserSubscribedInSite ? 'show_div' : 'hide_div' }} div_already_sub_{{ $siteToSubscribe->id }} alert alert-success" style="border-color: #b5b5b5;"><i class="fas fa-users" style="font-size: 22px;"></i>
                 {{ __('trans.subscribed') }}  - {{ $userTestsCountInSite ? __('trans.tested').' '.$userTestsCountInSite.' '.__('trans.test')  : '' }}
              </div>
          </div>
      @endif
@endif
