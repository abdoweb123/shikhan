
  <div class="card" style="width: 100%;border-radius: 12px;">


    <div class="card-body" style="text-align: center;background-color: #e6f7eb;border-radius: 12px;">
      <h3 class="card-title">{{ $site_name ?? '' }}<br>{{ $term_name }}</h3>
      <h5 class="card-subtitle mb-2 text-muted" style="padding: 0px 0px;color: #186232 !important;">@lang('core.test_term')</h5>
      <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->

      @if (isset($user_tested_term) && $user_tested_term)
        <div style="padding-top: 5px;display: inline-flex;">
          <div style="padding: 0px 20px;"><span> {{ __('field.rate') }} : </span><span>{{ $term_rate }}</span></div>
          <div style="padding: 0px 20px;"><span> {{ __('field.degree') }} : </span><span>{{ $term_degree ? round($term_degree, 2) : '' }}</span></div>
        </div>
      @endif

      @if ($openTermTestToUser)
      <div>
        <a href="{{ route('courses.quiz_term',['site' => $site->slug,'term' => $term->id]) }}" class="btn btn-primary"
            style="background-color: #ea7c24;border: none;padding: 7px 29px;">@lang('core.test_now')
        </a>
      </div>
      @endif

      <div class="d-flex justify-content-center" style="padding-top: 20px;">
        @include('front.include.prev_results_same_term', ['prevResultsSameTerm' => $userResultsOfTerm])
      </div>
      @if (! $userHasTrays)
        <span class="alert alert-warning " >{{__('core.invalid_quiz_count')}} </span>
      @endif

      @if (isset($show_certificate) && $show_certificate)
      <div style="display: inline-flex;text-align: right;padding-top: 7px;">
          &nbsp;{{ __('trans.download_now') }}&nbsp; <div class="loading_div_{{$term_id}}" style="padding: 0px 4px;margin: 0px 4px;"></div>
          <a data-href="{{ route('download-term-certificate', ['id' => $term_test_id, 'type' => 'jpg']) }}" data-id="{{ $term_id }}"
            class="download_image btn but-default">
            <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;{{ __('trans.image') }}&nbsp;</i>
          </a>
          <a data-href="{{ route('download-certificate', ['id' => $term_test_id, 'type' => 'pdf']) }}" data-id="{{ $term_id }}"
            class="download_image btn but-default">
            <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp; Pdf &nbsp;</i>
          </a>
      </div>
      @endif


      @if (isset($term_test_id))
      <div style="display: inline-flex;text-align: right;padding-top: 7px;">
          &nbsp;{{ __('trans.download_now') }}&nbsp; <div class="loading_div_{{$term_id}}" style="padding: 0px 4px;margin: 0px 4px;"></div>
          <a data-href="{{ route('download-term-certificate', ['id' => $term_test_id, 'type' => 'jpg']) }}" data-id="{{ $term_id }}"
            class="download_image btn but-default">
            <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp;{{ __('trans.image') }}&nbsp;</i>
          </a>
          <a data-href="{{ route('download-certificate', ['id' => $term_test_id, 'type' => 'pdf']) }}" data-id="{{ $term_id }}"
            class="download_image btn but-default">
            <i class="fa fa-images" style="font-size: 13px;padding-left: 1px;padding-right: 1px;">&nbsp; Pdf &nbsp;</i>
          </a>
      </div>
      @endif


    </div>
  </div>
