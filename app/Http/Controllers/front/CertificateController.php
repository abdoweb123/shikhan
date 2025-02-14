<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use App\MemberCourseCertificate;
use App\Translations\TermTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;
use App;
use DB;
use Auth;
use App\site;
use App\Translations\CourseTranslation;
use App\course;
use App\Term;
// use App\course_site;
// use App\course_test_result;
use App\member;
use App\MemberSiteCertificate;
use App\Services\GlobalService;
use App\Services\CourseService;
use App\Services\SettingService;
use App\Services\SiteConditionsService;
use Illuminate\Support\Arr;

class CertificateController extends Controller
{

    private $rangeDegree=1;
    private $addToSiteId = 17;

    public function __construct(
      private GlobalService $globalService,
      private CourseService $courseService,
      private SettingService $settingService,
      private SiteConditionsService $siteConditionsService
    )
    {

    }


//    public function downloadTermCertificate(Request $request)
//    {
//
//        if ($request->type != 'jpg' && $request->type != 'pdf'){
//          return redirect()->route('certificates');
//        }
//
//        $language = App::getLocale();
//        $termResultId = $request->id;
//        $user = Auth::guard('web')->user();
//        $userId = $user->id;
//
//
//        $termTestResult = $user->term_results()->where('id','=',$termResultId)->where('locale', app()->getlocale())->firstOrFail();
//        if ($termTestResult->rate < $this->rangeDegree){
//            return back()->withErrors(['', __('trans.less_than_70')]);
//        }
//
//        $term = Term::findorfail($termTestResult->term_id);
//        $message = $term->translate($termTestResult->locale);
//        $site = $term->site()->select('title')->first();
//        if(! $site){
//          return back()->withErrors(['','Please refer to the administration']);
//        }
//
//        if(! $term->certificate_template_name){
//          return back()->withErrors(['','Please refer to the site administration']);
//        }
//        $term->certificate_template_name = $term->certificate_template_name;
//
//
//        $bgImageName = 'male'; // bg_image
//        if($user->gender == 1){ $bgImageName = 'male';}
//        if($user->gender == 2){ $bgImageName = 'female';}
//
//
//        $bgImage = $this->getBgImage($term->params, $bgImageName);
//        if(! $bgImage){
//          return back()->withErrors(['','Please refer to the academy administration']);
//        }
//
//
//        $examAtHijri = $this->getExamAtHijri($termTestResult->created_at);
//
//        if ($request->type == 'jpg'){
//            $termTemplate = $this->getCertificatesTemplates($term->certificate_template_name.'_jpg', $language); //  'certificate_template_with_sig_jpg'
//            $message->content = $termTemplate;
//
//            return response()->json([ 'errors' => '', 'data' => view('certificates.term_jpg', [
//                'data' => $termTestResult,
//                'exam_at_hijri' => $examAtHijri,
//                'subject' => $message->subject,
//                'content' => $message->content,
//                'term' => $term,
//                'site' => $site,
//                'user' => $user,
//                'bg_image' => app()->getLocale().'_'.$bgImage,
//              ])->render()
//            ]);
//        }
//
//
//        if ($request->type == 'pdf'){
//            $courseTemplate = $this->getCertificatesTemplates($site->certificate_template_name.'_pdf', $language); // 'certificate_template_with_sig_pdf'
//            $message->content = $courseTemplate;
//
//            return response()->json([ 'errors' => '', 'data' => view('certificates.pdf.course_pdf', [
//                'data' => $courseTestResult,
//                'exam_at_hijri' => $examAtHijri,
//                'subject' => $message->subject,
//                'content' => $message->content,
//                'course' => $course,
//                'site' => $site,
//                'bg_image' => app()->getLocale().'_'.$bgImage,
//              ])->render()
//            ]);
//        }
//
//
//
//    }

