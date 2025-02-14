<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App;
use App\course;
use App\member;
use App\Services\PartnerService;

class PartnerSubscription extends Controller
{

    public function __construct(private PartnerService $partnerService)
    {
        //
    }

    public function index(Request $request)
    {

        // ini_set('memory_limit', -1);
        $data['get'] = $request->input();

        $data['result'] = member::select('id','name','email','status','created_at','deleted_at')->withTrashed()->orderBy('members.id', 'ASC');

        if (!empty($data['get']['term'])) {
          $data['result']
            ->where('email','like','%'.$data['get']['term'].'%')
            ->orwhere('name','like','%'.$data['get']['term'].'%')
            ->orwhere('phone','like','%'.$data['get']['term'].'%');
        }

        $data['result'] = $data['result']->paginate(10);

        return view ('back.content.partners.index',$data);

    }


    // public function getUserDetails(Request $request)
    // {

    //       $user = member::where('id', $request->params['userId'])->first();
    //       if (!$user){
    //         return response()->json('');
    //       }

    //       // اختبارات الطالب
    //       if ($request->params['detailsType'] == 'USER_COURSES') {
    //           return response()->json([
    //             'data' => $this->globalService->renderUserCoursesMaxDegrees($user)
    //           ]);
    //       }

    //       // الدورات التى لم يشترك بها الطالب
    //       if ($request->params['detailsType'] == 'USER_COURSES_DOESNT_SUBSCRIPE') {
    //           return response()->json([
    //             'data' => $this->globalService->renderCoursesUserDoesntSubscripeIn($user)
    //           ]);
    //       }

    //       // الدورات التى لم يختبرها الطالب
    //       if ($request->params['detailsType'] == 'USER_COURSES_ACTIVE_NOT_TESTED') {
    //           return response()->json([
    //             'data' => $this->globalService->renderCoursesNotTestedForUser($user)
    //           ]);
    //       }

    //       // اشتراكات الطالب
    //       if ($request->params['detailsType'] == 'USER_SUBSCRIPTIONS') {
    //           return response()->json([
    //             'data' => $this->globalService->renderUserSubscriptions($user)
    //           ]);
    //       }

    //       if ($request->params['detailsType'] == 'USER_TEST_RESULT_ANSWERS') {
    //           return response()->json([
    //             'data' => $this->globalService->renderUserTestResultAnswers($request->params)
    //           ]);
    //       }



    //       // compare user cources results between dynamic function
    //       if ($request->params['detailsType'] == 'USER_COMPARE_COURSES') {

    //           $user = member::where('id', $request->params['userId'])->first();

    //           $dynamicService = new \App\Services\UserResultsService();
    //           $dynamicDetails = $dynamicService->setUser( $user )->getUserCoursesTestsResults();

    //           $staticService = new \App\Services\ResultsService();
    //           $staticDetails =  $staticService->setUser( $user )->getFinalUserCourseResult();

    //           return response()->json([
    //             'data' => view('common.members.user-courses', ['detailsType' => 'USER_COMPARE_COURSES', 'dynamicDetails' => $dynamicDetails, 'staticDetails' => $staticDetails])->render()
    //           ]);

    //       }


    //       // return view('front.reports_global.sta_search_user_courses',compact('report'));


    // }

    // public function create()
    // {
    //     return view ('back.content.members.create');
    // }

    // public function changeUserStatus(Request $request)
    // {
    //     if(! $request->user_id){
    //       return back();
    //     }

    //     $user = member::withTrashed()->findOrFail($request->user_id);
    //     if ($user->deleted_at){
    //       $user->restore();
    //     } else {
    //       $user->delete();
    //     }


    //     return back()->with('success', 'Member Disabled!');
    // }

   
    // public function status(Request $request)
    // {
    //     $id = \Route::input('member');
    //     $status = $request->input('status');

    //     $save = member::findOrFail($id);
    //     $save->status = intval($status);
    //     $save->updated_by = Auth::guard('admin')->user()->id;
    //     $save->save();

    //     return redirect()->route('dashboard.members.index')->with('success', $status ? 'Member Enabled Successfully!' : 'Member Disabled Successfully!');
    // }

    // public function destroy(Request $request)
    // {
    //     $id = \Route::input('member');

    //     $row = member::where(['status' => 0,'id' => $id])->firstOrFail();
    //     $row->delete();

    //     return redirect()->route('dashboard.members.index')->with('success', 'Member deleted Successfully!');
    // }



}
