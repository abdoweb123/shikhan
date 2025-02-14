@extends('front.layouts.report')
@section('head')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
       .for_search {
           background-color: #b3f3cb !important;
       }
    </style>
@endsection
@section('content')


<section class="row" style="background-color: #d4ddfb;padding: 40px;">

  @include('front.reports_global.nav_statistics')

  <div class="col-lg-12" style="text-align: right;">
      <form method="POST" class="row" action="{{ route('front.main_statistics_search','ar') }}">
          @csrf
              <div class="col-lg-2">
                  <div class="form-group">
                    <label for="from">بداية من يوم</label>
                      <input type="date" class="form-control @error('from') is-invalid @enderror" name="from" id="from" value="{{ old('from', $from ?? '') }}"  autocomplete="from" style="color: black" autofocus placeholder="{{ __('field.from') }}">
                      @error('from')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                  </div>
              </div>
              <div class="col-lg-2">
                  <div class="form-group">
                    <label for="to">حتي نهاية يوم </label>
                      <input type="date" class="form-control @error('to') is-invalid @enderror" name="to" id="to" value="{{ old('to', $to ?? '') }}"  autocomplete="to" style="color: black" autofocus placeholder="{{ __('field.to') }}">
                      @error('to')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                  </div>
              </div>
              {{--
              <div class="col-lg-3">
                  <div class="form-group">
                    <label for="site_id">النوع</label>
                      <select  class="form-control" name="oldOrNew" id="oldOrNew">
                              <option value="0" {{ old('oldOrNew') == 0 ? 'selected' : '' }}>دورات المرحلة الأولى</option>
                              <option value="1" {{ old('oldOrNew') == 1 ? 'selected' : '' }}>دورات المرحلة الثانية</option>
                      </select>
                  </div>
              </div>
              --}}
              <!-- <div class="col-lg-3">
                  <div class="form-group">
                    <label for="site_id">اختر الــدبلوم</label>
                    @isset($sites)
                      <select  class="form-control" name="site_id" id="site_id">
                          <option value="" {{old('site_id',$site_id ?? '') == null? 'selected':''}}></option>
                          @foreach($sites as $site)
                              <option value="{{$site->id }}" {{old('site_id',$site_id ?? '') == $site->id ? 'selected':''}}>{{ $site->translation->first() != null ? $site->translation->first()->name : $site->title}}</option>
                          @endforeach
                      </select>
                      @error('site_id')

                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    @endisset
                  </div>
              </div>
              <div class="col-lg-3">
                   <div class="form-group">
                     <label for="course_id">اختر الدورة</label>

                       <select  class="form-control" name="course_id" id="course_id">
                           <option value="" {{old('course_id',$course_id ?? '') == null? 'selected':''}}></option>
                        {{--   @foreach($courses as $course)
                               <option value="{{$course->id }}" {{old('course_id',$course_id ?? '') == $course->id ? 'selected':''}}>{{ $course->translation->first() != null ? $course->translation->first()->name : $course->title}}</option>
                           @endforeach --}}
                       </select>
                       @error('course_id')

                       <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                       </span>
                       @enderror

                   </div>
               </div> -->
              <div class="col-lg-2" style="text-align: center;">
                <div class="form-group">
                  <label for="course_id">.</label>
                  <button type="submit" id="sta_search" att-Search="true" att-URL="{{ route('front.main_statistics_search', ['lang' => 'ar']) }}" class="btn btn-primary w-100">
                    عرض التقرير
                  </button>
                  <div class="spinner-border" role="status" id="sta_search_loading" style="visibility: collapse;">
                    <span class="sr-only">Loading...</span>
                  </div>


                </div>
              </div>


      </form>
  </div>

  <div class="col-lg-12" style="overflow: auto;text-align: right;font-size: 30px;">إحصائيات عامة</div>




  {{--
  <div class="col-lg-12" style="text-align: right;">
      <form method="POST" class="row" action="{{ route('front.main_statistics_search','ar') }}">
          @csrf
              <div class="col-lg-3">
                  <div class="form-group">
                    <label for="site_id"></label>
                      <select  class="form-control" name="oldOrNew" id="oldOrNew">
                                <option value="0" {{ old('oldOrNew') == 0 ? 'selected' : '' }}>الدورات القديمة</option>
                              <option value="1" {{ old('oldOrNew') == 1 ? 'selected' : '' }}>الدورات الجديدة</option>
                      </select>
                  </div>
              </div>
              <div class="col-lg-2" style="text-align: center;">
                <div class="form-group">
                  <label for="course_id">.</label>
                  <button type="submit" class="btn clever-btn w-100">
                    عرض التقرير
                  </button>
                </div>
              </div>
      </form>
  </div>
  --}}


  <!--  main statistics -->
  <div class="col-lg-12" style="overflow: auto;text-align: right;">

      <table class="table table-striped" style="width: 100%;">
        <thead>
          <th>الإحصاء</th>
          <th> المرحلة الاولى</th>
          <th> التاريخ المحدد </th>
          <th> المرحلة الثانية</th>
          <th> التاريخ المحدد </th>
          <th> اجمالى </th>
          <th> اجمالى التاريخ المحدد </th>
        </thead>
        <tbody>
          <tr>
              <td>  اجمالي المسجلين فى الأكاديمية </td>
              <td> {{ $oldOrNew_0['countOfMembers'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_0_countOfMembersSearch"></span>
                {{--
                <!-- @isset($search)  {{ $oldOrNew_0['countOfMembersSearch'] }} @endisset -->
                --}}
              </td>
              <td></td>
              <td class="for_search"></td>
              <td></td>
              <td></td>
          </tr>
          <tr>
              <td>  اجمالي المختبرين  </td>
              <td>{{ $oldOrNew_0['countOfTestedUsers'] }} </td>
              <td class="for_search">
                <span id="oldOrNew_0_countOfTestedUsersSearch"></span>
                {{--
                <!-- @isset($search)  {{ $oldOrNew_0['countOfTestedUsersSearch'] }} / جدد : {{ $oldOrNew_0['countOfTestedUsersOfRegisteredUsersSearch'] }}  @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_1['countOfTestedUsers'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_1_countOfTestedUsersSearch"></span>
                {{--
                <!-- @isset($search)  {{ $oldOrNew_1['countOfTestedUsersSearch'] }} / جدد : {{ $oldOrNew_1['countOfTestedUsersOfRegisteredUsersSearch'] }}  @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_0['countOfTestedUsers'] + $oldOrNew_1['countOfTestedUsers'] }} </td>
              <td class="for_search">
                <span id="oldOrNew_sum_countOfTestedUsersSearch"></span>
              </td>
          </tr>
          <tr>
              <td> عدد الاشخاص الذين اجتازوا دبلوم أو أكثر</td>
              <td>{{ $oldOrNew_0['countSuccessdUsersOfAllSites']  }} / {{ $oldOrNew_0['countSuccessdTestsOfAllSites'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_0_countSuccessdUsersOfAllSitesSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_0['countSuccessdUsersOfAllSitesSearch']  }} / {{ $oldOrNew_0['countSuccessdTestsOfAllSitesSearch'] }} @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_1['countSuccessdUsersOfAllSites']  }} / {{ $oldOrNew_1['countSuccessdTestsOfAllSites'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_1_countSuccessdUsersOfAllSitesSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_1['countSuccessdUsersOfAllSitesSearch']  }}  / {{ $oldOrNew_1['countSuccessdTestsOfAllSitesSearch'] }}  @endisset -->
                --}}
              </td>
              <td>
                {{ $oldOrNew_0['countSuccessdUsersOfAllSites'] + $oldOrNew_1['countSuccessdUsersOfAllSites'] }} /
                {{ $oldOrNew_0['countSuccessdTestsOfAllSites'] + $oldOrNew_1['countSuccessdTestsOfAllSites'] }}
              </td>
              <td class="for_search">
                <span id="oldOrNew_sum_countSuccessdUsersOfAllSitesSearch"></span>
              </td>
          </tr>
          <tr>
              <td> عدد الاشخاص الذين اجتازو دبلوم واحد فقط / اكثر من دبلوم</td>
              <td>{{ $oldOrNew_0['countUsersSuccessedInOneDiplome']  }} / {{ $oldOrNew_0['countUsersSuccessedMoreThanOneDiplome'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_0_countUsersSuccessedInOneDiplomeSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_0['countUsersSuccessedInOneDiplomeSearch']  }} / {{ $oldOrNew_0['countUsersSuccessedMoreThanOneDiplomeSearch'] }} @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_1['countUsersSuccessedInOneDiplome']  }} / {{ $oldOrNew_1['countUsersSuccessedMoreThanOneDiplome'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_1_countUsersSuccessedInOneDiplomeSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_1['countUsersSuccessedInOneDiplomeSearch']  }}  / {{ $oldOrNew_1['countUsersSuccessedMoreThanOneDiplomeSearch'] }}  @endisset -->
                --}}
              </td>
              <td>
                {{ $oldOrNew_0['countUsersSuccessedInOneDiplome'] + $oldOrNew_1['countUsersSuccessedInOneDiplome'] }} /
                {{ $oldOrNew_0['countUsersSuccessedMoreThanOneDiplome'] + $oldOrNew_1['countUsersSuccessedMoreThanOneDiplome'] }}
              </td>
              <td class="for_search">
                <span id="oldOrNew_sum_countUsersSuccessedInOneDiplomeSearch"></span>
              </td>
          </tr>
          <tr>
              <td>  اجمالى الاشتراكات فى الدبلومات</td>
              <td>{{ $oldOrNew_0['countOfSubsSites'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_0_countOfSubsSitesSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_0['countOfSubsSitesSearch'] }} @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_1['countOfSubsSites'] }} </td>
              <td class="for_search">
                <span id="oldOrNew_1_countOfSubsSitesSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_1['countOfSubsSitesSearch'] }} @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_0['countOfSubsSites'] + $oldOrNew_1['countOfSubsSites'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_sum_countOfSubsSitesSearch"></span>
              </td>
          </tr>
          <tr>
              <td> اجمالى المشتركين بدبلومات </td>
              <td>{{ $oldOrNew_0['countOfSubsUsers'] }} </td>
              <td class="for_search">
                <span id="oldOrNew_0_countOfSubsUsersSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_0['countOfSubsUsersSearch'] }} /  جدد : {{ $oldOrNew_0['countOfSubsUsersOfRegisteredUsersSearch'] }} @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_1['countOfSubsUsers'] }} </td>
              <td class="for_search">
                <span id="oldOrNew_1_countOfSubsUsersSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_1['countOfSubsUsersSearch'] }}  @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_0['countOfSubsUsers'] + $oldOrNew_1['countOfSubsUsers'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_sum_countOfSubsUsersSearch"></span>
              </td>
          </tr>
          <!-- <tr>
              <td>  اجمالى الغير مشتركين بدورات </td>
              <td>{{ $oldOrNew_0['countOfNotSubsUser'] }}</td>
              <td class="for_search">@isset($search) {{ $oldOrNew_0['countOfNotSubsUsersSearch'] }} @endisset</td>
              <td>{{ $oldOrNew_1['countOfNotSubsUser'] }}</td>
              <td class="for_search">@isset($search) {{ $oldOrNew_1['countOfNotSubsUsersSearch'] }} @endisset</td>
          </tr> -->
          <tr>
              <td>  اجمالى الاختبارات </td>
              <td>{{ $oldOrNew_0['countOfTests'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_0_countOfTestsSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_0['countOfTestsSearch'] }} @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_1['countOfTests'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_1_countOfTestsSearch"></span>
                {{--
                <!-- @isset($search) {{ $oldOrNew_1['countOfTestsSearch'] }} @endisset -->
                --}}
              </td>
              <td>{{ $oldOrNew_0['countOfTests'] + $oldOrNew_1['countOfTests'] }}</td>
              <td class="for_search">
                <span id="oldOrNew_sum_countOfTestsSearch"></span>
              </td>
          </tr>
          <!-- <tr>
              <td>  اجمالي الغير مختبرين  </td>
              <td>{{ $oldOrNew_0['countOfNotTestedUsers'] }}</td>
              <td class="for_search">@isset($search) {{ $oldOrNew_0['countOfNotTestedUsersSearch'] }} @endisset</td>
              <td>{{ $oldOrNew_1['countOfNotTestedUsers'] }}</td>
              <td class="for_search">@isset($search) {{ $oldOrNew_1['countOfNotTestedUsersSearch'] }} @endisset</td>
          </tr> -->
          {{--
          <!-- ajax اجمالى المشتركين بدورات -->
          <tr>
              <td> اجمالى المشتركين بدورات مع التكرار  </td>
              <td>
                    <div id='oldOrNew_0'></div>
                    <button att-old_or_new="0" att-Search="false" att-URL="{{ route('front.main_statistics_courses_subs', ['lang' => 'ar']) }}"
                        class="btn btn-success sta_courses_subs">احصائية
                    </button>
              </td>
              <td class="for_search">
                    <div id='oldOrNew_0_search'></div>
                    <button att-old_or_new="0" att-Search="true" att-URL="{{ route('front.main_statistics_courses_subs', ['lang' => 'ar']) }}"
                        class="btn btn-success sta_courses_subs">احصائية
                    </button>
              </td>
              <td>
                    <div id='oldOrNew_1'></div>
                    <button id='oldOrNew_1' att-Search="false" att-old_or_new="1" att-URL="{{ route('front.main_statistics_courses_subs', ['lang' => 'ar']) }}"
                        class="btn btn-success sta_courses_subs">احصائية
                    </button>
              </td>
              <td class="for_search">
                    <div id='oldOrNew_1_search'></div>
                    <button att-old_or_new="1" att-Search="true" att-URL="{{ route('front.main_statistics_courses_subs', ['lang' => 'ar']) }}"
                        class="btn btn-success sta_courses_subs">احصائية
                    </button>
              </td>
          </tr>
          --}}
        </tbody>
      </table>

  </div>



  <!-- تقارير قترات جدبد -->
  @isset($search)
  <div class="col-md-6" style="text-align: right;">
      <div style="font-size: 30px;font-weight: bold;padding: 15px 0px 15px 0px;">تقرير الدورات / الفترات</div>
      <div class="row">
        <table style="width: 100%;" id="kt_table_2">
          <tbody>
            @isset($oldOrNew_0['countOfSubsUsersSearch'])
              <tr>
                <td class="th-fs">المسجلين بالموقع :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                <td>{{ $oldOrNew_0['countOfMembersSearch'] }} </td>
                <td>{{ $oldOrNew_1['countOfMembersSearch'] }} </td>
              </tr>
              <tr>
                <td class="th-fs">المسجلين بالدورات  :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                <td>{{ $oldOrNew_0['countOfSubsUsersSearch'] }}</td>
                <td>{{ $oldOrNew_1['countOfSubsUsersSearch'] }}</td>
              </tr>
              <tr>
                <td class="th-fs">المسجلين بالدورات بالتكرار  من :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                <td>{{ $oldOrNew_0['countOfSubsSitesSearch'] }} </td>
              </tr>
              <tr>
                <td class="th-fs">المختبرين  من :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                <td>{{  $oldOrNew_0['countOfTestedUsersSearch'] }}</td>
                <td>{{  $oldOrNew_1['countOfTestedUsersSearch'] }}</td>
              </tr>
            @endisset
          </tbody>
        </table>
      </div>
  </div>
  @endisset




  <!-- sites courses -->
  <div class="col-lg-6" style="overflow: auto;text-align: right;">

      <table class="table table-striped" style="width: 100%;">
        <thead>
          <th>الإحصاء</th>
          <th> الرقم</th>
        </thead>
        <tbody>
          <tr>
              <td> عدد الدبلومات </td>
              <td>{{ $oldOrNew_0['countOfSites'] }}</td>
              <td>{{ $oldOrNew_1['countOfSites'] }}</td>
              <td class="for_search"></td>
          </tr>
          <tr>
              <td> عدد الدورات </td>
              <td>{{ $oldOrNew_0['countOfCourses'] }}</td>
              <td>{{ $oldOrNew_1['countOfCourses'] }}</td>
              <td class="for_search"></td>
          </tr>
          <tr>
              <td> عدد الدورات الفعالة</td>
              <td>{{ $oldOrNew_0['countOfActiveCourses'] }}</td>
              <td>{{ $oldOrNew_1['countOfActiveCourses'] }}</td>
              <td class="for_search"></td>
          </tr>
          <tr>
              <td> عدد الشهادات </td>
              <td>{{  $oldOrNew_0['countOfCertficiations']  }}</td>
              <td>{{  $oldOrNew_1['countOfCertficiations']  }}</td>
              <td class="for_search"></td>
          </tr>
        </tbody>
      </table>

  </div>






  {{--
  <div class="col-lg-12" style="overflow: auto;">

      <table style="width: 100%;">
        <thead>
          <th> عدد الدبلومات </th>
          <th> عدد الدورات </th>
          <th> عدد الدورات الفعالة</th>
          <th> عدد الشهادات </th>
          <th>  اجمالي المسجلين فى الأكاديمية</th>
          <th>  اجمالي المشتركين بدورات </th>
          <th>  اجمالي المشتركين بدورات بالتكرار</th>
          <th>  اجمالى الغير مشتركين بدورات </th>
          <th>  اجمالى الاختبارات </th>
          <th>  اجمالي المختبرين  </th>
          <th>  اجمالي الغير مختبرين  </th>
          <th>  نسبة المختبرين الى اجمالى المسجلين بالموقع   </th>
          <th>  نسبة المختبرين الى اجمالى المشتركين بدورات  </th>
        </thead>
        <tbody>
          <tr >
              <td>{{ $countOfSites }}</td>
              <td>{{ $countOfCourses }}</td>
              <td>{{ $countOfActiveCourses }}</td>
              <td>{{ $old_or_new ? '' : $countOfCertficiations  }}</td>
              <td>{{ $countOfMembers }}</td>
              <td>{{ $countOfSubsUsers }} </td>
              <td>{{ $old_or_new ? '' : $countOfSubs }} </td>
              <td>{{ $old_or_new ? '' : $countOfNotSubsUser }}</td>
              <td>{{ $old_or_new ? '' : $countOfTests }}</td>
              <td>{{ $old_or_new ? '' : $countOfTestedUsers }} </td>
              <td>{{ $old_or_new ? '' : $countOfNotTestedUsers }}</td>
              <td>{{ $old_or_new ? '' : $persOfTestedUsersToAllUsers }} %</td>
              <td>{{ $old_or_new ? '' : $persOfTestedUsersToAllSubs }} %</td>
          </tr>
        @isset($search)
          <tr class="for_search">
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>{{ $countOfMembersSearch }}</td>
              <td>{{ $countOfSubsUsersSearch }} </td>
              <td>{{ $old_or_new ? '' : $countOfSubsSearch }}</td>
              <td>{{ $old_or_new ? '' : $countOfNotSubsUsersSearch }}</td>
              <td>{{ $old_or_new ? '' : $countOfTestsSearch }}</td>
              <td>{{ $old_or_new ? '' : $countOfTestedUsersSearch }}</td>
              <td>{{ $old_or_new ? '' : $countOfNotTestedUsersSearch }}</td>
              <td>{{ $old_or_new ? '' : $persOfTestedUsersToAllUsersSearch }} %</td>
              <td>{{ $old_or_new ? '' : $persOfTestedUsersToAllSubsSearch }} %</td>
          </tr>
        @endisset
        </tbody>
      </table>

  </div>
  --}}























  <div class="col-md-12" style="text-align: right;overflow: auto;">

      <!-- تقارير الدبلومات -->
      @isset($sites_subscriptions_count)
        <div style="font-size: 30px;font-weight: bold;padding: 15px 0px 15px 0px;">تقرير الدبلومات</div>

        <table style="width: 100%;" id="kt_table_1">
          <thead >
            <tr>
              <th> الاســــم</th>
              <th>عدد الدورات فى الدبلوم</th>
              <th>عدد الدورات الفعالة فى الدبلوم</th>
              <th>النسبة</th>
              <th>الحالة</th>
              <th>مجموع الاختبار</th>
              <th>مجموع الاشتراكات</th>
              <th> النسبة </th>
            </tr>
          </thead>
          <tbody>
              @foreach($sites_subscriptions_count as $site_subscription_count)
                <tr>
                  <td>{{ $site_subscription_count['title'] }} </td>
                  <td>{{ $site_subscription_count['courses_count'] }}</td>
                  <td>{{ $site_subscription_count['courses_ative_count'] }}</td>
                  <td>{{ round(    $site_subscription_count['count_test'] / ( $site_subscription_count['count_test'] * $site_subscription_count['courses_ative_count'])    , 2) }}</td>
                  <td>{{$site_subscription_count['status']}}</td>
                  <td>{{ $site_subscription_count['count_test'] }}</td>
                  <td>{{ $site_subscription_count['count_subscriptions'] }}</td>
                  <td>@php $ev= $site_subscription_count['count_subscriptions'] > 0 ? ($site_subscription_count['count_test'] / $site_subscription_count['count_subscriptions']) *100 : 0;@endphp {{round($ev, 2) }} %</td>
                </tr>
              @endforeach
          </tbody>
        </table>

      @endisset











    <!-- سيتم نقل هذا التقرير الى دروب ليست التقارير الجديدة -->
    {{--
    <div class="col-md-7">
      <table class="col-md-12 w-100" id="kt_table_1">
        <thead >
          <tr>
            <th> الاســــم</th>
            @isset($results)
              <th>اسم الدبلوم</th>
            @endisset
            <th>الحالة</th>
            <th>مجموع الاختبار</th>
            <th>مجموع الاشتراكات</th>
            <th> النسبة </th>
          </tr>
        </thead>
        <tbody>
          @isset($results)
            @foreach($results as  $result)
              <tr>
                <?php $alias=$result->alias; ?>
                <td>{{ $result->translation->first() != null ? $result->translation->first()->name : $result->title}}  </td>
                <td>@foreach($result->sites as $site ) @if(! $loop->first) -  @endif {{ $site->translation->first() != null ? $site->translation->first()->name : $site->title}} @endforeach</td>
                <td>{!! $result->exam_at <= date('Y-m-d') && $result->exam_at !=  Null ? '<span  class="is_active" > نشط </span>' : '<span class="is_not_active" > غير نشط </span>' !!}</td>
                <td><a href="{{ route('front.report_courses.users',['lang' => 'ar','course' => $alias]) }}">{{$result->test_results_count}}</a></td>
                <td>{{$result->subscribers_count}}</td>
                <td>@php $ev= $result->subscribers_count > 0 ? ($result->test_results_count / $result->subscribers_count) *100 : 0;@endphp {{round($ev, 2) }} %</td>
              </tr>
            @endforeach
          @endisset
        </tbody>
      </table>
    </div>
    --}}




















    {{--
    <!-- دورات سيتم نقله شاشاة منفصلة - التقرير الاصلى تقارير فترات -->
    <div class="col-md-12" style="text-align: right;padding-top: 20px;">
      <div style="font-size: 30px;font-weight: bold;padding: 15px 0px 15px 0px;">تقرير الدورات / الفترات</div>

      @isset($results)
          <div class="row">
            <table style="width: 100%;" id="kt_table_2">
              <thead>
                <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td class="th-f">  عدد المسجلين فى الأكاديمية </td>
                  <td>{{$countOfMembers}}  مشارك </td>
                  <td class="th-f">عدد المسجلين بالدورات</td>
                  <td>{{$countOfSubsUsersSearch}}</td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td class="th-f"> عدد المسجلين بالدورات بالتكرار </td>
                  <td>{{ $countOfSubsSitesSearch }} </td>
                  <td class="th-f"> عدد المختبرين فى كامل الموقع </td>
                  <td>{{ $countOfTestedUsers }}</td>
                  <td></td>
                  <td></td>
                </tr>
                @isset($countOfSubsUsersSearch)
                  <tr>
                    <td class="th-fs">المسجلين بالموقع :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                    <td>{{$countOfMembersSearch}} جديد </td>
                    <td class="th-fs">المسجلين بالدورات  :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                    <td>{{ $countOfSubsUsersSearch }}</td>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td class="th-fs">المسجلين بالدورات بالتكرار  من :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                    <td>{{$countOfSubsSitesSearch}} </td>
                    <td class="th-fs">المختبرين  من :{{$from ?? ''}} | الي : {{$to ?? ''}}</td>
                    <td>{{$countOfTestedUsersSearch}}</td>
                    <td></td>
                    <td></td>
                  </tr>
                @endisset

                <tr>
                  <th>اسم الدورة</th>
                  <th>اسم الدبلوم</th>
                  <th>تاريخ الدورة</th>
                  <th>بدون التكرار</th>
                  <th>بالتكرار</th>
                  <th>المشتركين</th>
                </tr>
                @php $countTests = 0;$countTests_withR = 0; @endphp
                @foreach($results as $result)


                    @if ( $result->exam_at <= date('Y-m-d') && $result->exam_at !=  Null )
                        <tr id="site_{{ $result->site_id }}">
                          <td>{{ $result->translation->first() != null ? $result->translation->first()->name : $result->title }}</td>
                          @php $bgc = '#fff'; @endphp

                          <td style="background-color: {{ $bgc }}">@foreach( $result->sites as $site ) @if(! $loop->first) - @endif {{ $site->translation->first() != null ? $site->translation->first()->name : $site->title}} @endforeach</td>
                          <td>{{ $result->translation->first() != null ? $result->translation->first()->date_at : $result->date_at }}</td>
                          <td>{{ $result->test_results_count }}</td>
                          @php
                           $resultwithR = $dataWithCount->where('id',$result->id )->first();
                           $countTests = $countTests + $result->test_results_count;
                           $countTests_withR = $countTests_withR + $resultwithR->test_results_count;
                          @endphp
                          <td>{{ $resultwithR->test_results_count }}</td>
                          <td>{{ $result->subscribers_count }}</td>
                        </tr>
                    @endif
                @endforeach
              <tr>
                <td> مجموع المختبرين</td>
                <td></td>
                <td></td>
                <td>{{ $countTests }}</td>
                <td>{{ $countTests_withR  }}</td>
                <td></td>
              </tr>
              </tbody>
            </table>
        </div>
      @endisset
    </div>
    --}}


  </div>



    <!-- Register Now Countdown -->
</section>
<!-- ##### Register Now End ##### -->


<script>

    $("#sta_search").click(function(event) {

          var ajaxRequest;
          var old_or_new='';  // $(this).attr( "att-old_or_new" );
          var search=$(this).attr( "att-Search" );

          if(search == "true"){
            var from = document.getElementById('from').value;
            var to = document.getElementById('to').value;
            // document.getElementById('oldOrNew_'+old_or_new+'_search').innerHTML  = 'Loading';
          } else {
            var from = '';
            var to = '';
            // document.getElementById('oldOrNew_'+old_or_new).innerHTML  = 'Loading';
          }

          // document.getElementById('sta_search_loading').innerHTML  = 'Searching';
          $('#sta_search_loading').css({'visibility':'visible'});



           event.preventDefault();
           ajaxRequest= $.ajax({
                url: $(this).attr( "att-URL" ),
                type: "post",
                data: { 'old_or_new' : old_or_new, 'from': from, 'to': to, "_token": "{{ csrf_token() }}"}
            });
            ajaxRequest.done(function (response, textStatus, jqXHR){

                // if(search == "true"){
                //   document.getElementById('oldOrNew_'+old_or_new+'_search').innerHTML  = response;
                // } else {
                //   document.getElementById('oldOrNew_'+old_or_new).innerHTML  = response;
                // }




                document.getElementById('oldOrNew_0_countOfMembersSearch').innerHTML = response['data']['oldOrNew_0']['countOfMembersSearch'];



                document.getElementById('oldOrNew_0_countOfTestedUsersSearch').innerHTML = response['data']['oldOrNew_0']['countOfTestedUsersSearch'] + '/' + ' جدد ' + response['data']['oldOrNew_0']['countOfTestedUsersOfRegisteredUsersSearch'];
                document.getElementById('oldOrNew_1_countOfTestedUsersSearch').innerHTML = response['data']['oldOrNew_1']['countOfTestedUsersSearch'] + '/' + ' جدد ' + response['data']['oldOrNew_1']['countOfTestedUsersOfRegisteredUsersSearch'];
                document.getElementById('oldOrNew_sum_countOfTestedUsersSearch').innerHTML =
                    ( parseInt(response['data']['oldOrNew_0']['countOfTestedUsersSearch']) + parseInt(response['data']['oldOrNew_1']['countOfTestedUsersSearch']) ) +
                      '/' + ' جدد ' +
                    ( parseInt(response['data']['oldOrNew_0']['countOfTestedUsersOfRegisteredUsersSearch']) + parseInt(response['data']['oldOrNew_1']['countOfTestedUsersOfRegisteredUsersSearch']) );




                document.getElementById('oldOrNew_0_countSuccessdUsersOfAllSitesSearch').innerHTML = response['data']['oldOrNew_0']['countSuccessdUsersOfAllSitesSearch'] + '/' + response['data']['oldOrNew_0']['countSuccessdTestsOfAllSitesSearch'];
                document.getElementById('oldOrNew_1_countSuccessdUsersOfAllSitesSearch').innerHTML = response['data']['oldOrNew_1']['countSuccessdUsersOfAllSitesSearch'] + '/' + response['data']['oldOrNew_1']['countSuccessdTestsOfAllSitesSearch'];
                document.getElementById('oldOrNew_sum_countSuccessdUsersOfAllSitesSearch').innerHTML =
                  ( parseInt(response['data']['oldOrNew_0']['countSuccessdUsersOfAllSitesSearch']) + parseInt(response['data']['oldOrNew_1']['countSuccessdUsersOfAllSitesSearch'] ) ) + '/' +
                  ( parseInt(response['data']['oldOrNew_0']['countSuccessdTestsOfAllSitesSearch']) + parseInt(response['data']['oldOrNew_1']['countSuccessdTestsOfAllSitesSearch'] ) );




                document.getElementById('oldOrNew_0_countUsersSuccessedInOneDiplomeSearch').innerHTML = response['data']['oldOrNew_0']['countUsersSuccessedInOneDiplomeSearch'] + '/' + response['data']['oldOrNew_0']['countUsersSuccessedMoreThanOneDiplomeSearch'];
                document.getElementById('oldOrNew_1_countUsersSuccessedInOneDiplomeSearch').innerHTML = response['data']['oldOrNew_1']['countUsersSuccessedInOneDiplomeSearch'] + '/' + response['data']['oldOrNew_1']['countUsersSuccessedMoreThanOneDiplomeSearch'];
                document.getElementById('oldOrNew_sum_countUsersSuccessedInOneDiplomeSearch').innerHTML =
                  ( parseInt(response['data']['oldOrNew_0']['countUsersSuccessedInOneDiplomeSearch']) + parseInt(response['data']['oldOrNew_1']['countUsersSuccessedInOneDiplomeSearch']) ) + '/' +
                  ( parseInt(response['data']['oldOrNew_0']['countUsersSuccessedMoreThanOneDiplomeSearch']) + parseInt(response['data']['oldOrNew_1']['countUsersSuccessedMoreThanOneDiplomeSearch']) );




                document.getElementById('oldOrNew_0_countOfSubsSitesSearch').innerHTML = response['data']['oldOrNew_0']['countOfSubsSitesSearch'];
                document.getElementById('oldOrNew_1_countOfSubsSitesSearch').innerHTML = response['data']['oldOrNew_1']['countOfSubsSitesSearch'];
                document.getElementById('oldOrNew_sum_countOfSubsSitesSearch').innerHTML =
                  ( parseInt(response['data']['oldOrNew_0']['countOfSubsSitesSearch']) + parseInt(response['data']['oldOrNew_1']['countOfSubsSitesSearch']) );


                document.getElementById('oldOrNew_0_countOfSubsUsersSearch').innerHTML = response['data']['oldOrNew_0']['countOfSubsUsersSearch'] + '/' + ' جدد ' + response['data']['oldOrNew_0']['countOfSubsUsersOfRegisteredUsersSearch'];
                document.getElementById('oldOrNew_1_countOfSubsUsersSearch').innerHTML = response['data']['oldOrNew_1']['countOfSubsUsersSearch'] ;
                document.getElementById('oldOrNew_sum_countOfSubsUsersSearch').innerHTML =
                  ( parseInt(response['data']['oldOrNew_0']['countOfSubsUsersSearch']) + parseInt(response['data']['oldOrNew_1']['countOfSubsUsersSearch']) ) +
                  '/' + ' جدد ' +
                  ( parseInt(response['data']['oldOrNew_0']['countOfSubsUsersOfRegisteredUsersSearch']) );




                document.getElementById('oldOrNew_0_countOfTestsSearch').innerHTML = response['data']['oldOrNew_0']['countOfTestsSearch'];
                document.getElementById('oldOrNew_1_countOfTestsSearch').innerHTML = response['data']['oldOrNew_1']['countOfTestsSearch'];
                document.getElementById('oldOrNew_sum_countOfTestsSearch').innerHTML =
                  ( parseInt(response['data']['oldOrNew_0']['countOfTestsSearch']) + parseInt(response['data']['oldOrNew_1']['countOfTestsSearch']) )


                // document.getElementById('sta_search_loading').innerHTML  = '';
                $('#sta_search_loading').css({'visibility':'collapse'});

            });
            ajaxRequest.fail(function (){
                console.log("fail");
                // document.getElementById('sta_search_loading').innerHTML  = '';
                $('#sta_search_loading').css({'visibility':'collapse'});
            });
    });

</script>



@endsection
@section('script')
<script>
  jQuery(document).ready(function($){
    $('#site_id').change(function(){
          $.get("{{ route('front.ajax_courses','ar')}}",
              { option: $(this).val() },
              function(data) {
                  var model = $('#course_id');
                  model.empty();
                  model.append("<option value=''> </option>");
                  $.each(data, function(index, element) {
                      model.append("<option value='"+ element.id +"'>" + element.title + "</option>");
                  });
              });
      });
  });
</script>

<x-admin.datatable.footer-js/>

<script>
  $(document).ready( function () {
      var table = $('#kt_table_2').DataTable({
                      dom: 'fBptipr', // pBfrtip    Blfrtip
                      // buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print' ]
                      'ordering': false,
                      "pageLength": 100,


                      scrollX: true,
                      language: {
                        paginate: {
                          next: "التالى",
                          previous: "السابق"
                        }
                      },
                      columnDefs: [ { // scheckbox -----
                          orderable: true,
                          className: 'select-checkbox',
                          targets:   0
                      } ],
                      select: {
                          style:    'multi',
                          selector: 'td:first-child'
                      },

                      // order: [[ 1, 'asc' ]], // end check box ------
                      buttons: [
                        {extend:'pageLength'},
                         { extend: 'copy' },
                         { extend: 'excel' },
                         { extend: 'csv' },
                         { extend: 'print' },
                         { text: 'pdf' , action: function () {

                                             // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                                             data = document.getElementById("kt_table_2").innerHTML;
                                             // Done but error 414 request url is too larg solved by changing get to post

                                             $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} });
                                             // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                                             $.ajax({
                                             url: "/pdf",
                                             type: 'post',
                                             // dataType: "json",
                                             data: { 'data':data },
                                             xhrFields: { responseType: 'blob' },
                                             success: function(response, status, xhr) {
                                                 // https://github.com/barryvdh/laravel-dompdf/issues/404

                                                 // console.log(response);
                                                 // var filename = "" ;
                                                 // var disposition = xhr.getResponseHeader('Content-Disposition');
                                                 // if (disposition) {
                                                 //     var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                                 //     var matches = filenameRegex.exec(disposition);
                                                 //     if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                                                 // }
                                                 // var blob = new Blob([response], { type: 'application/octet-stream' });
                                                 // var URL = window.URL || window.webkitURL;
                                                 // var downloadUrl = URL.createObjectURL(blob);
                                                 // var a = document.createElement("a");
                                                 // a.href = downloadUrl;
                                                 // // a.setAttribute('href', );
                                                 // a.download = filename;
                                                 // document.body.appendChild(a);
                                                 // a.target = "_blank";
                                                 // a.click();


                                                 var filename = "";
                                                 var disposition = xhr.getResponseHeader('Content-Disposition');

                                                  if (disposition) {
                                                     var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                                     var matches = filenameRegex.exec(disposition);
                                                     if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                                                 }
                                                 var linkelem = document.createElement('a');
                                                 try {
                                                     var blob = new Blob([response], { type: 'application/octet-stream' });

                                                     if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                                         //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                                         window.navigator.msSaveBlob(blob, filename);
                                                     } else {
                                                         var URL = window.URL || window.webkitURL;
                                                         var downloadUrl = URL.createObjectURL(blob);

                                                         if (filename) {
                                                             // use HTML5 a[download] attribute to specify filename
                                                             var a = document.createElement("a");

                                                             // safari doesn't support this yet
                                                             if (typeof a.download === 'undefined') {
                                                                 window.location = downloadUrl;
                                                             } else {
                                                                 a.href = downloadUrl;
                                                                 a.download = filename;
                                                                 document.body.appendChild(a);
                                                                 a.target = "_blank";
                                                                 a.click();
                                                             }
                                                         } else {
                                                             window.location = downloadUrl;
                                                         }
                                                     }

                                                 } catch (ex) {
                                                     console.log(ex);
                                                 }

                                             },error: function (xhr, status, error)
                                                { console.log(xhr.responseText); },
                                             });
                                        }
                         }
                     ]
                  });


                  // select all  -------------------------------------------------
                  $("#select_all").on( "click", function(e) {
                      if ($(this).is( ":checked" )) {
                          table.rows().select();
                          $('#delete').removeClass('btn btn-outline-danger');
                          $('#delete').addClass('btn btn-danger btn-elevate');
                          $('#delete').text( deleteWord + ' : ' + table.rows('.selected').data().length );
                      } else {
                          table.rows().deselect();
                          $('#delete').removeClass('btn btn-danger btn-elevate');
                          $('#delete').addClass('btn btn-outline-danger');
                          $('#delete').text( deleteWord );
                      }
                  });


                  // select row  -------------------------------------------------
                  deleteWord = "{{ __('words.delete') }}";
                  $('#kt_table_2 tbody').on( 'click', 'tr', function () {
                      $(this).toggleClass('selected');

                      if (table.rows('.selected').data().length > 0 ) {
                          $('#delete').removeClass('btn btn-outline-danger');
                          $('#delete').addClass('btn btn-danger btn-elevate');
                          $('#delete').text( deleteWord + ' : ' + table.rows('.selected').data().length );
                      } else {
                        $('#delete').removeClass('btn btn-danger btn-elevate');
                        $('#delete').addClass('btn btn-outline-danger');
                        $('#delete').text( deleteWord );
                      }
                  });


                  // delete button -----------------------------------------------
                $( '#frm_delete' ).on('submit', function(e) {

                    e.preventDefault();

                    var dataList=[];
                    $("#kt_table_2 .selected").each(function(index) {
                        dataList.push($(this).find('td:first').attr('value'))
                    })

                    if(dataList.length == 0){
                      Swal.fire({
                          title: "{{ __('admin/dashboard.please_select_record') }}",
                          text: "{{ __('admin/dashboard.please_select_record') }}",
                          type:"info" ,
                          timer: 3000,
                          showConfirmButton: true,
                          confirmButtonText: '{{ __("admin/dashboard.ok") }}'
                      });
                      return;
                    };

                    var type = $(this).attr('method');
                    var url = $(this).attr('action');
                    var data = $(this).serialize();
                    data = data + '&' + 'ids=' + dataList;

                    Swal.fire({
                      title: '{{ __("messages.confirm_delete_title") }}', text: '{{ __("messages.confirm_delete_text") }}', type: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: '{{ __("messages.yes_delete") }}' , cancelButtonText: '{{ __("messages.cancel") }}'
                    }).then((result) => {
                      if (result.value) {
                                $.ajax({
                                url : url ,
                                type : type ,
                                data : data , // {'ids':dataList},
                                dataType:"JSON",
                                success: function (data) {
                                    // console.log(data);
                                    // return;

                                    if(data['success']) {
                                      location.reload();
                                    }

                                    if(data['error']) {
                                        Swal.fire("{{trans('messages.deleted_faild')}}", data['error'], "error");
                                    }
                                },
                                error: function (xhr, status, error)
                                {
                                  if (xhr.status == 419) // httpexeption login expired or user loged out from another tab
                                  {window.location.replace( '{{ route("index") }}' );}
                                  Swal.fire("", "{{ __('messages.deleted_faild') }}", "error");
                                  console.log(xhr.responseText);

                                }
                            });
                      }
                    })

                });
                //  --------------------------------------------------------------
  });
</script>

<x-buttons.but_delete_inline_js/>




@endsection
