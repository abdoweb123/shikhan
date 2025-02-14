<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Services\CourseService;
use App\Services\LookupService;
use Illuminate\Http\Request;

use App\Services\LessonService;
use App\Services\LanguageService;
use App\Services\TeacherService;
use App\Services\OptionService;

use App\Translations\LessonTranslation;
use App\Teacher;
use App\course;
use App\LessonOption;
use App\Models\Option;
use App\ItemOptionValueSelector;
use App\ItemOption;

use App\Http\Requests\LessonAdminRequest;

use Illuminate\Support\Str;
// use App\helpers\UtilHelper;
use App\Traits\FileUpload;
use File;
use Auth;
use DB;
use Session;
use Illuminate\Validation\ValidationException;

class LessonController extends Controller
{
    use FileUpload;
//    private $languageService;
//    private $lessonServ;

//    public function __construct(LessonService $lessonService, LanguageService $languageService)
//    {
//        $this->languageService = $languageService;
//        $this->lessonServ = $lessonService;
//    }

    public function __construct(
        private LessonService $lessonService,
        private TeacherService $teacherService,
        private CourseService $courseService,
        private OptionService $optionService,
        private LookupService $lookupService,
        private LanguageService $languageService)
    {
        $this->middleware(function ($request, $next) {
            $this->inputLocale = app('inputLocale');
            $this->share(['inputLocale', $this->inputLocale ]);
            return $next($request);
        });
    }

    public function index(Request $request,$course = null)
    {
        $course = course::query()->find($course);
        if ($course){
            $data = Lesson::query()->where('course_id',$course->id)
                ->select('id','title_general','is_active','deleted_at')->get();
        }
        else{
            $data = Lesson::select('id','title_general','is_active','deleted_at')->get();
        }
//        $data = Lesson::withTrashed()->select('id','title_general','is_active','deleted_at')->get();
        return view('back.content.lessons.index',compact(['data']));
    }

    public function create()
    {
//        return view('back.content.lessons.create', $this->getLookUp());
        return view('back.content.lessons.create', $this->getLookUp());
    }

