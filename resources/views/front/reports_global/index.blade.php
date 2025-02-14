<div class="col-8" style="text-align: right;">
  @php $currentReport = Request::segment(2); @endphp
  <select name="reports" id="reports" onchange="getReport(this)" style="direction: rtl !important;">
    <option value="prizes_sta_users_by_most_tests_most_degree"
          {{ $currentReport == 'prizes_sta_users_by_most_tests_most_degree' ? 'selected' : ''}}>
          تقرير الطلاب واختباراتهم ومتوسط درجات الطالب مرتبين بمتوسط الدرجات
    </option>
    <option value="prizes_sta_users_tested_in_all_his_subscriptions"
          {{ $currentReport == 'prizes_sta_users_tested_in_all_his_subscriptions' ? 'selected' : ''}}>
          تقرير الطلاب الذين اختبروا فى كل ما سجلو فيه
    </option>
    <option value="prizes_sta_users_tests_in_each_site"
          {{ $currentReport == 'prizes_sta_users_tests_in_each_site' ? 'selected' : ''}}>
          تقرير اختبارات الطلاب داخل كل دبلوم
    </option>
    <option value="prizes_sta_users_subscriptions_in_each_site"
          {{ $currentReport == 'prizes_sta_users_subscriptions_in_each_site' ? 'selected' : ''}}>
          تقرير اشتراكات الطلاب داخل كل دبلوم
    </option>
    <option value="prizes_sta_registerd_in_all_courses_and_less_x_courses_not_tested_by_degree"
          {{ $currentReport == 'prizes_sta_registerd_in_all_courses_and_less_x_courses_not_tested_by_degree' ? 'selected' : ''}}>
          الطلاب الذين اختبروا اكثر من 23 دورة
    </option>
    <option value="prizes_sta_users_by_degree_in_each_site"
          {{ $currentReport == 'prizes_sta_users_by_degree_in_each_site' ? 'selected' : ''}}>
          نتائج الطلاب مرتبين بالدرجة على مستوى الدبلوم
    </option>

    <option value="prizes_sta_registered_from_extrnal"
          {{ $currentReport == 'prizes_sta_registered_from_extrnal' ? 'selected' : ''}}>
          الطلاب الذين سجلو حضورهم من الخارج - زووم
    </option>
    <option value="prizes_sta_registerd_in_all_courses"
          {{ $currentReport == 'prizes_sta_registerd_in_all_courses' ? 'selected' : ''}}>
          بحث عن الطلاب
    </option>

    <option value="prizes_sta_users_complete_diplome"
          {{ $currentReport == 'prizes_sta_users_complete_diplome' ? 'selected' : ''}}>
          الجوائز الثانية - كل من اكمل دبلوم مرتبين بمتوسط الدرجة على مستوى الدبلوم
    </option>

  </select>
</div>

<div class="col-3">
  <a href="{{ route('front.v_report_courses','ar') }}" class="btn clever-btn w-100" target="_blank">الرجوع لتقارير الدورات</a>
</div>

<script>
function getReport(me) {
  var rptAlias = me.value;
  var url = "https://" + window.location.hostname + '/' + '{{ app()->getlocale() }}' + '/' + rptAlias;
  // console.log(url);
  window.location =  url;
}
</script>
