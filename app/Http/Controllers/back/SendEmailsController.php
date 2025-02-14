<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;

use \App\Actions\Queries\UsersSubscribedButNotTestedEver;

class SendEmailsController extends Controller
{
    private $utilConn;

    public function __construct()
    {
        // $this->utilConn = config('project.db_util_connection');
    }

    // public function index_org(Request $request)
    // {
    //     $sites = $this->sites();
    //     $queries = $this->queries();
    //     return view('back.content.emails.show',compact(['sites','queries']));
    // }

    public function index(Request $request)
    {

          // $request->flush();
          // $request->session()->flush();
          session()->flashInput($request->input());

          $sites = $this->sites();
          $queries = $this->queries();

          $currentQuery = '';
          $defaultDateFrom = Carbon::create(2019,01,01)->toDateString();
          $defaultDateTo = Carbon::now()->format('Y-m-d');





          // send emails
          if ($request->has('send_email_query')){
            $currentQuery = $request->send_email_query;
          }
          // don't send just diplay the result at the bootom of the page
          if ($request->has('show_data_query')){
            $currentQuery = $request->show_data_query;
          }
          // don't send just diplay the count at the bootom of the page
          if ($request->has('count_data_query')){
            $currentQuery = $request->count_data_query;
          }
          // don't send just export the result to csv file
          if ($request->has('export_csv_data_query')){
            $currentQuery = $request->export_csv_data_query;
          }







          // check query in $this->queries()
          foreach ($queries as $query) {
            if ($query['alias'] == $currentQuery){
              $currentQuery = $query['alias'];
            }
          }

          if ($request->isMethod('post')) {
            if(! $currentQuery) {
              return back()->withinput()->withErrors(['general' => 'select Query']);
            }
          }








          // call functions dynamiclly by name
          // $data = $this->{$currentQuery}();

          $params = [];
          $data = null;
          $dataCount = null;
          $currentQueryAlias = $currentQuery;




          if ($currentQuery == 'TestSendEmail'){
              // email test only message to samia, sabah, kamal, tarik
          }


          // email 1 with its parameter
          if ($currentQuery == 'UsersSubscribedButNotTestedEver'){
              // if (!$request->from_date_not_tested){ return back()->withErrors(['from_date' => 'ادخل تاريخ البداية']); }
              $params['from_date'] = $request->from_date_not_tested ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_not_tested ?? $defaultDateTo;
              // if (empty($request->site_id_not_tested)){ return back()->withErrors(['site_id_not_tested' => 'ادخل الدورة']); }
              $params['site_id'] = $request->site_id_not_tested;
          }


          // email 2 with its parameter
          if ($currentQuery == 'UsersHasXCoursesToFinishDeiplom'){
              if (!$request->more_than_x_courses_finish){ return back()->withErrors(['more_than_x_courses' => 'ادخل عدد الدورات']); }
              $params['more_than_x_courses'] = $request->more_than_x_courses_finish;
              $params['site_id'] = $request->site_id_finish;
          }


          // email 3 with its parameter
          if ($currentQuery == 'UsersDidntTestedEver'){
            //
          }


          if ($currentQuery == 'UsersDidntTestedFromTo'){
              // if (!$request->from_date_didnt_tested){ return back()->withErrors(['from_date_didnt_tested' => 'ادخل تاريخ البداية']); }
              // $params['from_date'] = $request->from_date_didnt_tested;
              $params['from_date'] = $request->from_date_didnt_tested ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_didnt_tested ?? $defaultDateTo;
              $params['site_id'] = $request->site_id_didnt_tested;
          }


          // email 4 with its parameter
          if ($currentQuery == 'UsersRegisterdFromTo'){
              // if (!$request->from_date_register){ return back()->withErrors(['from_date_register' => 'ادخل تاريخ البداية']); }
              // $params['from_date'] = $request->from_date_register;
              $params['from_date'] = $request->from_date_register ?? $defaultDateFrom;
              $params['to_date'] = $request->to_date_register ?? $defaultDateTo;
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


          // email 7 with its parameter
          if ($currentQuery == 'SendEmail'){
              if (!$request->emails) { return back()->withErrors(['emails' => 'ادخل عناوين البريد الاكترونى']); }
              $emailsArray = explode(',', $request->emails);
              if (! is_array($emailsArray)) { return back()->withErrors(['emails' => 'عناوين البريدج الاكترونى غير صحيحة']); }
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




          // register emails
          if ($request->has('send_email_query')){
              if (!$request->message){ return back()->withErrors(['from_date' => 'ادخل الرسالة']); }

              // for queries (will move to util_db)
              $qId = DB::Table('emails_to_send_queries')->insertGetId([
                  'func' => $currentQuery,
                  'params' => json_encode($params),
                  'message' => $request->message,
                  'created_at' => now()
              ]);

              // for spacific emails
              if ($currentQuery == 'SendEmail'){
                $mailsToInsert = [];
                foreach ($emailsArray as $email) {
                  if($email){ // to ignore if last item (after ,) is empty
                    $mailsToInsert[] = ['email' => $email, 'emails_to_send_queries_id' => $qId];
                  }
                }
                DB::table('emails_to_send')->insert($mailsToInsert);
              }

              return redirect()->back()->withSuccess('تم التسجيل');
          }











          if ($request->isMethod('post')) {

              //  detrmine current class
              $functionPath = 'App\\Actions\\Queries\\' . $currentQuery;

              // just show data
              if ($request->has('show_data_query')){
                    $params = $params + [ 'paginate' => 50 ];
                    $currentFunction =  new $functionPath($params);
                    $data = $currentFunction->getData($params);
              }

              // just get count data
              if ($request->has('count_data_query')){
                    $params = $params + [ 'paginate' => 1 ];
                    $currentFunction =  new $functionPath($params);
                    $dataCount = $currentFunction->getData(); // paginate with 0 record so get the total records without the real data
              }

              // just export data
              if ($request->has('export_csv_data_query')){
                    $params = $params + [ 'exprt_type' => 'csv' ];
                    $currentFunction =  new $functionPath($params);
                    $data = $currentFunction->exportData();
              }

          }







          return view('back.content.emails.show', compact(['sites','queries','data','dataCount','currentQueryAlias','defaultDateFrom','defaultDateTo']));


    }

    public function edit(Request $request)
    {


        $data = DB::table('emails_to_send_queries')
          ->leftjoin('emails_to_send_queries_members', 'emails_to_send_queries.id', 'emails_to_send_queries_members.emails_to_send_queries_id')
          ->select('emails_to_send_queries.id','emails_to_send_queries.func','emails_to_send_queries.params',
                   'emails_to_send_queries.is_active','emails_to_send_queries.status','emails_to_send_queries.created_at',
                   DB::raw('COUNT(emails_to_send_queries_members.emails_to_send_queries_id) as sent_count')
                   )
          ->groupBy('emails_to_send_queries.id')
          ->orderby('emails_to_send_queries.status', 'desc')
          ->orderby('emails_to_send_queries.is_active', 'desc')
          ->paginate(7);

          foreach ($data as $item) {
              foreach ($this->queries() as $value) {
                if($value['alias'] == $item->func){
                  $item->title = $value['title'];
                }
              }

              $params = json_decode($item->params, true);
              if( isset($params['site_id']) ){
                  if (is_array($params['site_id'])){
                    $item->site_title = \App\site::wherein('id', $params['site_id'] )->select('id','title')->get()->pluck('title')->implode(' / ');
                  } else {
                    $item->site_title = \App\site::where('id', $params['site_id'] )->select('id','title')->first()->title;
                  }
              }


              // SendEmail joined with another table (emails_to_send) in util_db
              if( $item->func == 'SendEmail'){
                  $item->sent_count = DB::table('emails_to_send')
                    ->where('emails_to_send_queries_id', $item->id)->count();
              }


              $paramsExcept = Arr::except($params, ['site_id']);
              $item->data = '';
              foreach ($paramsExcept as $value) {
                if( is_array( $value)){
                    $item->data = $item->data . ' ' . implode(" ", $value );
                } else {
                  $item->data = $item->data . ' ' . $value;
                }
              }



          }

        return view('back.content.emails.edit',compact(['data']));

    }

    public function editDetails(Request $request)
    {
        $data = DB::table('emails_to_send_queries')->where('id', $request->id)->first();
        abort_if(! $data, 404);

        // check query in $this->queries()
        $currentQuery = '';
        $queries = $this->queries();
        foreach ($queries as $query) {
          if ($query['alias'] == $data->func){
            $currentQuery = $query['title'];
          }
        }

        return view('back.content.emails.edit-details', compact(['data','currentQuery']));

    }

    public function updateDetails(Request $request)
    {
        DB::table('emails_to_send_queries')->where('id', $request->id)->update(['message' => $request->message]);
        return back()->with(['success' => 'تم التعديل']);
    }

    public function updateStatus(Request $request)
    {
        $send = DB::table('emails_to_send_queries')->where('id', $request->id)->update(['is_active' => $request->value]);
        return redirect()->back()->withSuccess('تم');
    }

    public function queries()
    {
        return [
          [ 'alias' => 'TestSendEmail', 'title' => 'اختبار الرسالة لبريد الشركة' ],
          [ 'alias' => 'UsersSubscribedButNotTestedEver', 'title' => 'طلاب اشتركو فى دورات من تاريخ معين ولم يختبروا فيها' ],
          [ 'alias' => 'UsersRegisterdFromTo', 'title' => 'طلاب أنشأو حساب من الى' ],
          [ 'alias' => 'UsersHasXCoursesToFinishDeiplom', 'title' => 'من بقى له عدد معين من الدورات لاتمام الدبلوم' ],
          [ 'alias' => 'UsersDidntTestedEver', 'title' => 'لم يختبر نهائيا' ],
          [ 'alias' => 'UsersDidntTestedFromTo', 'title' => 'لم يختبر فى دبلوم معين من الى' ],
          [ 'alias' => 'UsersTestedXCoursesAndSuccessed', 'title' => 'اختبر عدد من الدورات من الى' ],
          [ 'alias' => 'UsersTestedXCoursesAndNotTestedForPeriod', 'title' => 'متبقى له اقل من عدد معين من الدورات لاستكمال الدبلوم ولم يختبر فى الفترة من الى' ],
          [ 'alias' => 'UsersTestedOrSubscribedSitesAndNotTestedOrSubscribedOthers', 'title' => 'اختبر او اشترك فى دبلومات ولم يختبر او لم يشترك فى اخرى' ],
          [ 'alias' => 'UsersSubscribedInSitesFromTo', 'title' => 'من اشترك فى دبلوم/ات من الى' ],
          [ 'alias' => 'UsersSuccessedInSitesFromTo', 'title' => 'من انهى دبلوم/ات من الى' ],
          [ 'alias' => 'SendEmail', 'title' => 'ارسال رسالة لعناوين بريد محددة' ],
        ];
    }

    public function sites()
    {
       return DB::Table('sites')->select('id','title')->get();
    }


}