    // done for aia
    public function downloadSiteCertificate(Request $request)
    {

        $currentTerm = site::where('id', \Route::input('id'))->select('certificate_template')->first();
        abort_if(! $currentTerm , 404);
        abort_if(! $currentTerm->certificate_template , 404);

        // conditions to can print cirt
        // abort_if(! $this->siteConditionsService->userFinishedAtLeastOneOfOldSites($currentTerm, auth()->user()), 404);
        abort_if(! $this->siteConditionsService->userFinishDependents($currentTerm, auth()->user())['userFinishDependents'], 404);


        $templates = json_decode($currentTerm->certificate_template, true);
        $siteCertificateJpg = $templates['site_certificate']['jpg'] ?? '';
        $siteCertificatePdf = $templates['site_certificate']['pdf'] ?? '';
        abort_if(! $siteCertificateJpg, 404);
        abort_if(! $siteCertificatePdf, 404);




        $bgImage = 'site_certificate_male.jpg'; // default if no gender found
        if(Auth::guard('web')->user()->gender == 1){ $bgImage = 'site_certificate_male.jpg';}
        if(Auth::guard('web')->user()->gender == 2){ $bgImage = 'site_certificate_female.jpg';}




        $languages = ['en','sw'];
        $language = App::getLocale();
        $sites = SiteTranslation::where('site_id', \Route::input('id'))
          ->wherein('locale',$languages)
          ->select('site_id','name','locale')->get();

        $site = new \stdClass();
        $site->id = $sites->first()->site_id;
        $site->created_at = $sites->first()->created_at;
        $site->locale = $language;
        $site->title = $sites->where('locale','en')->first() ? $sites->where('locale','en')->first()->name : '';
        $site->title_lang = $sites->where('locale','sw')->first() ? $sites->where('locale','sw')->first()->name : '';


        $user = \Auth::guard('web')->user();


        $user_id = $user->id;
        $user->birthdayLtr = '';
        if ($user->birthday){
          $birthdayLtr = date_create($user->birthday);
          $user->birthdayLtr = date_format($birthdayLtr,"d-m-Y");
        }


        $siteNotCompleted = false;
        if (ourAuth()){
          $siteNotCompleted = true;
        } else {
          $siteNotCompleted = $this->globalService->siteNotCompleted([
              'site_id' => $site->id,
              'exists' => true
          ]);
          abort_if( $siteNotCompleted , 404);
        }




        $userCoursesDegrees = $this->globalService->getUserDegreesOfEachSite(
            $user,
            ['siteId' => $site->id]
        );


        // degree
        $userSitePoints = $this->globalService->getUserSiteDegree($userCoursesDegrees);

        $coursesOfSite  = $this->globalService->getCourses([
          'siteId' => $site->id,
          'count' => true
        ]);


        if (ourAuth()){

        } else {
          if ( $coursesOfSite > count($userCoursesDegrees)){
              abort_if( $siteNotCompleted , 404);
          }
        }



        $site->user_site_degree = $userSitePoints / ($coursesOfSite * 100);
        $site->user_site_degree = round($site->user_site_degree * 100, 2);
        $site->user_site_rate = $this->globalService->siteRateRanges($site->user_site_degree);



        // will not check minimum rate . just print any percntage
        if (ourAuth()){

        } else {
          if ($site->user_site_rate < $this->rangeDegree){
              return back()->withErrors(['', __('trans.less_than_70')]);
          }
        }


        $site->siteCertificateCode = $this->getSiteCertificateCode($site, $user);

        $message = new \stdClass();
        $message->subject = '.';

        $examAtHijri = $this->getExamAtHijri(date('Y-m-d'));



        if ($request->type == 'jpg'){
            $siteTemplate = $this->getCertificatesTemplates($siteCertificateJpg, $language); // 'site_certificate_template_with_sig_jpg'
            $message->content = $siteTemplate;

            return response()->json([ 'errors' => '', 'data' => view('certificates.site_jpg', [
                'data' => $site,
                'user' => $user,
                'exam_at_hijri' => $examAtHijri,
                'subject' => $message->subject,
                'content' => $message->content,
                'bg_image' => $bgImage,
              ])->render()
            ]) ;
        }



        if ($request->type == 'pdf'){
            $siteTemplate = $this->getCertificatesTemplates($siteCertificatePdf, $language); // 'certificate_template_with_sig_pdf'
            $message->content = $siteTemplate;

            return response()->json([ 'errors' => '', 'data' => view('certificates.pdf.site_pdf', [
                'data' => $site,
                'user' => $user,
                'exam_at_hijri' => $examAtHijri,
                'subject' => $message->subject,
                'content' => $message->content,
                'bg_image' => $bgImage,
              ])->render()
            ]) ;
        }


    }


    public function downloadTermCertificate(Request $request)
    {
        $currentTerm = Term::where('id', \Route::input('id'))->first();

//        $currentTerm = Term::where('id', \Route::input('id'))->select('certificate_template_name')->first();
        abort_if(! $currentTerm , 404);
        abort_if(! $currentTerm->certificate_template_name , 404);


        $templates = json_decode($currentTerm->certificate_template_name, true);
        $termCertificateJpg = $templates['term_certificate']['jpg'] ?? '';
        $termCertificatePdf = $templates['term_certificate_courses']['pdf'] ?? '';
        abort_if(! $termCertificateJpg, 404);
        abort_if(! $termCertificatePdf, 404);



        $bgImage = 'term_certificate_male.jpg'; // default if no gender found
        if(Auth::guard('web')->user()->gender == 2){ $bgImage = 'term_certificate_female.jpg';}




        $languages = ['en','ar'];
        $language = App::getLocale();
        $terms = TermTranslation::where('term_id', \Route::input('id'))
            ->wherein('locale',$languages)
            ->select('term_id','name','locale')->get();

        $term = new \stdClass();
        $term->id = $terms->first()->term_id;
        $term->site_id = $currentTerm->site_id;
        $term->created_at = $terms->first()->created_at;
        $term->locale = $language;
        $term->title = $terms->where('locale','ar')->first() ? $terms->where('locale','ar')->first()->name : '';
        $term->title_lang = $terms->where('locale','en')->first() ? $terms->where('locale','en')->first()->name : '';


        $user = \Auth::guard('web')->user();


        $user_id = $user->id;
        $user->birthdayLtr = '';
        if ($user->birthday){
            $birthdayLtr = date_create($user->birthday);
            $user->birthdayLtr = date_format($birthdayLtr,"d-m-Y");
        }


        $currentTermDegree = $currentTerm->term_results->first()->degree;

        $term->user_term_rate = $this->globalService->siteRateRanges($currentTermDegree);
        $term->user_term_degree = $currentTermDegree;

        $term->termCertificateCode = $this->getTermCertificateCode($term, $user);

        $message = new \stdClass();
        $message->subject = '.';

        $examAtHijri = $this->getExamAtHijri(date('Y-m-d'));



        if ($request->type == 'jpg'){
            $termTemplate = $this->getCertificatesTemplates($termCertificateJpg, $language); // 'site_certificate_template_with_sig_jpg'
            $message->content = $termTemplate;

            return response()->json([ 'errors' => '', 'data' => view('certificates.term_jpg', [
                'data' => $term,
//                'data' => $currentTerm,
                'user' => $user,
                'exam_at_hijri' => $examAtHijri,
                'subject' => $message->subject,
                'content' => $message->content,
                'bg_image' => $bgImage,
            ])->render()
            ]) ;
        }



        if ($request->type == 'pdf'){
            $siteTemplate = $this->getCertificatesTemplates($termCertificatePdf, $language); // 'certificate_template_with_sig_pdf'
            $message->content = $siteTemplate;

            return response()->json([ 'errors' => '', 'data' => view('certificates.pdf.term_pdf', [
                'data' => $term,
//                'data' => $currentTerm,
                'user' => $user,
                'exam_at_hijri' => $examAtHijri,
                'subject' => $message->subject,
                'content' => $message->content,
                'bg_image' => $bgImage,
            ])->render()
            ]) ;
        }


    }


