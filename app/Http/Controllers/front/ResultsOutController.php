<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use App\member;
use App\MemberSiteCertificate;
use App\site;
use App\course;
use App\Services\UserResultsService;
use Validator;

class ResultsOutController extends Controller
{

    public function showUserResultsOut()
    {
        return view('front.content.certificates.show_user_results_out');
    }

    public function getUserResultsOut(Request $request)
    {

        $request->flash();

        $searchWith = 'code';
        $userResultsService = new UserResultsService();

        $validator = Validator::make(['email' => $request->search],[
          'email' => 'email'
        ]);

        if($validator->passes()){
          $searchWith = 'email';
        }

        $data = [];

        if($searchWith == 'email'){
          $user = member::where('email',$request->search)->first();
          if(!$user){
            return back()->withErrors(['error' => 'لا توجد بيانات']);
          }
          // $data = $this->getUserSitesTestsResults($request, $user);
          $data = $userResultsService->setUser($user)->getUserSitesTestsResults();

        }


        if($searchWith == 'code' && $request->search_type == 'diplom' ){
            $memberSiteCertificate = MemberSiteCertificate::where('code',$request->search)->first();

            if(!$memberSiteCertificate){
              return back()->withErrors(['error' => 'لا توجد بيانات']);
            }

            $site = site::where('id',$memberSiteCertificate->site_id)->first();
            if(!$site){
              return back()->withErrors(['error' => 'لا توجد بيانات']);
            }

            $user = member::where('id',$memberSiteCertificate->user_id)->first();
            if(!$user){
              return back()->withErrors(['error' => 'لا توجد بيانات']);
            }

            $data['sites'] = [$userResultsService->setUser($user)->setSite($site)->getUserSiteTestsResults()];
        }




        if($searchWith == 'code' && $request->search_type == 'course' ){

            $memberCourseCertificate = \App\course_test_result::where('code',$request->search)->first();
            if(!$memberCourseCertificate){
              return back()->withErrors(['error' => 'لا توجد بيانات']);
            }

            $course = course::where('id',$memberCourseCertificate->course_id)->first();
            if(!$course){
              return back()->withErrors(['error' => 'لا توجد بيانات']);
            }

            $user = member::where('id',$memberCourseCertificate->user_id)->first();
            if(!$user){
              return back()->withErrors(['error' => 'لا توجد بيانات']);
            }




            $CertificateHasEjaza = \App\CourseTestVisual::where('user_id', $memberCourseCertificate->user_id)->where('course_id', $memberCourseCertificate->course_id)->first();
            if ($CertificateHasEjaza){
                  // it has ejaza
                  if($memberCourseCertificate->degree < ejazaPointsOfSuccess()){
                    return back()->withErrors(['error' => 'لا توجد بيانات']);
                  }


                  $ejazaService = new \App\Services\EjazaService();
                  $userSucessInEjaza = $ejazaService->setUser($user)
                    ->setSiteId($memberCourseCertificate->site_id)
                    ->setCourseId($memberCourseCertificate->course_id)
                    ->setTestResult($memberCourseCertificate)
                    ->userSucessInEjaza();

                  if(!$userSucessInEjaza){
                    return back()->withErrors(['error' => 'لا توجد بيانات']);
                  }
            } else {
                  // normal certificate not ejaza
                  if($memberCourseCertificate->degree < pointOfSuccess()){
                    return back()->withErrors(['error' => 'لا توجد بيانات']);
                  }
            }


            $memberCourseCertificate->title = $course->title;
            $data['course'] = $memberCourseCertificate;
            $data['course']->date = $memberCourseCertificate->created_at->format('Y-m-d');
            $hijriHelper = new \App\helpers\HijriDateHelper( strtotime($memberCourseCertificate->created_at) );
            $data['course']->date_hijri = $hijriHelper->get_year() . '-' . $hijriHelper->get_month() . '-' . $hijriHelper->get_day();
            $data['certificateCode'] = $memberCourseCertificate->code;

        }


        return redirect()->route('front.show_user_results_out')->with( ['data' => $data, 'user' => $user] );

    }

}
