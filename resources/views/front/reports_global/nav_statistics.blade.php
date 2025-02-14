<div class="col-lg-12" style="padding-bottom: 30px;">
  <div class="row justify-content-center">

      <div class="col-lg-2" style="padding-bottom: 10px;">
        <a href="{{ route('front.main_statistics','ar') }}" class="btn btn-secondary w-100">إحصائيات عامة</a>
      </div>

      <div class="col-lg-2" style="padding-bottom: 10px;">
        <a href="{{ route('front.sites_statistics','ar') }}" class="btn btn-secondary w-100">إحصائيات الدبلومات</a>
      </div>

      {{--
      <div class="col-lg-2" style="padding-bottom: 10px;">
        <a href="{{ route('front.report_courses.viewadvanced','ar') }}" class="btn btn-primary w-100">تقارير بالفترات</a>
      </div>

      <div class="col-lg-2" style="padding-bottom: 10px;">
        <form method="POST" action="{{ route('front.report_courses','ar') }}">
          @csrf
          <input type="hidden" name="type" value="sites">
          <button type="submit" class="btn btn-primary w-100">تقارير الدبلومات</button>
        </form>
      </div>

      <div class="col-lg-2" style="padding-bottom: 10px;">
        <form method="POST" action="{{ route('front.report_courses_register','ar') }}">
            @csrf
            <input type="hidden" name="type" value="1">
            <button type="submit" class="btn btn-primary w-100">تقارير اخري</button>
        </form>
      </div>
      --}}

      {{--
      <div class="col-lg-2" style="padding-bottom: 10px;">
        <a href="{{ route('front.prizes.sta_users_by_most_tests_most_degree','ar') }}" class="btn clever-btn w-100" target="_blank"
          style="background-color: #a8db5e;color: #024d02;">تقارير الجوائز</a>
      </div>
      --}}

  </div>
</div>
