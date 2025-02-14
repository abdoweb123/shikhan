<?php

namespace App\Actions\Queries;
use DB;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


// طلاب اشتركو او اختبرو فى دبلومات ولم يشتركو او لم يختبرو فى دبلومات
class UsersTestedOrSubscribedSitesAndNotTestedOrSubscribedOthers
{

    private $from_date;
    private $to_date;
    private $site_id_in;
    private $site_id_not_in;
    private $test_or_subscribe_ts_in_not_in;
    private $count;
    private $paginate;
    private $func_id;
    private $exprt_type;

    public function __construct( $params=[] )
    {
        $this->from_date = isset($params['from_date']) ? $params['from_date'] : null;
        $this->to_date = isset($params['to_date']) ? $params['to_date'] : null;
        $this->site_id_in = isset($params['site_id_in']) ? $params['site_id_in'] : null;
        $this->site_id_not_in = isset($params['site_id_not_in']) ? $params['site_id_not_in'] : [];
        $this->test_or_subscribe_ts_in_not_in = isset($params['test_or_subscribe_ts_in_not_in']) ? $params['test_or_subscribe_ts_in_not_in'] : null;

        $this->count = isset($params['count']) ? $params['count'] : null;
        $this->paginate = isset($params['paginate']) ? $params['paginate'] : null;

        $this->func_id = isset($params['func_id']) ? $params['func_id'] : null;

        $this->exprt_type = isset($params['exprt_type']) ? $params['exprt_type'] : 'csv';
    }

