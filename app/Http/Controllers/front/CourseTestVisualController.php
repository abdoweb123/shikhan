<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;

use App\CourseTestVisual;
use App\MemberTestVisualUploads;
use App\CourseTestVisualType;
use App\course_test_result;
use App\Services\GlobalService;
use App\site;
use Auth;
use DB;
use Validator;
use App\helpers\UtilHelper;

class CourseTestVisualController extends Controller
{

  // private $courseService;
  // private $globalService;
  //
  // public function __construct(CourseService $courseService, GlobalService $globalService)
  // {
  //     $this->courseService = $courseService;
  //     $this->globalService = $globalService;
  // }


  public function show(Request $request)
  {

      $validator = Validator::make(['site' => $request->site , 'course' => $request->course ], [
         'site'=>'required|string|max:250',
         'course'=>'required|string|max:250',
      ]);

      if ($validator->fails()) {
          return redirect()->back()->withInput()->withErrors($validator);
      }


      $data = $this->data($request,'courses');


      $data['site'] = site::where(['status' => 1,'alias' => $request->site])->firstOrFail();
      $data['course'] = $data['site']->courses()->where(['status' => 1])->whereTranslation('alias',$request->course)->firstOrFail();

      $data['courseTestVisual'] = Auth::user()->course_tests_visual()
          ->with(['members_tests_visual_uploads' => function($q){
              return $q->where('language', app()->getlocale());
          }])
          ->where('site_id', $data['site']->id)
          ->where('course_id', $data['course']->id)
          ->where('language', app()->getlocale())
          ->first();

      $this->seoInfo('course',$data['course']->id);

      $data['title_page']= $data['course']->title;

      return  view('front.content.courses.show_test_visual_uploads',$data);

  }

  public function upload(Request $request)
  {

        $request->validate([
          'site_id' => 'required|exists:sites,id',
          'course_id' => 'required|exists:courses,id',
          'upload' => 'required|file|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav,mp4,mov,ogg,ogx,oga,ogv,webm|max:100000',
          'title' => 'nullable|string|max:100',
        ],[
          'upload' => 'الملف',
          'upload.required' => 'الملف مطلوب ',
          'upload.mimes' => 'الملف يجب ان يكون بأحد الامتدادات التالية mpeg,mpga,mp3,wav,mp4,mov,ogg,ogx,oga,ogv,webm',
          'upload.max' => 'اقصى حد للملف 60 ميجا',
          'title.required' => 'ادخل العنوان',
          'title.max' => 'اقصى عدد حروف هو 100 حرف'
        ]);



        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
        ini_set('max_input_time', 300);
        ini_set('max_execution_time', 300);




        $path = $request->file('upload')->storeAs(
            'visual_tests', Auth::id().'_'.$request->site_id.'_'.$request->course_id.'_'.$request->file('upload')->hashName()
        );


        // will activate it to prevent inserting in one table in ignore the other table
        // DB::transaction(function () {
            $stored = CourseTestVisual::updateOrCreate([
                'user_id' => Auth::id(),
                'site_id' => $request->site_id,
                'course_id' => $request->course_id,
                'language' => app()->getlocale()
            ]);

            $stored = MemberTestVisualUploads::create([
                'course_test_visual_id' => $stored->id,
                'title' => $request->title,
                'language' => app()->getlocale(),
                'type' => $request->file('upload')->getClientMimeType(),
                'file' => $path,
            ]);
        // });

        return back()->with('message', 'تم الحفظ');

  }