    public function downloadCertificate(Request $request)
    {

        if ($request->type != 'jpg' && $request->type != 'pdf'){
            return redirect()->route('certificates');
        }

        $language = App::getLocale();
        $testResultId = explode( '-', \Route::input('id') )[0];
        $termId = explode( '-', \Route::input('id') )[1];
        $user = Auth::guard('web')->user();
        $userId = $user->id;


        $courseTestResult = $user->course_test_results()->where('id','=',$testResultId)->where('locale', app()->getlocale())->firstOrFail();
        if ($courseTestResult->rate < $this->rangeDegree){
            return back()->withErrors(['', __('trans.less_than_70')]);
        }

        $course = $courseTestResult->course;
        $message = $course->translate($courseTestResult->locale);
        $courseTerm = $course->terms()->where('term_id',$termId)->withPivot('params')->first();
//        return $term = $course->with(['terms'=>function($q) use ($termId){
//            $q->where('term_id',$termId);
//        }])->first();

        if(! $courseTerm){
            return back()->withErrors(['','Please refer to the administration']);
        }

        if(! $courseTerm->certificate_template_name){
            return back()->withErrors(['','Please refer to the site administration']);
        }
        $courseTerm->certificate_template_name = $courseTerm->pivot->certificate_template_name;


        $termData = Term::where('id', $termId)->select('id')->first();
        if(! $termData){
            return back()->withErrors(['','Please refer to the site administration for data']);
        }

        $bgImageName = 'male'; // bg_image
        if($user->gender == 1){ $bgImageName = 'male';}
        if($user->gender == 2){ $bgImageName = 'female';}


        $termData->params = $courseTerm->pivot->params;

        $bgImage = $this->getBgImage($termData->params, $bgImageName);
        if(! $bgImage){
            return back()->withErrors(['','Please refer to the academy administration']);
        }


        $examAtHijri = $this->getExamAtHijri($courseTestResult->created_at);

//        return $term->certificate_template_name;

        if ($request->type == 'jpg'){
            $courseTemplate = $this->getCertificatesTemplates($courseTerm->certificate_template_name.'_jpg', $language); //  'certificate_template_with_sig_jpg'
            $message->content = $courseTemplate;

            return response()->json([ 'errors' => '', 'data' => view('certificates.course_jpg_new', [
                'data' => $courseTestResult,
                'exam_at_hijri' => $examAtHijri,
                'subject' => $message->subject,
                'content' => $message->content,
                'course' => $course,
//                'site' => $site,
                'term' => $termData,
                'termData' => $termData,
                'bg_image' => app()->getLocale().'_'.$bgImage,
            ])->render()
            ]);
        }


        if ($request->type == 'pdf'){
            $courseTemplate = $this->getCertificatesTemplates($courseTerm->certificate_template_name.'_pdf', $language); // 'certificate_template_with_sig_pdf'
            $message->content = $courseTemplate;

            return response()->json([ 'errors' => '', 'data' => view('certificates.pdf.course_pdf', [
                'data' => $courseTestResult,
                'exam_at_hijri' => $examAtHijri,
                'subject' => $message->subject,
                'content' => $message->content,
                'course' => $course,
//                'site' => $site,
                'term' => $termData,
                'termData' => $termData,
                'bg_image' => app()->getLocale().'_'.$bgImage,
            ])->render()
            ]);
        }



    }