    public function store(LessonAdminRequest $request)
    {
        $request->flash();

        // Get Old Options data to retrive it if validation faild
        if($request->options) {
            foreach ($request->options as $i => $value) {
                if ($request->types[$i] == 'select') {
                    $oldOptions[]= array($request->options[$i],$request->values[$i],$request->types[$i],$request->SelValue[$i],$request->optionName[$i],$request->titles[$i]);
                }
                if ($request->types[$i] == 'text') {
                    $oldOptions[]= array($request->options[$i],$request->values[$i],$request->types[$i],$request->SelValue[$i],$request->optionName[$i],$request->titles[$i]);
                }
            }
          }
        if (isset($oldOptions)){
          Session::flash('oldOptions', $oldOptions);
        }


        $language = $request->validated()['language'];

        if(! $this->languageService->languageStatus('alies', $language)) {
            throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
        }


        if ($this->lessonService->validateDoublicateAlias($request->validated()['alias'], $language )){
            throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
        }



        try {
            DB::beginTransaction();

            $stored = Lesson::forceCreate([
              'teacher_id' => $request->validated()['teacher_id'],
              'course_id'=>$request->validated()['course_id'],
              'title_general' => $request->validated()['title'],
              'sort' => $request->validated()['sort'],
              'is_active' => $request->validated()['is_active']
            ]);

            if (! $stored) {
              return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
            }


            $storedTranslation = LessonTranslation::forceCreate([
              'lesson_id' => $stored->id,
              'locale' => $language,
              'title' => $request->validated()['title'],
              'alias' => $request->validated()['alias'],
              // 'pdf' => $request->validated()['pdf'],
              // 'sound' => $request->validated()['sound'],
              // 'video' => str_ireplace("watch?v=", "embed/", $request->validated()['video']),
              'link_zoom' => $request->validated()['link_zoom'],
              'started_at' => date('Y-m-d H:i', strtotime($request->validated()['started_at'])),
              'brief' => $request->validated()['brief'],
              'video_duration' => $request['video_duration'],
              'header' => $request->validated()['header'],
              'meta_description' => $request->validated()['meta_description'],
              'meta_keywords' => $request->validated()['meta_keywords'],
              'trans_status' => $request->validated()['is_active'],
              'access_user_id' => Auth::id(),
              'ip' => getUserIp() ,
            ]);



            // save html
            $storedTranslation->html = createHtml(
                Lesson::FOLDER_HTML ,
                $request->validated()['html'],
                ['recordId' => $storedTranslation->id]
            );
            $storedTranslation->save();


            // save options
            if(is_countable($request->options))
            {
                foreach ($request->options as $i => $value)
                {
                    if ($request->types[$i] == 'select')
                    {
                        $post_option_value_selector = new ItemOptionValueSelector();
                        $post_option_value_selector->item_id = $stored->id;
                        $post_option_value_selector->option_id = $request->options[$i];
                        $post_option_value_selector->option_value_id = $request->SelValue[$i];
                        $post_option_value_selector->title = [$language => Str::limit($request->titles[$i],1000,'') ];
                        $post_option_value_selector->save();
                    }

                    if ($request->types[$i] == 'text')
                    {
                        $post_option = new ItemOption();
                        $post_option->item_id = $stored->id;
                        $post_option->option_id = $request->options[$i];
                        $post_option->locale = $language;
                        $post_option->value = Str::limit($request->values[$i],1000,'');
                        $post_option->title = Str::limit($request->titles[$i],1000,'');
                        $post_option->save();

                         // video_image
                        if(($request->video_img =="on")&&(strpos($request->values[$i],'youtube') !== false))
                        {
                           if(strpos($request->values[$i],'iframe') !== false){
                              $video =explode('/',$request->values[$i]);
                              $video_id=explode('" ',$video[4]);
                              $post->img="https://img.youtube.com/vi/".$video_id[0]."/hqdefault.jpg";
                              $post->save();
                            }

                            elseif(strpos($request->values[$i],'watch?v=') !== false){
                              $video =explode('watch?v=',$request->values[$i]);
                              $video_id =explode('&',$video[1]);
                              $post->img="https://img.youtube.com/vi/".$video_id[0]."/hqdefault.jpg";
                              $post->save();

                            }

                              elseif((strpos($request->values[$i],'iframe')== false)&&(strpos($request->values[$i],'embed')!== false)){
                              $video =explode('embed/',$request->values[$i]);
                              $video_id=explode('" ',$video[1]);
                              $post->img="https://img.youtube.com/vi/".$video_id[0]."/hqdefault.jpg";
                              $post->save();
                              }
                        }
                    }
                }
            }


            // upload image
            if( $request->hasFile('image') ) {
                $path = $this->storeFile($request , [
                    'fileUpload' => 'image', 'folder' => Lesson::FOLDER_IMAGE, 'recordId' => $storedTranslation->id,
                ]);
                if (! $path) {
                  return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
                }
                $storedTranslation->image = $path;
                $storedTranslation->save();
            }


            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            dd($ex);
            return back()->withinput()->withErrors(['general' => 'general error' ]);
        }

        return redirect(route('dashboard.lessons.index'));
    }


//    public function edit(Request $request)
//    {
//        ini_set('memory_limit', '512M');
//        $language = $this->getLanguage();
//        if(! $this->languageService->languageStatus('alies', $language)) {
//            return redirect()->route('dashboard.lessons.index')->with('fail', 'Lnaguage not found');
//        }
//
////        $data = Lesson::withTrashed()->where('id', $request->id)->with(['options' => function($q) use ($language)
//        $data = Lesson::where('id', $request->id)->with(['options' => function($q) use ($language)
//                { $q->where('locale','=',$language);},
//                'options.options.option_info'=> function($q) use ($language)
//                { $q->where('locale','=',$language);},
//                'option_values.options.option_info' => function($q) use ($language)
//                { $q->where('locale','=',$language);} ,
//                'option_values.option_value_info' => function($q) use ($language)
//                { $q->where('locale','=',$language);}])
//        ->firstorfail();
//
//        $translation = $data->translate($language);
//        // $LessonOptions = LessonOption::where('lesson_id', $request->id)->where('locale', $language)->get();
//
//        return view('back.content.lessons.edit', compact('data','translation') + $this->getLookUp() ); // ,'LessonOptions'
//
//    }

    public function edit(Request $request)
    {

//        $locale = $this->inputLocale;

        $locale = $request->language;

        $data = Lesson::where('id', $request->id)
            ->with(['options' => function($q) use ($locale) {
                $q->where('locale','=',$locale);
            },
                'options.option',
            ])->firstorfail();


//        return $data;


        $translation = $data->translate($locale);
////        dd($translation);
//        if ($translation !=){
//            return $translation = '';
//        }


        return view('back.content.lessons.edit', compact('data','translation','locale') + $this->getLookUp());

    }

