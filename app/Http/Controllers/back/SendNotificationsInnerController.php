<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Mail;
use App\Services\SettingService;
use Illuminate\Support\Arr;

use Carbon\Carbon;

class SendNotificationsInnerController extends Controller
{

    private $settingServ;

    public function __construct( SettingService $settingService )
    {
        $this->settingServ = $settingService;
    }

    public function index(Request $request)
    {

          $request->flush();



          $sites = $this->sites();
          $queries = $this->queries();

          $currentQuery = '';
          $defaultDateFrom = Carbon::create(2019,01,01)->toDateString();
          $defaultDateTo = Carbon::now()->format('Y-m-d');


          // send emails
          if ($request->has('send_notifications_query')){
            $currentQuery = $request->send_notifications_query;
          }
          // don't send just diplay the result at the bootom of the page
          if ($request->has('show_data_query')){
            $currentQuery = $request->show_data_query;
          }


          // check query in $this->queries()
          foreach ($queries as $query) {
            if ($query['alias'] == $currentQuery){
              $currentQuery = $query['alias'];
              $currentQueryTitle = $query['title'];
            }
          }

          if ($request->isMethod('post')) {
            if(! $currentQuery) {
              return back()->withinput()->withErrors(['general' => 'select Query']);
            }
            if (!$request->title){
              return back()->withErrors(['from_date' => 'ادخل عنوان الرسالة']);
            }
          }

          $params = [];
          $data = null;

          if ($currentQuery == 'TestSendEmail'){
              // email test only message to samia, sabah, kamal, tarik
          }

          // email 1 with its parameter
          if ($currentQuery == 'UsersSubscribedButNotTestedEver'){
              if (!$request->from_date_users_subscribed_but_not_tested_ever){ return back()->withErrors(['from_date' => 'ادخل تاريخ البداية']); }
              $params['from_date'] = $request->from_date_users_subscribed_but_not_tested_ever ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_users_subscribed_but_not_tested_ever ?? $defaultDateTo;
              $params['site_id'] = $request->site_id_users_subscribed_but_not_tested_ever;
              $params['site_name'] = $request->site_name_users_subscribed_but_not_tested_ever;
          }

          // email 2 with its parameter
          if ($currentQuery == 'UsersHasXCoursesToFinishDeiplom'){
              if (!$request->more_than_x_courses){ return back()->withErrors(['more_than_x_courses' => 'ادخل عدد الدورات']); }
              $params['more_than_x_courses'] = $request->more_than_x_courses;
              $params['site_id'] = $request->site_id;
              $params['site_name'] = $request->site_name_users_has_x_courses_to_finish_deiplom;
          }

          // email 3 with its parameter
          if ($currentQuery == 'UsersDidntTestedEver'){
              // No Parametrs
          }

          // email 4 with its parameter
          if ($currentQuery == 'UsersRegisterdFromTo'){
              if (!$request->from_date_users_registerd_from_to){ return back()->withErrors(['from_date' => 'ادخل تاريخ البداية']); }
              $params['from_date'] = $request->from_date_users_registerd_from_to;
              $params['to_date'] = $request->to_date_users_registerd_from_to ?? Carbon::now()->format('Y-m-d');
          }

          // email 5 with its parameter
          if ($currentQuery == 'UsersDidntTestedFromTo'){
              // if (!$request->from_date_users_didnt_tested_from_to){ return back()->withErrors(['from_date' => 'ادخل تاريخ البداية']); }
              // $params['from_date'] = $request->from_date_users_didnt_tested_from_to;
              // $params['to_date'] = $request->to_date_users_didnt_tested_from_to ?? Carbon::now()->format('Y-m-d');
              //

              // if (!$request->from_date_didnt_tested){ return back()->withErrors(['from_date_didnt_tested' => 'ادخل تاريخ البداية']); }
              // $params['from_date'] = $request->from_date_didnt_tested;
              $params['from_date'] = $request->from_date_didnt_tested ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_didnt_tested ?? $defaultDateTo;
              $params['site_id'] = $request->site_id_didnt_tested;
          }

          // email 5 with its parameter
          if ($currentQuery == 'UsersTestedXCoursesAndSuccessed'){
              // if (!$request->from_date_courses_successedd){ return back()->withErrors(['from_date' => 'ادخل تاريخ البداية']); }
              // $params['from_date'] = $request->from_date_courses_successedd;
              $params['from_date'] = $request->from_date_courses_successedd ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_courses_successedd ?? $defaultDateTo;

              $params['succeeded'] = $request->chk_success_courses_successedd;

              if (!$request->more_than_x_courses_successedd){ return back()->withErrors(['more_than_x_courses' => 'ادخل عدد الدورات']); }
              $params['more_than_x_courses'] = $request->more_than_x_courses_successedd;
              $params['site_id'] = $request->site_id_successedd;
          }

          // email 6 with its parameter
          if ($currentQuery == 'UsersTestedXCoursesAndNotTestedForPeriod'){
              // if (!$request->from_date_period){ return back()->withErrors(['from_date' => 'ادخل تاريخ البداية']); }
              // $params['from_date'] = $request->from_date_period;
              $params['from_date'] = $request->from_date_period ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_period ?? $defaultDateTo;

              if (!$request->more_than_x_courses_period){ return back()->withErrors(['more_than_x_courses' => 'ادخل عدد الدورات']); }
              $params['more_than_x_courses'] = $request->more_than_x_courses_period;
              $params['site_id'] = $request->site_id_period;
          }

          // email 6 with its parameter
          if ($currentQuery == 'SendEmail'){
              if (!$request->ids) { return back()->withErrors(['ids' => 'ادخل id للطلاب']); }
              // $emailsArray = explode(',', $request->emails);
              $idsArray = array_map('intval', explode(',', $request->ids));
              if (! is_array($idsArray)) { return back()->withErrors(['ids' => 'ids للطلاب غير صحيح']); }
              $params['ids'] = $idsArray;
          }

          // email 8 with its parameter
          if ($currentQuery == 'UsersTestedOrSubscribedSitesAndNotTestedOrSubscribedOthers'){
              // if (!$request->from_date_ts_in_not_in){ return back()->withErrors(['from_date' => 'ادخل تاريخ البداية']); }
              // $params['from_date'] = $request->from_date_ts_in_not_in;
              $params['from_date'] = $request->from_date_ts_in_not_in ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_ts_in_not_in ?? $defaultDateTo;

              $params['site_id_in'] = $request->site_id_ts_in;
              $params['site_id_not_in'] = $request->site_id_ts_not_in;

              $params['test_or_subscribe_ts_in_not_in'] = $request->test_or_subscribe_ts_in_not_in;
          }

          // email 9 with its parameter
          if ($currentQuery == 'UsersSubscribedInSitesFromTo'){
              $params['from_date'] = $request->from_date_subscribed_sites ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_subscribed_sites ?? $defaultDateTo;
              $params['site_id'] = $request->site_id_subscribed_sites;
          }

          // email 10 with its parameter
          if ($currentQuery == 'UsersSuccessedInSitesFromTo'){
              $params['from_date'] = $request->from_date_successed_sites ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_successed_sites ?? $defaultDateTo;
              $params['site_id'] = $request->site_id_successed_sites;
          }

          if ($request->has('show_data_query')){
                // detrmine currnt class
                $functionPath = 'App\\Actions\\Queries\\' . $currentQuery;
                $params = $params + [ 'paginate' => 50 ];
                $currentFunction =  new $functionPath($params);
                $data = $currentFunction->getData();
          }

          // insert notifications then assign to inserted Notificateion to user
          if ($request->has('send_notifications_query')){

              // get tag
              $paramsExcept = Arr::except($params, ['site_id']);
              $tag = '';
              foreach ($paramsExcept as $value) {
                if( is_array( $value)){
                    $tag = $tag . ' ' . implode(" ", $value );
                } else {
                  $tag = $tag . ' ' . $value;
                }
              }

              // insert
              $insertedNotificateion = DB::Table('notifications_inner')->insertGetId([
                  'title' => $request->title,
                  'tag' => $currentQueryTitle . ' - ' . $tag, // dont set ids with the tag
                  'body' => $request->message,
                  'created_at' => now()
              ]);


              // detrmine currnt class
              $functionPath = 'App\\Actions\\Queries\\' . $currentQuery;
              $currentFunction =  new $functionPath($params);
              $currentFunction->AssignNotificationsToUser($insertedNotificateion);

              // also send any notifications to us
              // $testEmailFunction = new \App\Actions\Queries\TestSendEmail();
              // $testEmailFunction->AssignNotificationsToUser($insertedNotificateion, $params);
          }


          // if ($request->isMethod('post')) {
          //     return redirect()->route('dashboard.send_notifications.inner.index');
          // }

          return view('back.content.notifications.show',compact(['sites','queries','data','defaultDateFrom','defaultDateTo']));

    }