    // done for aia
    public function downloadSiteCoursesCertificate(Request $request)
    {


        $currentTerm = site::where('id', \Route::input('id'))->select('certificate_template')->first();
        abort_if(! $currentTerm , 404);
        abort_if(! $currentTerm->certificate_template , 404);


        // conditions to can print cirt
        // abort_if(! $this->siteConditionsService->userFinishedAtLeastOneOfOldSites($currentTerm, auth()->user()), 404);
        if (! ourAuth()){
          abort_if(! $this->siteConditionsService->userFinishDependents($currentTerm, auth()->user())['userFinishDependents'], 404);
        }


        $templates = json_decode($currentTerm->certificate_template, true);
        $siteCertificateCourseJpg = $templates['site_certificate_courses']['jpg'] ?? '';
        $siteCertificateCoursePdf = $templates['site_certificate_courses']['pdf'] ?? '';
        abort_if(! $siteCertificateCourseJpg, 404);
        abort_if(! $siteCertificateCoursePdf, 404);


        $languages = ['en','sw'];
        if ($request->type != 'jpg' && $request->type != 'pdf'){
          return redirect()->route('certificates');
        }

        $language = App::getLocale() ;

        // $site = site::findorfail(\Route::input('id'));
        $sites = SiteTranslation::where('site_id', \Route::input('id'))
          ->wherein('locale',$languages)
          ->select('site_id as id','name','locale')->get();

        $site = new \stdClass();
        $site->id = $sites->first()->id;
        $site->created_at = $sites->first()->created_at;
        $site->locale = $language;
        $site->title = $sites->where('locale','sw')->first() ? $sites->where('locale','sw')->first()->name : '';
        $site->title_lang = $sites->where('locale','en')->first() ? $sites->where('locale','en')->first()->name : '';

        $user = \Auth::guard('web')->user();
        $user_id = $user->id ;

        $siteNotCompleted = false;
        $siteNotCompleted = $this->globalService->siteNotCompleted([
            'site_id' => $site->id,
            'exists' => true
        ]);
        if (! ourAuth()){
          abort_if( $siteNotCompleted , 404);
        }


        $userCoursesDegrees = $this->globalService->getUserDegreesOfEachSite(
            $user,
            ['siteId' => $site->id]
        );
        $coursesIds = Arr::pluck($userCoursesDegrees, ['course_id']);


        $courses = CourseTranslation::wherein('course_id', $coursesIds)
          ->wherein('locale',$languages)
          ->select('id','course_id','name','locale')->get();



        foreach ($userCoursesDegrees as $course) {
          $course->course_degree = $this->globalService->siteRateRanges($course->max_degree);
          $course->title = $courses->where('locale','sw')->where('course_id',$course->course_id)->first() ? $courses->where('locale','sw')->where('course_id',$course->course_id)->first()->name : '';
          $course->title_lang = $courses->where('locale','en')->where('course_id',$course->course_id)->first() ? $courses->where('locale','en')->where('course_id',$course->course_id)->first()->name : '';
        }






        // degree
        $userSitePoints = $this->globalService->getUserSiteDegree($userCoursesDegrees); // 1086
        $coursesOfSite  = $this->globalService->getCourses([
          'siteId' => $site->id,
          'count' => true
        ]);

        $site->user_site_degree = $userSitePoints / ($coursesOfSite * 100);
        $site->user_site_degree = round($site->user_site_degree * 100, 2);
        $site->user_site_rate = $this->globalService->siteRateRanges($site->user_site_degree);



        if ( $coursesOfSite > count($userCoursesDegrees) ) {
          if (! ourAuth()){
            abort_if( $siteNotCompleted , 404);
          }
        }


        // will not check minimum rate . just print any percntage
        if ($site->user_site_rate < $this->rangeDegree){
            return back()->withErrors(['', __('trans.less_than_70')]);
        }






        $data = new \stdClass();
        $data->locale = $language;
        $currentDate = date('Y-m-d');
        $examAtHijri = $this->getExamAtHijri($currentDate);

        if ($request->type == 'jpg'){
            $siteCoursesTemplate = $this->getCertificatesTemplates($siteCertificateCourseJpg, $language); // 'certificate_template_site_courses_degree_jpg'


            $content = $siteCoursesTemplate;

            $html = $this->convertUserSiteCoursesDegreeToHtml($userCoursesDegrees, 'jpg');

            return response()->json([ 'errors' => '', 'data' => view('certificates.site_courses_jpg', [
                'data' => $data,
                'language' => $language,
                'userCoursesDegrees' => $userCoursesDegrees,
                'user' => $user,
                'exam_at_hijri' => $examAtHijri,
                'content' => $content,
                'site' => $site,
                'currentDate' => $currentDate,
                'html' => $html
              ])->render()
            ]) ;
        }



        if ($request->type == 'pdf'){
            $siteCoursesTemplate = $this->getCertificatesTemplates($siteCertificateCoursePdf, $language); // 'test_pdf_as_jpg'
            $content = $siteCoursesTemplate;
            $content = str_replace("https://www.aia-academy.com","",$content);
            // $content = str_replace("height: 800px","height: 1754px",$content); // 3508
            // $content = str_replace("width: 565px","width: 1240px",$content); // 2480

            $html = $this->convertUserSiteCoursesDegreeToHtml($userCoursesDegrees, 'pdf');

            return response()->json([ 'errors' => '', 'data' => view('certificates.pdf.site_courses_pdf', [
                'data' => $data,
                'language' => $language,
                'userCoursesDegrees' => $userCoursesDegrees,
                'user' => $user,
                'exam_at_hijri' => $examAtHijri,
                'content' => $content,
                'site' => $site,
                'currentDate' => $currentDate,
                'html' => $html
              ])->render()
            ]) ;
        }


    }