    public function update(LessonAdminRequest $request)
    {
//        return $request;

        $edit = Lesson::findorfail($request->lesson);

        $options = Option::get();

        $language = $request->validated()['language'];

        if(! $this->languageService->languageStatus('alies', $language)) {
            throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
        }

        if ($this->lessonService->validateDoublicateAlias($request->validated()['alias'], $language, $edit->id)) {
            throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
        }




        try {
            DB::beginTransaction();

            $edit->teacher_id = $request->validated()['teacher_id'];
            $edit->course_id=$request->validated()['course_id'];
            $edit->sort = $request->validated()['sort'];
            $edit->is_active = $request->validated()['is_active'];
            $edit->save();

//            return $request['video_duration'];

            $translation = $edit->translations()->updateOrCreate([
               'locale' => $language,
            ],[
              'title' => $request->validated()['title'],
              'alias' => $request->validated()['alias'],
              // 'pdf' => $request->validated()['pdf'],
              // 'sound' => $request->validated()['sound'],
              // 'video' => str_ireplace("watch?v=", "embed/", $request->validated()['video']),
              'link_zoom' => $request->validated()['link_zoom'],
              'started_at' => date('Y-m-d H:i', strtotime($request->validated()['started_at'])),
              'brief' => $request->validated()['brief'],
              'video_duration' => $request['video_duration'],
              'header' => $request->validated()['header'],
              'meta_description' => $request->validated()['meta_description'],
              'meta_keywords' => $request->validated()['meta_keywords'],
              'trans_status' => $request->validated()['is_active'],
              'access_user_id' => Auth::id(),
              'ip' => getUserIp() ,
            ]);




            // save html
            File::delete('storage/app/public/' . $translation->html);
            $edit->html = createHtml(
                Lesson::FOLDER_HTML ,
                $request->validated()['html'],
                ['recordId' => $translation->id]
            );
            $translation->save();




            // upload image
            if ($request->image_remove) {
                File::delete('storage/app/public/' . $translation->image);
                $translation->image = null;
                $translation->save();
            }

            if( $request->hasFile('image') ) {
                $path = $this->storeFile($request , [
                    'fileUpload' => 'image', 'folder' => Lesson::FOLDER_IMAGE, 'recordId' => $translation->id,
                ]);
                if (! $path) {
                  return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
                }
                $translation->Update(['image' => $path]);
            }






            // options
            if(is_countable($request->options)) {
              foreach ($request->options as $i => $value) {
                if ($request->types[$i] == 'select') {
                    $oldOptions[]= array($request->options[$i],$request->values[$i],$request->types[$i],$request->SelValue[$i],$request->optionName[$i],$request->titles[$i]);
                }
                if ($request->types[$i] == 'text') {
                    $oldOptions[]= array($request->options[$i],$request->values[$i],$request->types[$i],$request->SelValue[$i],$request->optionName[$i],$request->titles[$i]);
                }
              }
            }
            if (isset($oldOptions)){
                Session::flash('oldOptions', $oldOptions);
            }

            // delete then insert item_option and item options values
            $post_option_value_selector = ItemOptionValueSelector::where('item_id', $edit->id)->delete();
            $post_option = ItemOption::where('item_id', $edit->id)->where('locale', $language)->delete();

            if(is_countable($request->options)) {
                foreach ($request->options as $i => $value)
                {
                    if ($request->types[$i] == 'select')
                    {
                        $post_option_value_selector = new ItemOptionValueSelector();
                        $post_option_value_selector->item_id = $edit->id;
                        $post_option_value_selector->option_id = $request->options[$i];
                        $post_option_value_selector->option_value_id = $request->SelValue[$i];
                        $post_option_value_selector->title = [$language => Str::limit($request->titles[$i],1000,'')];
                        $post_option_value_selector->save();
                    }

                    if ($request->types[$i] == 'text'){
                        $post_option = new ItemOption();
                        $post_option->item_id = $edit->id;
                        $post_option->option_id = $request->options[$i];
                        $post_option->locale = $language;
                        // $post_option->value = Helper::FormatString( Str::limit($request->values[$i],250,''));
                        $post_option->value = Str::limit($request->values[$i],1000,'');
                        $post_option->title = Str::limit($request->titles[$i],1000,'');
                        $post_option->save();

                        // video_image
                        if(($request->video_img =="on")&&(strpos($request->values[$i],'youtube') !== false)){

                           if(strpos($request->values[$i],'iframe') !== false){
                              $video =explode('/',$request->values[$i]);
                              $video_id=explode('" ',$video[4]);
                              $post->img="https://img.youtube.com/vi/".$video_id[0]."/hqdefault.jpg";
                              $post->save();
                            }

                            elseif(strpos($request->values[$i],'watch?v=') !== false){
                              $video =explode('watch?v=',$request->values[$i]);
                              $video_id =explode('&',$video[1]);
                              $post->img="https://img.youtube.com/vi/".$video_id[0]."/hqdefault.jpg";
                              $post->save();

                            }

                              elseif((strpos($request->values[$i],'iframe')== false)&&(strpos($request->values[$i],'embed')!== false)){
                              $video =explode('embed/',$request->values[$i]);
                              $video_id=explode('" ',$video[1]);
                              $post->img="https://img.youtube.com/vi/".$video_id[0]."/hqdefault.jpg";
                              $post->save();
                              }
                        }
                    }
                 }
            }

            $request->session()->forget('oldOptions');





            if($request->pdf_title){
                $LessonOption=LessonOption::firstOrCreate([
                  'locale'=>$language,
                  'option_id'=>1,
                  'lesson_id'=>$edit->id,
                ]);
                $LessonOption->value=$request->pdf_title;
                $LessonOption->save();
            }



            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            dd($ex);
            return back()->withinput()->withErrors(['general' => 'general error' ]);
        }


        return redirect(route('dashboard.lessons.index'));


    }

//    public function setActive(Request $request)
//    {
//        $data = Lesson::findorfail($request->id);
//        $status = !$data->is_active;
//
//        // if we try to active a lesson so check the parent of it if the parent is inactive then make it active
//        if ($status == 1) {
//          $parent = Lesson::where(['id' => $data->id , 'is_active' => 0 ])->first();
//          if ($parent){
//              if ($request->ajax()) {
//                return response()->json(['status'=>'error', 'msg'=>__('lesson.activate_parent') .' - '. $parent->title_general, 'alert'=>'swal' ]);
//              }
//              return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
//          }
//
//        }
//
//        $this->lessonServ->setActive($data , $status);
//
//        if ($request->ajax()) {
//          return response()->json(['status'=>'success', 'msg'=>__('messages.updated'), 'alert'=>'swal' ]);
//        }
//
//
//
//        // $this->flashAlert([ 'success' => ['msg'=> __('messages.updated') ] ]);
//        return redirect( route('dashboard.lessons.index') );
//
//    }