    public function queries()
    {
        return [
          [ 'alias' => 'TestSendEmail', 'title' => 'اختبار الرسالة لبريد الشركة' ],
          [ 'alias' => 'UsersSubscribedButNotTestedEver', 'title' => 'طلاب اشتركو فى دورات من تاريخ معين ولم يختبروا نهائيا' ],
          [ 'alias' => 'UsersRegisterdFromTo', 'title' => 'طلاب أنشأو حساب من الى' ],
          [ 'alias' => 'UsersHasXCoursesToFinishDeiplom', 'title' => 'من بقى له عدد معين من الدورات لاتمام الدبلوم' ],
          [ 'alias' => 'UsersDidntTestedEver', 'title' => 'لم يختبر نهائيا' ],
          [ 'alias' => 'UsersDidntTestedFromTo', 'title' => 'لم يختبر فى دبلوم معين من الى' ], // [ 'alias' => 'UsersDidntTestedFromTo', 'title' => 'طلاب لم يختبروا من فترة من الى' ],
          [ 'alias' => 'UsersTestedXCoursesAndSuccessed', 'title' => 'اختبر عدد من الدورات من الى' ],
          [ 'alias' => 'UsersTestedXCoursesAndNotTestedForPeriod', 'title' => 'متبقى له اقل من عدد معين من الدورات لاستكمال الدبلوم ولم يختبر فى الفترة من الى' ],
          [ 'alias' => 'UsersTestedOrSubscribedSitesAndNotTestedOrSubscribedOthers', 'title' => 'اختبر او اشترك فى دبلومات ولم يختبر او لم يشترك فى اخرى' ],
          [ 'alias' => 'UsersSubscribedInSitesFromTo', 'title' => 'من اشترك فى دبلوم/ات من الى' ],
          [ 'alias' => 'UsersSuccessedInSitesFromTo', 'title' => 'من انهى دبلوم/ات من الى' ],
          [ 'alias' => 'SendEmail', 'title' => 'ارسال رسالة لطلاب محددين' ],
        ];
    }

    public function sites()
    {
       return DB::Table('sites')->select('id','title')->get();
    }

    public function editTemplate()
    {
        $messageAfterRegestration = $this->settingServ->getSettingByProperty('message_after_registration')->value;
        return view('back.content.notifications.edit_template_msg_after_reg',compact(['messageAfterRegestration']));
    }

    public function storeTemplate(Request $request)
    {
        $messageAfterRegestration = $this->settingServ->getOriginalSettingByProperty('message_after_registration');
        $messageAfterRegestration->update([
          'value' => ['ar' => $request->message] + $messageAfterRegestration->value
        ]);

        return redirect()->back()->with('success', 'Updated Successfully');
        // return Redirect::back()->withErrors(['msg' => 'The Message']);
    }


}
