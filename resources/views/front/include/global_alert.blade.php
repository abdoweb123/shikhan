

@if ( Session::has('welcome_message'))

  <!-- welcome message after register-->
  <!-- will show with javascript in the-index master page -->
  <div class="modal fade show" id="pobup_modal" tabindex="-1" role="dialog" aria-labelledby="pobup_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <!-- <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div> -->
        <div class="modal-body" style="text-align: left;">
          {!! Session::get('welcome_message') !!}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" >{{ __('trans.close')}}</button>
        </div>
      </div>
    </div>
  </div>

@endif


<div class="container" style="text-align: center;">

  <span style="margin: 20px;"></span>
  {{--
  @if(session()->has('userMustChangeNamePreventAccount'))

          <div class="alert alert-danger" role="alert" style="text-align: center;">
            السلام عليكم ورحمة الله وبركاته<br>
            وبعد انتهاء مهلة الاسبوع لحذف الحساب تلقائيا، رأينا أن نعطي طلابننا فرصة جديدة ثلاثة أيام إضافية ويهمنا جداً بقائكم<br>
            <span style="font-weight: bold;">كرماً تسجيل الحسابات المكررة أو كتابة السبب الحقيقي حتى يتواصل معك مسؤول الدعم الفني على الرابط</span><br>
            فإن لم تسجلوا أو تعللوا السبب سيوقف بعدها حسابكم نهائيا<br>
            املأ الاستمارة من هنا<br>
            <a href="https://docs.google.com/forms/d/e/1FAIpQLSd_IsWdWoiPMarH5zxVR5-Os0qtAt5wesEZZb4iA353lJV-QQ/viewform" target="_blank">https://docs.google.com/forms/d/e/1FAIpQLSd_IsWdWoiPMarH5zxVR5-Os0qtAt5wesEZZb4iA353lJV-QQ/viewform</a><br>
            (شكرا لتفهمكم)
          </div>

          <div class="alert alert-danger" role="alert" style="text-align: center;">
            تم ايقاف الحساب نهائيا وان اردت استرجاعه تواصل مع الدعم الفني على الواتس اب
            <a href="https://bit.ly/3yNalG2" target="_blank">https://bit.ly/3yNalG2</a><br>
            أو على التليجرام
            <a href="https://t.me/BALDATAYIBA" target="_blank">https://t.me/BALDATAYIBA</a><br>
          </div>

  @endif
  --}}

  @if(session()->has('userMustChangeName'))
          <div class="alert alert-danger" role="alert" style="text-align: center;">
            السلام عليكم ورحمة الله وبركاته<br>
            نأسف لأننا سنضطر لإيقاف حسابك خلال أيام<br>
            <span style="font-weight: bold;">إلا أن تقوم بكتابة اسمك كاملا من هذا الرابط</span><br>
            <a href="https://forms.gle/hc9rKxfL9Cy3TK6z8" target="_blank">https://forms.gle/hc9rKxfL9Cy3TK6z8</a><br>
            (شكرا لتفهمكم)
          </div>
  @endif

  @if(session()->has('userMustChangeNameBecauseDublicated'))
          <div class="alert alert-danger" role="alert" style="text-align: center;">
            السلام عليكم ورحمة الله وبركاته<br>
            تبين لنا ان لكم حسابين او ان الاسم مكرر<br>
            نأسف لأننا سنضطر لإيقاف حسابك خلال أيام<br>
            <span style="font-weight: bold;">سجل في هذا النموذج  الحساب الذي تريد انا تغلقه والحساب الذى تود الابقاء عليه    </span><br>
            <a href="https://forms.gle/7cxywN6wHPvmo6cc9" target="_blank">https://forms.gle/7cxywN6wHPvmo6cc9</a><br>
            (شكرا لتفهمكم)
          </div>
  @endif

  @if(session()->has('userGet100'))
        <div class="alert alert-danger" role="alert" style="text-align: center;">
          تبارك لكم الأكاديمية فأنت من الطلاب المتميزين جداً ولعلمنا أن طلبة العلم أحرص الناس على الصدق والمصداقية<br>
          يسرنا أن تعينونا في فهم ما حدث بتعبئة الإستبانة المرفقه خلال الخمسة أيام القادمة حتى يستمر حسابكم<br>
          تقبل الله منكم، وكتب أجركم، ونفع بكم، ورفع قدركم.<br>
          <span style="font-weight: bold;">يرجى التسجيل في الاستبيان التالي   </span><br>
          <a href="https://forms.gle/9iZUgkQo8SyNKsQm8" target="_blank">https://forms.gle/9iZUgkQo8SyNKsQm8</a><br>
          (شكرا لتفهمكم)
        </div>
  @endif

  @if(request()->courseWillZoomToday)
    @if (date('Y-m-d H:i:s', strtotime( request()->courseWillZoomToday['date_at'] . ' 22:45:00' )) >= date('Y-m-d H:i:s'))

      <div class="alert alert-newevent" style="text-align: center;" role="alert">
        <i class="fas fa-video" style="font-size: 27px;color: #ce6a66;"> {{ __('trans.onair_today') }} </i>
        <div style="font-size: 20px;"><span style="font-weight: bold;">
          <a style="color: inherit;text-decoration: underline;"
            href="{{ route('courses.show', [
              'site' => request()->courseWillZoomToday['site_alias'],
              'course' => request()->courseWillZoomToday['course_alias']
              ]) }}">{{ request()->courseWillZoomToday['name'] }}
          </a></span> - {{ __('trans.onair_date_mecca') }}</div>

          @if (date('Y-m-d H:i:s' ,strtotime( request()->courseWillZoomToday['date_at'] . ' 17:45:00' )) <= date('Y-m-d H:i:s'))
            @if(request()->courseWillZoomToday['link_zoom'])
              <div><a href="{{ request()->courseWillZoomToday['link_zoom'] }}">اضغط هنا لحضور البث المباشر</a></div>
            @endif
          @endif
      </div>

    @endif
  @endif


  <!-- display suggestion cource if no oom today to dont disp;ay tow messages at the top of the page -->
  @auth
      @if(! request()->courseWillZoomToday)
        @if(request()->suggestionCource)
        <div>
          <span style="padding: 15px 25px;background-color: #ffd599;border-radius: 60px;display: inline-block;">
            <span>{{ __('trans.suggest') }} : </span>
            <a style="color: inherit;text-decoration: underline;font-weight: bold;"
              href="{{ route('courses.show', [
                'site' => request()->suggestionCource['site_alias'],
                'course' => request()->suggestionCource['course_alias']
                ]) }}">{{ request()->suggestionCource['title'] }}
            </a>
          </span>
        </div>
        @endif
      @endif
  @endauth

</div>