    public function setActive(Request $request)
    {
        $data = Lesson::findorfail($request->id);
        $status = !$data->is_active;

        $data->is_active = $status;
        $data->save();

        if ($request->ajax()) {
            return response()->json(['status'=>'success', 'msg'=>__('messages.updated'), 'alert'=>'swal' ]);
        }

        session()->flash('success', __('messages.updated'));
        return redirect( route('dashboard.lessons.index') );
    }

    public function destroy(Request $request)
    {

        $delete =  Lesson::where('id',$request->id)->forcedelete();

        $ids = [] ;
        if ($request->ids) {
            $ids = explode(",", $request->ids);
        }
//
//       $delete = Lesson::whereIn('id',$ids)->forcedelete();
//        // if ( $delete !== true ) {
//        //   if ( $request->expectsJson() ) {
//        //     return response()->json(['error' => $delete ]);
//        //   } else {
//        //     return redirect()->back()->withErrors([ $delete ]);
//        //   }
//        // }
//
        if ( $request->expectsJson() ) {
          return response()->json(['success' =>  __('messages.deleted') ]);
        } else {
          return back()->withinput()->withErrors(['success' => __('messages.deleted') ]);
        }

    }


    public function deleteItem(Request $request)
    {
        $lesson =  Lesson::query()->findOrFail($request->id);

        if ($lesson){
            $lesson_translation = LessonTranslation::where('lesson_id',$lesson->id)->forcedelete();
            $lesson->forcedelete();
        }

        return redirect()->back()->with('success', 'Lesson Deleted Successfully!');
    }

//    public function getLookUp()
//    {
//        $teachers = Teacher::select('id','name')->get();
//        $courses =  course::select('id','title')->orderBy('site_id','desc')->get();
//        $options =  Option::get();
//        return compact(['teachers','courses','options']);
//    }

    public function getLookUp()
    {
        return [
            'teachers' => $this->teacherService->getSummary(),
            'courses' => $this->courseService->getSummary(),
            'statuses' => $this->lookupService->getActiveStatuses(),
            'lessonStudyTypes' => $this->lookupService->getActiveLessonStudyTypes(),
            'options' => $this->optionService->get(),
        ];

        return compact(['teachers','courses','statuses','options']);

    }

    private function getLanguage()
    {
      return request()->query('language') ?? null;
    }


}