    // not done for aia
    public function downloadEjazaCertificate(Request $request)
    {

        if ($request->type != 'jpg' && $request->type != 'pdf'){
          return redirect()->route('certificates');
        }

        $language = App::getLocale() ;
        $testResultId = explode( '-', \Route::input('id') )[0];
        $siteId = explode( '-', \Route::input('id') )[1];
        $userId = \Auth::guard('web')->id();

        $courseTestResult = App\member::find($userId)->test_results()->where('id','=',$testResultId)->firstOrFail();




        // check success -------------------------------------------------------------
        $ejazaService = new \App\Services\EjazaService();
        $ejazaService->setUser(auth()->user())->setSiteId($siteId)->setCourseId($courseTestResult->course_id)->setTestResult($courseTestResult);

        if (! $ejazaService->userSucessInEjaza()){
            return back()->withErrors(['', __('trans.less_than',[ 'degree' => ejazaPointsOfSuccess()]) ]);
        }


        $course = $courseTestResult->course;
        $message = $course->translate($courseTestResult->locale);
        $site = $course->sites()->where('site_id',$siteId)->select('title','extra_certificate_templates')->first();
        if(! $site){
          return back()->withErrors(['','برجاء الرجوع لإدارة الموقع']);
        }

        $site->certificate_template_name = json_decode($site->extra_certificate_templates, true)['ejaza'] ?? '';
        if(! $site->certificate_template_name){
          return back()->withErrors(['','برجاء الرجوع لإدارة الموقع']);
        }

        // dd($site->certificate_template_name);

        $courseTestsVisual = Auth::user()->course_tests_visual()
            ->where('site_id', $courseTestResult->site_id)
            ->where('course_id', $courseTestResult->course_id)
            ->where('language', app()->getlocale())
            ->firstorfail();






          $gender = 'male';
          if(Auth::guard('web')->user()->gender == 1){ $gender = 'male';}
          if(Auth::guard('web')->user()->gender == 2){ $gender = 'female';}



        $certificateName = $gender.'_ejaza_'.$request->type.'_type_'.$courseTestsVisual->type_id;
        if (! isset($site->certificate_template_name[$certificateName])){
          return back()->withErrors(['','برجاء الرجوع للادارة']);
        }

        if (! isset($site->certificate_template_name[$certificateName]['name'])){
          return back()->withErrors(['','برجاء الرجوع لإدارة الموقع']);
        }
        $currentCertificateName = $site->certificate_template_name[$certificateName]['name'];


        if (! isset($site->certificate_template_name[$certificateName]['bg_image'])){
          return back()->withErrors(['','برجاء الرجوع لإدارة الموقع']);
        }
        $currentCertificateBgImage = $site->certificate_template_name[$certificateName]['bg_image'];
        // dd($currentCertificateBgImage);



        $data = new \stdClass();
        $data->locale = $language;
        $currentDate = $courseTestResult->created_at->format('Y-m-d'); // date('Y-m-d')
        $examAtHijri = $this->getExamAtHijri($currentDate);



        if ($request->type == 'jpg'){
            $courseTemplate = $this->getCertificatesTemplates($currentCertificateName, $language); //  'certificate_template_with_sig_jpg'
            $message->content = $courseTemplate;

            return response()->json([ 'errors' => '', 'data' => view('certificates.ejaza_jpg', [
                'data' => $data,
                'exam_at_hijri' => $examAtHijri,
                'current_date' => $currentDate,
                'subject' => $message->subject,
                'content' => $message->content,
                'courseTestResult' => $courseTestResult,
                'course' => $course,
                'site' => $site,
                'bg_image' => $currentCertificateBgImage,
              ])->render()
            ]) ;
        }



        if ($request->type == 'pdf'){
            $courseTemplate = $this->getCertificatesTemplates('test_pdf_as_jpg', $language); //  $currentCertificateName
            $message->content = $courseTemplate;
            $message->content = str_replace("https://www.baldatayiba.com", "", $message->content);

            return response()->json([ 'errors' => '', 'data' => view('certificates.pdf.ejaza_pdf', [
                'data' => $data,
                'exam_at_hijri' => $examAtHijri,
                'current_date' => $currentDate,
                'subject' => $message->subject,
                'content' => $message->content,
                'courseTestResult' => $courseTestResult,
                'course' => $course,
                'site' => $site,
                'bg_image' => $currentCertificateBgImage,
              ])->render()
            ]) ;
        }


    }


    // not done for aia
    public function downloadMainAdvancedSiteCertificate(Request $request)
    {

        $globalCertificateService = new \App\Services\GlobalCertificate\GlobalCertificateService();
        $activeAdvancedSiteCertificate = $globalCertificateService->getActiveAdvancedSiteCertificate();
        dd($activeAdvancedSiteCertificate);

        abort_if(! $activeAdvancedSiteCertificate , 404);


        $userAdvancedSiteCertificateDetails = $globalCertificateService->setUser(auth()->user())
          ->setGlobalCertificate($activeAdvancedSiteCertificate)
          ->getUserAdvancedSiteCertificateDetails();
        abort_if(! $userAdvancedSiteCertificateDetails->hasAdvancedSiteCertificate , 404);



        $templates = $activeAdvancedSiteCertificate->certificate_template_name;
        abort_if(! isset($templates['main']) , 404);


        $mainTempalteJpg = $templates['main']['jpg'] ?? '';
        $mainTempaltePdf = $templates['main']['pdf'] ?? '';
        abort_if(! $mainTempalteJpg, 404);
        abort_if(! $mainTempaltePdf, 404);


        $user = \Auth::guard('web')->user();

        $mainBgImage = 'main_advanced_site_certificate_male.jpg';
        if($user->gender == 2){ $mainBgImage = 'main_advanced_site_certificate_female.jpg';}


        $user->birthdayLtr = '';
        if ($user->birthday){
          $birthdayLtr = date_create($user->birthday);
          $user->birthdayLtr = date_format($birthdayLtr,"d-m-Y");
        }




        $language = 'ar';

        $data = new \stdClass();
        $data->locale = $language;
        $data->user_sites_full_degree = $userAdvancedSiteCertificateDetails->userSitesFullDegree;
        $data->user_sites_full_rate = $userAdvancedSiteCertificateDetails->userSitesFullRate;
        $data->created_at = date('Y-m-d');
        $data->certificate_code = $userAdvancedSiteCertificateDetails->globalCertificate->code;
        $data->countUserSuccessedSites = $userAdvancedSiteCertificateDetails->countUserSuccessedSites;
        $data->sumSitesValidCourses = $userAdvancedSiteCertificateDetails->sumSitesValidCourses;
        $data->sumSitesVideosDuration = $userAdvancedSiteCertificateDetails->sumSitesVideosDuration;


        $examAtHijri = $this->getExamAtHijri(date('Y-m-d'));

        if ($request->type == 'jpg'){
            $siteTemplate = $this->getCertificatesTemplates($mainTempalteJpg, $language);

            return response()->json([ 'errors' => '', 'data' => view('certificates.advanced_site.main_jpg', [
                'user' => $user,
                'data' => $data,
                'exam_at_hijri' => $examAtHijri,
                'content' => $siteTemplate,
                'bg_image' => $mainBgImage,
              ])->render()
            ]) ;
        }


        if ($request->type == 'pdf'){
            $siteTemplate = $this->getCertificatesTemplates($mainTempaltePdf, $language);

            return response()->json([ 'errors' => '', 'data' => view('certificates.advanced_site.main_pdf', [
                'user' => $user,
                'data' => $data,
                'exam_at_hijri' => $examAtHijri,
                'content' => $siteTemplate,
                'bg_image' => $mainBgImage,
              ])->render()
            ]) ;
        }


    }