  public function quizCorrection(Request $request)
  {


      $validator = Validator::make(['site' => $request->site , 'course' => $request->course ], [
         'site'=>'required|string|max:250',
         'course'=>'required|string|max:250',
      ]);

      if ($validator->fails()) {
          return redirect()->back()->withInput()->withErrors($validator);
      }

      $data = $this->data($request,'courses');

      $data['site'] = site::where(['status' => 1,'alias' => $request->site])->select('id', 'title')->firstOrFail();
      $data['course'] = $data['site']->courses()->where(['status' => 1])->whereTranslation('alias',$request->course)->firstOrFail();
      $data['types'] = CourseTestVisualType::all();

      $lesson = $data['course']->lessons->first();

      if( $lesson && $lesson->teacher->id != Auth::guard('web')->user()->teacher_id){
        return redirect()->route('home');
      }

      $searchStudentName = $request->query('name');
      $searchStudentName = $searchStudentName ? UtilHelper::formatNormal($searchStudentName) : null;

      $data['studentsTests'] = CourseTestVisual::where('site_id', $data['site']->id)
        ->where('course_id', $data['course']->id)
        ->when($searchStudentName, function($query) use($searchStudentName){
            return $query->wherehas('member', function($q) use($searchStudentName){
              return $q->where('name_search', 'like', '%'.$searchStudentName.'%');
            });
        })
        ->with(['members_tests_visual_uploads' => function($q){
            return $q->where('language', app()->getlocale());
        },'member:id,name'])
        ->orderBy('rate', 'asc')
        ->orderBy('id', 'asc')
        ->paginate(5);

      return view('front.content.courses.quiz_correction',$data);

  }

  public function correct(Request $request)
  {

      $validatedData = $request->validate([
        'comment' => 'nullable|string|max:500',
        'rate'=>'in:1,2',
      ],[
        'comment.max' => 'النص لا يزيد عن 500 جرف',
        'rate.in'=>'برجاء اختيار الدرجة'
      ]);

      $data['site'] = site::where(['status' => 1,'id' => $request->site_id])->select('id')->firstOrFail();
      $data['course'] = $data['site']->courses()->where(['status' => 1])->whereTranslation('course_id',$request->course_id)->firstOrFail();

      $lesson = $data['course']->lessons->first();

      if( $lesson && $lesson->teacher->id != Auth::guard('web')->user()->teacher_id){
          return response()->json(['status'=> false]);
      }

      $update = CourseTestVisual::where('site_id', $data['site']->id)
        ->where('course_id', $data['course']->id)
        ->where('user_id', $request->user_id)
        ->where('language', app()->getlocale())
        ->firstOrFail()
        ->update([ 'rate' => $request->rate, 'type_id' => $request->type_id, 'comment' => $request->comment ])
        ;

      // $mainTest = course_test_result::firstOrCreate([
      //       'site_id' => $data['site']->id,
      //       'course_id' => $data['course']->id,
      //       'user_id' => $request->user_id,
      //       'locale' => app()->getlocale()
      //  ]);
      //  $mainTest->visual_rate = $request->rate;
      //  $mainTest->save();


      // $totalDegree = null;
      // $visualDegree = $globalService->siteDegreeRanges($request->rate);
      // if($mainTest->edit_degree){
      //     $totalDegree = ($mainTest->edit_degree * 30 / 100) + ($visualDegree * 70 / 100);
      // } else {
      //     $totalDegree = $visualDegree * 70 / 100;
      // }

      // $mainTest->visual_degree = $visualDegree;
      // $mainTest->degree = $totalDegree;
      // $mainTest->rate = $globalService->siteRateRanges($totalDegree);
      // $mainTest->save();

      return response()->json(['status'=> true]);

  }

  public function delete(Request $request)
  {

      $validatedData = $request->validate([
        'test_visual_id'=>'required|integer',
        'test_upload_id'=>'required|integer',
      ]);



      $data = Auth::user()->course_tests_visual()->where('id', $request->test_visual_id)
          ->where( function($query){
              return $query->where('rate','!=',1)->orWhereNull('rate'); // wherenull('rate') becuase laravel ignore null in this case ('rate','!=',1), it will not get null records   https://stackoverflow.com/questions/28256933/eloquent-where-not-equal-to
          })->firstOrFail()
          ->members_tests_visual_uploads()
          ->where('id', $request->test_upload_id)->firstOrFail();


      \Illuminate\Support\Facades\Storage::delete($data->file);

      $data->delete();

      return back();

  }


}
