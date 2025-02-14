<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App,Auth;
use Session;
use App\site;
use App\course;
use App\LessonOld;
use App\Rate;
use App\Teacher;
use DB;
class teacherController extends Controller
{

  public function index(Request $request)
  {
      $lang = App::getLocale() ;
      $data = $this->data($request,'teachers');
      $this->seoInfo('page_inf','teachers');
      $data['title_page'] = "teachers";
      $data['teachers'] = Teacher::active()->whereTranslation('locale', app()->getlocale())->orderBy('sort')->paginate(60);

      return  view('front.content.teachers.index',$data);
  }

  public function show(Request $request,$alis)
  {

        $lang = App::getLocale() ;
        $data = $this->data($request,'teachers');

        // $name=str_replace('_', ' ', $alis);
        // $data['teachers'] = IsTeacher::where('name', $name)->active()->firstorfail();
        $data['teachers'] = Teacher::whereTranslation('locale', app()->getlocale())
            ->whereTranslation('alias', $request->name)
            ->active()
            ->with('country')
            ->firstorfail();


        $this->seoInfo('tracher', $data['teachers']->id, $data['teachers']);

        $ids= $data['teachers']->lessons->pluck('course_id');

        $data['courses'] = course::where('status',1)->whereTranslation('locale', app()->getlocale())->wherein('id',$ids)->get();

        $data['title_page'] = __('core.teachers') . '|' . $data['teachers']->name;

        return  view('front.content.teachers.show',$data);

    }

    public function rated(Request $request)
     {

       $teacher=Teacher::where('id',$request->teacher_id)->first();
       if($teacher){
           if(Auth::guard('web')->user()){
             $old = Rate::where('teacher_id', $request->teacher_id)->where('user_id', Auth::guard('web')->user()->id)->get();

             if(!$old){

               $create_rate=Rate::create([
                 'teacher_id'=>$request->teacher_id,
                 'user_id'=>Auth::guard('web')->user()->id,
                 'rated'=>$request->rate

               ]);
               if($create_rate){
                 $new = Rate::where('teacher_id', $request->teacher_id)->get();
                 $teacher->number_rated= $new->count();
                 $teacher->rated=$new->sum('rated')/$new->count();
                 $teacher->save();
               }
             }

           }else{
             $create_rate=Rate::create([
               'teacher_id'=>$request->teacher_id,
               'user_id'=>null,
               'rated'=>$request->rate

             ]);
             if($create_rate){
               $new = Rate::where('teacher_id', $request->teacher_id)->get();
               $teacher->number_rated= $new->count();
               $teacher->rated=round($new->sum('rated')/$new->count(), 1);
               $teacher->save();
             }


           }
         }

         $data["rated"]=$teacher->rated;
         $data["number_rated"]=$teacher->number_rated;
         return  $data;

     }

}