    // not done for aia
    public function downloadDetailsAdvancedSiteCertificate(Request $request)
    {

        $globalCertificateService = new \App\Services\GlobalCertificate\GlobalCertificateService();
        $activeAdvancedSiteCertificate = $globalCertificateService->getActiveAdvancedSiteCertificate();
        abort_if(! $activeAdvancedSiteCertificate , 404);


        $userAdvancedSiteCertificateDetails = $globalCertificateService->setUser(auth()->user())
          ->setGlobalCertificate($activeAdvancedSiteCertificate)
          ->getUserAdvancedSiteCertificateDetails();
        abort_if(! $userAdvancedSiteCertificateDetails->hasAdvancedSiteCertificate , 404);


        $templates = $activeAdvancedSiteCertificate->certificate_template_name;
        abort_if(! isset($templates['details']) , 404);

        $detailsTempalteJpg = $templates['details']['jpg'] ?? '';
        $detailsTempaltePdf = $templates['details']['pdf'] ?? '';
        abort_if(! $detailsTempalteJpg, 404);
        abort_if(! $detailsTempaltePdf, 404);


        $user = \Auth::guard('web')->user();

        $detailsBgImage = 'details_advanced_site_certificate_male.jpg';
        if($user->gender == 2){ $detailsBgImage = 'details_advanced_site_certificate_female.jpg';}


        $user->birthdayLtr = '';
        if ($user->birthday){
          $birthdayLtr = date_create($user->birthday);
          $user->birthdayLtr = date_format($birthdayLtr,"d-m-Y");
        }




        $language = 'ar';

        $data = new \stdClass();
        $data->locale = $language;
        $data->user_sites_full_degree = $userAdvancedSiteCertificateDetails->userSitesFullDegree;
        $data->user_sites_full_rate = $userAdvancedSiteCertificateDetails->userSitesFullRate;
        $data->created_at = date('Y-m-d');
        $data->certificate_code = $userAdvancedSiteCertificateDetails->globalCertificate->code;
        $data->countUserSuccessedSites = $userAdvancedSiteCertificateDetails->countUserSuccessedSites;
        $data->sumSitesValidCourses = $userAdvancedSiteCertificateDetails->sumSitesValidCourses;
        $data->sumSitesVideosDuration = $userAdvancedSiteCertificateDetails->sumSitesVideosDuration;


        $examAtHijri = $this->getExamAtHijri(date('Y-m-d'));

        if ($request->type == 'jpg'){
            $siteTemplate = $this->getCertificatesTemplates($detailsTempalteJpg, $language);

            return response()->json([ 'errors' => '', 'data' => view('certificates.advanced_site.details_jpg', [
                'user' => $user,
                'data' => $data,
                'exam_at_hijri' => $examAtHijri,
                'content' => $siteTemplate,
                'bg_image' => $detailsBgImage,
                'html' => $this->convertUserAdvancedSitesToHtml($userAdvancedSiteCertificateDetails->successedSites, 'jpg')
              ])->render()
            ]) ;
        }


        if ($request->type == 'pdf'){
            $siteTemplate = $this->getCertificatesTemplates($detailsTempaltePdf, $language);

            return response()->json([ 'errors' => '', 'data' => view('certificates.advanced_site.details_pdf', [
                'user' => $user,
                'data' => $data,
                'exam_at_hijri' => $examAtHijri,
                'content' => $siteTemplate,
                'bg_image' => $detailsBgImage,
                'html' => $this->convertUserAdvancedSitesToHtml($userAdvancedSiteCertificateDetails->successedSites, 'pdf')
              ])->render()
            ]) ;
        }


    }


