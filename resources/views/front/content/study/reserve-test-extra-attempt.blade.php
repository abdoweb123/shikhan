
  <div class="container-fluid">
    <div class="row" style="margin: 10px;">

      <div class="col-lg-12">
        <div class="section-heading">
          <h6></h6>
          <div id="reserve_extra_try_html_div_error"></div>
          <h2>{{ __('domain.reserve_extra_try_content') }} - {{ $test->extra_try_fee }} {{ getDefaultCurrency()->getSign() }}</h2>

          <!-- jaca script code in (show-course.blade) -->
          <form method="post" id="reserve_extra_try_form" action="{{ route('front.enrolls.courses.tests.reserve-extra-attempt', ['enrolled' => $enrolled->id, 'course' => $course->id, 'test' => $test->id]) }}" enctype="multipart/form-data">
            <div class="row" style="">
                <div class="col-lg-3 col-md-3">
                  <span class="category">{{ __('general.amount') }}</span><br>
                  <input type="number" name="total_pay_amount" required class="form-control">
                </div>
                <div class="col-lg-3 col-md-3">
                  <span class="category">{{ __('general.currency') }}</span><br>
                  <x-front.dd-currencies
                    :currencies="$currencies"
                    isRequired="true"
                    fieldName="currency_id"
                  />
                </div>
                <div class="col-lg-3 col-md-3">
                  <span class="category">{{ __('domain.enter_pay_image') }}</span><br>
                  <input type="file" name="total_pay_image" id="total_pay_image" >
                </div>

                <!-- submit -->
                <div class="col-lg-2 col-md-2">
                  <span class="category"></span><br>
                  <x-front.buttons.but_submit />
                </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