    public function collectQuery()
    {

        $testCreatedAtCondition = " and course_tests_results.created_at >= '$this->from_date' and course_tests_results.created_at <= '$this->to_date' ";


        // there is (in) (not_in) conditions
        $notInCondition_Start = '';
        $notInCondition_End = '';
        if(! empty($this->site_id_not_in)){
          $notInCondition_Start = " and( ";
              // conditions will be here
          $notInCondition_End = " ) ";
        }

        // conditions
        $notInConditions = '';

        // 01 - test only or test_subscrib
        if ($this->test_or_subscribe_ts_in_not_in == 'test_subscribe' || $this->test_or_subscribe_ts_in_not_in == 'test'){
            foreach ($this->site_id_not_in as $site_id) {

                if($notInConditions){
                    $notInConditions = $notInConditions . ' or ';
                }

                $notInConditions = $notInConditions . "
                    user_id not in
                    (
                        SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
                        (
                            SELECT course_id from course_site where site_id = $site_id
                        )
                    )
                ";

            }
        }

        // 02 - subscribe only or test_subscrib
        if ($this->test_or_subscribe_ts_in_not_in == 'test_subscribe' || $this->test_or_subscribe_ts_in_not_in == 'subscribe'){
            foreach ($this->site_id_not_in as $site_id) {

                if($notInConditions){
                    $notInConditions = $notInConditions . ' or ';
                }

                $notInConditions = $notInConditions . "
                    user_id not in
                    (
                        SELECT DISTINCT(user_id) FROM site_subscriptions WHERE site_id  = $site_id
                    )
                ";

            }
        }

        // main query
        $mainSql = DB::select("
              SELECT members.id , members.name, members.email, members.phone, members.whats_app from members WHERE id in
              (
                  SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
                    (
                        SELECT course_id from course_site where site_id = $this->site_id_in
                    )
                    $testCreatedAtCondition
                    $notInCondition_Start
                      $notInConditions
                    $notInCondition_End
              )
              and members.error_email is null
              ORDER BY members.id
        ");


        return $mainSql;

        // full query
        // $q = DB::select(
        //           "
        //               SELECT members.id , members.name, members.email, members.phone, members.whats_app from members WHERE id in
        //               (
        //                 	SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
        //                     	(
        //                       		SELECT course_id from course_site where site_id = $site_id_in
        //                     	)
        //                     and
        //                     	(
        //                             /*-----------------------test-------------------------*/
        //                             user_id not in
        //                             (
        //                                 SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
        //                                 (
        //                                     SELECT course_id from course_site where site_id = 24
        //                                 )
        //                             )
        //                             or
        //                             user_id not in
        //                             (
        //                                 SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
        //                                 (
        //                                     SELECT course_id from course_site where site_id = 14
        //                                 )
        //                             )
        //                             /*-----------------------subscrip-------------------------*/
        //                             or user_id not in
        //                             (
        //                                 SELECT DISTINCT(user_id) FROM site_subscriptions WHERE site_id  = 24
        //                             )
        //                             or
        //                             user_id not in
        //                             (
        //                                 SELECT DISTINCT(user_id) FROM site_subscriptions WHERE site_id  = 14
        //                             )
        //                         )
        //               )
        //           "
        //       );




    }

    public function getData()
    {
          $q = $this->arrayPaginator($this->collectQuery(), request());
          return $q;
    }

    public function exportData()
    {

          $exportService = new \App\Services\ExportService();
          if($this->exprt_type == 'csv') {
            return $exportService->exportCsv( ['members.id','name','email'], $this->collectQuery() );
          }

    }

    // public function getData($params=[])
    // {
    //
    //       $from_date = isset($params['from_date']) ? $params['from_date'] : null;
    //       $to_date = isset($params['to_date']) ? $params['to_date'] : null;
    //       $site_id_in = isset($params['site_id_in']) ? $params['site_id_in'] : null;
    //       $site_id_not_in = isset($params['site_id_not_in']) ? $params['site_id_not_in'] : [];
    //       $test_or_subscribe_ts_in_not_in = isset($params['test_or_subscribe_ts_in_not_in']) ? $params['test_or_subscribe_ts_in_not_in'] : null;
    //
    //       $count = isset($params['count']) ? $params['count'] : null;
    //       $paginate = isset($params['paginate']) ? $params['paginate'] : null;
    //
    //
    //       $testCreatedAtCondition = " and course_tests_results.created_at >= '$from_date' and course_tests_results.created_at <= '$to_date' ";
    //
    //
    //       // there is (in) (not_in) conditions
    //       $notInCondition_Start = '';
    //       $notInCondition_End = '';
    //       if(! empty($site_id_not_in)){
    //         $notInCondition_Start = " and( ";
    //             // conditions will be here
    //         $notInCondition_End = " ) ";
    //       }
    //
    //       // conditions
    //       $notInConditions = '';
    //
    //       // 01 - test only or test_subscrib
    //       if ($test_or_subscribe_ts_in_not_in == 'test_subscribe' || $test_or_subscribe_ts_in_not_in == 'test'){
    //           foreach ($site_id_not_in as $site_id) {
    //
    //               if($notInConditions){
    //                   $notInConditions = $notInConditions . ' or ';
    //               }
    //
    //               $notInConditions = $notInConditions . "
    //                   user_id not in
    //                   (
    //                       SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
    //                       (
    //                           SELECT course_id from course_site where site_id = $site_id
    //                       )
    //                   )
    //               ";
    //
    //           }
    //       }
    //
    //       // 02 - subscribe only or test_subscrib
    //       if ($test_or_subscribe_ts_in_not_in == 'test_subscribe' || $test_or_subscribe_ts_in_not_in == 'subscribe'){
    //           foreach ($site_id_not_in as $site_id) {
    //
    //               if($notInConditions){
    //                   $notInConditions = $notInConditions . ' or ';
    //               }
    //
    //               $notInConditions = $notInConditions . "
    //                   user_id not in
    //                   (
    //                       SELECT DISTINCT(user_id) FROM site_subscriptions WHERE site_id  = $site_id
    //                   )
    //               ";
    //
    //           }
    //       }
    //
    //       // main query
    //       $mainSql = DB::select("
    //             SELECT members.id , members.name, members.email, members.phone, members.whats_app from members WHERE id in
    //             (
    //                 SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
    //                   (
    //                       SELECT course_id from course_site where site_id = $site_id_in
    //                   )
    //                   $testCreatedAtCondition
    //                   $notInCondition_Start
    //                     $notInConditions
    //                   $notInCondition_End
    //             )
    //       ");
    //
    //
    //

    //       // full query
    //       // $q = DB::select(
    //       //           "
    //       //               SELECT members.id , members.name, members.email, members.phone, members.whats_app from members WHERE id in
    //       //               (
    //       //                 	SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
    //       //                     	(
    //       //                       		SELECT course_id from course_site where site_id = $site_id_in
    //       //                     	)
    //       //                     and
    //       //                     	(
    //       //                             /*-----------------------test-------------------------*/
    //       //                             user_id not in
    //       //                             (
    //       //                                 SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
    //       //                                 (
    //       //                                     SELECT course_id from course_site where site_id = 24
    //       //                                 )
    //       //                             )
    //       //                             or
    //       //                             user_id not in
    //       //                             (
    //       //                                 SELECT DISTINCT(user_id) FROM course_tests_results WHERE course_id in
    //       //                                 (
    //       //                                     SELECT course_id from course_site where site_id = 14
    //       //                                 )
    //       //                             )
    //       //                             /*-----------------------subscrip-------------------------*/
    //       //                             or user_id not in
    //       //                             (
    //       //                                 SELECT DISTINCT(user_id) FROM site_subscriptions WHERE site_id  = 24
    //       //                             )
    //       //                             or
    //       //                             user_id not in
    //       //                             (
    //       //                                 SELECT DISTINCT(user_id) FROM site_subscriptions WHERE site_id  = 14
    //       //                             )
    //       //                         )
    //       //               )
    //       //           "
    //       //       );
    //
    //       // $q = DB::table('members')
    //       //         ->join('course_tests_results', 'course_tests_results.user_id', 'members.id')
    //       //         ->join('course_site', 'course_tests_results.course_id', 'course_site.course_id')
    //       //         ->where('course_site.site_id', 13)
    //       //         ->where(function ($query) {
    //       //             $query->whereNotIn(
    //       //                       'course_tests_results.user_id',
    //       //                       DB::table('course_tests_results')
    //       //                           ->join('course_site', 'course_tests_results.course_id', 'course_site.course_id')
    //       //                           ->where('course_site.site_id', 24)->pluck('user_id')
    //       //                   )
    //       //                   ->orwhereNotIn(
    //       //                       'course_tests_results.user_id',
    //       //                       DB::table('course_tests_results')
    //       //                           ->join('course_site', 'course_tests_results.course_id', 'course_site.course_id')
    //       //                           ->where('course_site.site_id', 14)->pluck('user_id')
    //       //                   );
    //       //       })
    //       //       ->select('members.id')->distinct()
    //       //       ->get();
    //       //       dd($q);
    //
    //
    //
    //
    //       $q = $this->arrayPaginator($mainSql, request());
    //       return $q;
    //
    //       // if ($count){ return $q->count(); }
    //       // if ($paginate){ return $q->paginate($paginate); }
    //       // return $q->get();
    //
    // }


    public function arrayPaginator($array, $request)
    {
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }









    /*
    run the query with relation to 'emails_to_send_queries_members' table to
    don't send email to any one found in this table 'emails_to_send_queries_members'
    */
    public function getEmails($params=[])
    {

    }

    public function AssignNotificationsToUser($notification_id,$params=[])
    {


    }



}