    // شهادات زائدة للدبلوم
    public function downloadExtraCertificate(Request $request)
    {

            $extraCertificateId = $request->extra_certificate_id;

            $currentTerm = site::where('id', \Route::input('id'))->with(['extra_certificates' => function($q) use($extraCertificateId) {
                $q->where('id', $extraCertificateId);
            }])->select('id','certificate_template')->first();

            abort_if(! $currentTerm, 404);
            abort_if($currentTerm->extra_certificates->isEmpty(), 404);


            $extraCertificate = $currentTerm->extra_certificates->first();

            $extraCertificateJpg = @$extraCertificate['certificate_template']['jpg'] ?? '';
            $extraCertificatePdf = @$extraCertificate['certificate_template']['pdf'] ?? '';
            abort_if(! $extraCertificateJpg, 404);
            abort_if(! $extraCertificatePdf, 404);



            $user = Auth::guard('web')->user();
            if(! Auth::guard('web')->user()->gender){ $bgImage = $extraCertificate->params->bg_image;}
            if(Auth::guard('web')->user()->gender == 1){ $bgImage = $extraCertificate->params['male'] ? $extraCertificate->params['male'] : $extraCertificate->params['bg_image'];}
            if(Auth::guard('web')->user()->gender == 2){ $bgImage = $extraCertificate->params['female'] ? $extraCertificate->params['female'] : $extraCertificate->params['bg_image'];}



            // conditions  -------------------------------------
            $extraCertificateServicePath = 'App\\Services\\ExtraCertificates\\'.$extraCertificate->alias.'_service';

            $userResultsService = new App\Services\UserResultsServiceStatic();
            $currentTerm = $userResultsService->setUser($user)->setSite($currentTerm)->getUserSiteTestsResults();

            $params = ['site' => $currentTerm, 'user' => $user, 'type' => $request->type, 'extraCertificate' => $extraCertificate];
            $extraCertificateService = new $extraCertificateServicePath($params);
            $extraCertificate->result = $extraCertificateService->getResult();
            $extraCertificate->certificateData = $extraCertificateService->getCertificateData();

            if (! $extraCertificate->result['deserve']) {
              return response()->json(['msg', 'لا يمكن تحميل الشهادة برجاء الاتصال بادارة الموقع']);
            }
            // -------------------------------------------------------








            $message = new \stdClass();
            $message->subject = '.';

            $examAtHijri = $this->getExamAtHijri(date('Y-m-d'));



            $language = 'sw';
            $currentTerm->locale= $language;

            if ($request->type == 'jpg'){
                $siteTemplate = $this->getCertificatesTemplates($extraCertificateJpg, $language); // 'site_certificate_template_with_sig_jpg'
                $message->content = $siteTemplate;

                return response()->json([ 'errors' => '', 'data' => view('certificates.'.$extraCertificate->alias.'_jpg', [
                    'data' => $currentTerm,
                    'certificate_data' => $extraCertificate->certificateData,
                    'user' => $user,
                    'exam_at_hijri' => $examAtHijri,
                    'subject' => $message->subject,
                    'content' => $message->content,
                    'bg_image' => $bgImage,
                  ])->render()
                ]) ;
            }


            if ($request->type == 'pdf'){
                $siteTemplate = $this->getCertificatesTemplates($extraCertificatePdf, $language); // 'certificate_template_with_sig_pdf'
                $message->content = $siteTemplate;

                return response()->json([ 'errors' => '', 'data' => view('certificates.pdf.'.$extraCertificate->alias.'_pdf', [
                    'data' => $currentTerm,
                    'certificate_data' => $extraCertificate->certificateData,
                    'user' => $user,
                    'exam_at_hijri' => $examAtHijri,
                    'subject' => $message->subject,
                    'content' => $message->content,
                    'bg_image' => $bgImage,
                  ])->render()
                ]) ;
            }




    }


    private function getCertificatesTemplates($template, $language)
    {
//        return $template . $language;
        // $certificateTemplate = $this->courseService->getCourseTemplate([
        $certificateTemplate = $this->settingService->getCertificatesTemplates([
          'template' => $template,
          'language' => $language
        ]);

        if (! $certificateTemplate ){
          throw ValidationException::withMessages(['general' => 'برجاء مراجعة ادارة الموقع']);
        }

        return $certificateTemplate;
    }

    private function getExamAtHijri($date)
    {
        $hijriHelper = new \App\helpers\HijriDateHelper( strtotime($date) );
        return $hijriHelper->get_year() . '-' . $hijriHelper->get_month() . '-' . $hijriHelper->get_day();
    }

    private function convertUserSiteCoursesDegreeToHtml($userCoursesDegrees, $type)
    {

        // jpg padding and fonts defferant than pdf

        $rows = '';

        if($type == 'jpg'){
          foreach ($userCoursesDegrees as $key => $degree) {
              $rows = $rows .
                '<tr>'.
                  '<td style="padding: 4px; width: 40%;font-size: 12px;line-height: 15px;">'. $degree->title . '</td>'.
                  '<td style="padding: 4px; width: 7%;font-size: 12px;line-height: 15px;">'. __('trans.rate.'.$degree->course_degree,[], app()->getLocale()) . '</td>'.
                    '<td style="padding: 4px;" width: 6%;font-size: 12px;>'. $degree->max_degree . '</td>'.
                  '<td style="padding: 4px; width: 7%;font-size: 12px;line-height: 15px;">'.  __('trans.rate.'.$degree->course_degree,[], 'en') . '</td>'.
                  '<td style="padding: 4px; width: 40%;font-size: 12px;line-height: 15px;">'. $degree->title_lang . '</td>'.
                '</tr>';
          }
        }


        if($type == 'pdf'){
          foreach ($userCoursesDegrees as $key => $degree) {
              $rows = $rows .
                '<tr>'.
                  '<td style="padding: 13px; width: 40%;font-size: 18px;line-height: 22px;">'. $degree->title . '</td>'.
                  '<td style="padding: 13px; width: 7%;font-size: 18px;line-height: 22px;">'. __('trans.rate.'.$degree->course_degree,[], app()->getLocale()) . '</td>'.
                    '<td style="padding: 13px;" width: 6%;font-size: 20px;>'. $degree->max_degree . '</td>'.
                  '<td style="padding: 13px; width: 7%;font-size: 16px;line-height: 22px;">'.  __('trans.rate.'.$degree->course_degree,[], 'en') . '</td>'.
                  '<td style="padding: 13px; width: 40%;font-size: 20px;line-height: 22px;">'. $degree->title_lang . '</td>'.
                '</tr>';
          }
        }

        return '<table class="table table-bordered table-striped">
          <thead>
            <tr style="font-weight: bold;">
              <th scope="col">Kozi</th>
              <th scope="col">Daraja</th>
              <th scope="col"></th>
              <th scope="col">Degree</th>
              <th scope="col">Course</th>
            </tr>
          </thead>
          <tbody>'. $rows . '</tbody></table>';

    }

    private function convertUserAdvancedSitesToHtml($userSitesResults, $type)
    {

        // jpg padding and fonts defferant than pdf

        $rows = '';

        if($type == 'jpg'){
          foreach ($userSitesResults as $key => $siteResult) {
              $rows = $rows .
                '<tr>'.
                  '<td style="padding: 4px; width: 40%;font-size: 14px;line-height: 15px;">'. optional($siteResult->site)->name . '</td>'.
                  '<td style="padding: 4px; width: 7%;font-size: 12px;line-height: 15px;">'. __('trans.rate.'.$siteResult->user_full_rate,[], app()->getLocale()) . '</td>'.
                    '<td style="padding: 4px;" width: 6%;font-size: 12px;>'. optional($siteResult->site)->courses_count . '</td>'.
                    '<td style="padding: 4px;" width: 6%;font-size: 12px;>'. $siteResult->user_site_degree . '</td>'.
                  '<td style="padding: 4px; width: 7%;font-size: 12px;line-height: 15px;">'.  __('trans.rate.'.$siteResult->user_full_rate,[], 'en') . '</td>'.
                  '<td style="padding: 4px; width: 40%;font-size: 13px;line-height: 15px;">'.optional($siteResult->site)->name . '</td>'.
                '</tr>';
          }
        }


        if($type == 'pdf'){
          foreach ($userSitesResults as $key => $siteResult) {
              $rows = $rows .
                '<tr>'.
                  '<td style="padding: 4px; width: 38%;font-size: 12px;">'. optional($siteResult->site)->name . '</td>'.
                  '<td style="padding: 4px; width: 6%;font-size: 12px;">'. __('trans.rate.'.$siteResult->user_full_rate,[], app()->getLocale()) . '</td>'.
                    '<td style="padding: 4px;" width: 6%;font-size: 12px;>'. optional($siteResult->site)->courses_count . '</td>'.
                    '<td style="padding: 4px;" width: 6%;font-size: 12px;>'. $siteResult->user_site_degree . '</td>'.
                  '<td style="padding: 4px; width: 6%;font-size: 12px;">'.  __('trans.rate.'.$siteResult->user_full_rate,[], 'en') . '</td>'.
                  '<td style="padding: 4px; width: 38%;font-size: 12px;">'. optional($siteResult->site)->name . '</td>'.
                '</tr>';
          }
        }

        return '<table class="table table-bordered table-striped">
          <thead>
            <tr style="font-weight: bold;">
              <th scope="col">الدبلوم</th>
              <th scope="col">التقدير</th>
              <th scope="col">الدورات - courses</th>
              <th scope="col">الدرجة - Degree</th>
              <th scope="col">Rate</th>
              <th scope="col">Course</th>
            </tr>
          </thead>
          <tbody>'. $rows . '</tbody></table>';

    }

    private function getSiteCertificateCode($site, $user)
    {
        $siteCertificateCode = MemberSiteCertificate::where(['site_id' => $site->id, 'user_id' => $user->id])->first();
        if ( $siteCertificateCode ){
          return $siteCertificateCode->code;
        }

        $codeExists = true;
        $siteId = $site->id + $this->addToSiteId ;
        $code = strtoupper($site->locale) . '-' . $siteId . '-' ;

        do {
            $code = $code . $this->globalService->generateRandomString(9, ['upper' => true]);
            $codeExists = MemberSiteCertificate::where('code',$code)->exists();
        } while ( $codeExists == true );

        MemberSiteCertificate::create([
          'site_id' => $site->id ,
          'user_id' => $user->id ,
          'code' => $code ,
        ]);

        return $code;

    }

    private function getTermCertificateCode($term, $user)
    {
        $siteCertificateCode = MemberSiteCertificate::where(['term_id' => $term->id, 'user_id' => $user->id])->first();
        if ( $siteCertificateCode ){
            return $siteCertificateCode->code;
        }

        $codeExists = true;
        $termId = $term->id + $this->addToSiteId ;
        $code = strtoupper($term->locale) . '-' . $termId . '-' ;

        do {
            $code = $code . $this->globalService->generateRandomString(9, ['upper' => true]);
            $codeExists = MemberSiteCertificate::where('code',$code)->exists();
        } while ( $codeExists == true );

        MemberSiteCertificate::create([
            'site_id' => $term->site_id ,
            'term_id' => $term->id ,
            'user_id' => $user->id ,
            'code' => $code ,
        ]);

        return $code;

    }


    private function getBgImage($params, $bg_image)
    {
        return json_decode($params, true)[$bg_image] ?? '';
    }



}
