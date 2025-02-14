<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use App\Models\Term;
use http\Client\Response;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\site;
use App\course;
use App\Translations\CourseTranslation;
use App\course_site;

use App\LessonOld;

use App\Services\CourseService;
use App\Services\SiteService;
use App\Services\TermService;
use App\Services\LanguageService;


use Illuminate\Support\Facades\Input;
use App\libraries\Helpers;
use Illuminate\Support\Facades\Cache;

//use Validator;
use File;
use Illuminate\Support\Facades\Validator;
use Storage;
use App\Traits\FileUpload;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;

class CourseController extends Controller
{
    use FileUpload;

    public function __construct(
      private CourseService $courseService,
      private SiteService $siteService,
      private TermService $termService,
      private LanguageService $languageService)
    { }


    // from university app
    public function getAll(Request $request)
    {
//        $items = $this->courseService->getCourseInstance();
//        if ($request->name) {
//            $locale = app()->getLocale();
//            $items = $items->whereTranslation('alias', $request->name)
//                ->orWhere('name', 'like', '%'.$request->name.'%');
//        }
//
//        $items = $items->with('status')->paginate($paginate ?? config('domain.paginate'));
        $site_id = \Route::input('site');
        $courses = course::all();
        return view('back.content.courses.getAll',compact('courses','site_id'));
    }

    public function index()
    {
        $site_id = \Route::input('site');
        $data['site_id'] = \Route::input('site');
        $data['term_id'] = \Route::input('term');
        $term_id = $data['term_id'];
        $data['site'] = site::where('id', $data['site_id'])->whereTranslation('locale', app()->getlocale())->select('id','title')->firstOrFail();
//        $data['result'] = $data['site']->courses()->select('courses.id','courses.title','courses.status','link_ended','link')
//          ->with(['terms' => function($q) use($site_id){
//              return $q->where('terms.site_id', $site_id);
//          }])
//        ->orderBy('id', 'ASC')
//        ->get();

        $data['result'] = course::whereHas('terms', function($q) use($term_id){
             $q->where('course_site.term_id', $term_id);
        })->get();

        return view ('back.content.courses.index',$data);
    }

    public function create()
    {
        $data['site_id'] = \Route::input('site');
        $lang = json_decode(Helpers::defult_language());
        $lang_id = $lang->id;
        $data['site'] = site::where('id', $data['site_id'])->with('terms')->firstOrFail();

        return view ('back.content.courses.create', $data);
    }

    public function store(Request $request)
    {

        $site_id = \Route::input('site');

        $request->merge(['alias' => validateAlias(convertToLower(formatNormal($request->alias))) ]);

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'title' => 'required|max:255',
            'alias' => 'required|max:255',
//            'term_id' => 'required|exists:terms,id',
            'duration' => 'required',
            'image' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'video_duration' => 'nullable|date_format:H:i:s',
            'date' => 'nullable|date',
            'limit' => 'min:1',
            'sort' =>  'nullable|integer|unique:courses,sort,'.$request->id,
            'format' => 'required|string', // digits:2|
            'orientation' => 'required|string',
            'header' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'exam_approved' => 'nullable',
            'exam_at' => 'required_if:exam_approved,==,1',
            'max_trys' => 'required|integer|min:1',
        ]);




        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        if(! $this->languageService->languageStatus('alies', $request->language)) {
            throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
        }

        if ($this->courseService->aliasAndLanguageExists($request->alias, $request->language) ){
            throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
        }


        $site = site::where('id', $site_id)->firstOrFail();



        try {
            DB::beginTransaction();

            $stored = course::forceCreate([
                'title' => $request->title,
                'date' => $request->date,
                'format' => $request->format,
                'orientation' => $request->orientation,
                'exam_at' =>$request->exam_at,
                'exam_approved' => $request->exam_approved,
                'max_trys' => $request->max_trys,
                'sort' => $request->sort,
                'status' => $request->is_active,
            ]);

            if (!$stored) {
                return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
            }


            // attach course to site
//            $stored->sites()->attach($site->id, [
//              'term_id' => $request->term_id,
//              'certificate_template_name' => 'certificate_template_with_sig_new',
//              'short_link' => $this->createShortCode()
//            ]);


            $storedTranslation = CourseTranslation::forceCreate([
              'course_id' => $stored->id,
              'locale' => $request->language,
              'name' => $request->title,
              'alias' => $request->alias,
              'date_at' => $request->date,
              'video_duration' => $request->video_duration,
              'duration' => $request->duration,
              'header' => $request->header,
              'meta_description' => $request->meta_description,
              'meta_keywords' => $request->meta_keywords,
              'trans_status' => $request->is_active,
            ]);


            // $stored->link = $this->generateLink($stored);
            // $stored->link_ended = $request->input('date') ? ($request->input('date') . ' 23:59:00') : null;
            // $stored->save();


            // save html
            $storedTranslation->description = createHtml(
                course::FOLDER_HTML ,
                $request->html,
                ['recordId' => $stored->id]
            );
            $storedTranslation->save();


            // upload image
            if( $request->hasFile('image') ) {
                $path = $this->storeFile($request , [
                    'fileUpload' => 'image', 'folder' => site::FOLDER_IMAGE, 'recordId' => $storedTranslation->id,
                ]);
                if (! $path) {
                  return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
                }

                $storedTranslation->image_details = $path;
                $storedTranslation->save();
            }


            if (! $request->header){
              $storedTranslation->header = $request->title.' - '.$site->translate($request->language)->name .' - '. __('core.app_name',[],$request->language);
            }
            if (! $request->meta_description){
              $storedTranslation->meta_description = $request->title.' - '.$site->translate($request->language)->name .' - '. __('core.app_name',[],$request->language);
            }
            if (! $request->meta_keywords){
              $storedTranslation->meta_keywords = $request->title.' - '.$site->translate($request->language)->name .' - '. __('core.app_name',[],$request->language);
            }
            $storedTranslation->save();

            // if(! $request->header or $stored->meta_description == null  or $stored->meta_keywords == null){
            //     $description= null;
            //     // if(file_exists('storage/app/public/'.$stored->description) == true){
            //     //     $description= strip_tags(file_get_contents('storage/app/public/'.$storedTranslation->description ?? '') );
            //     //     $description=str_replace('&nbsp;','',$description);
            //     //     $description=str_replace('▶','',$description);
            //     // }
            //     $techer = ' ';
            //
            //     $header = $stored->name.' - '.$site ->title .' - '. __('core.app_name');
            //     $meta_description = $stored->name.' - '.$site ->title .' - '. __('core.app_name');
            //     $meta_keywords = $stored->name.' - '.$site ->title .' - '. __('core.app_name');
            //
            //     if($stored->header == null ){
            //        $storedTranslation->header = $header;
            //     }
            //     if($stored->meta_description == null ){
            //       $storedTranslation->meta_description = $meta_description;
            //     }
            //     if($stored->meta_keywords == null ){
            //        $storedTranslation->meta_keywords = $meta_keywords;
            //     }
            //     $storedTranslation->save();
            // }

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            dd($ex);
            return redirect()->route('dashboard.courses.create',$site_id)->withinput()->withErrors(['general' => 'general error' ]);
        }

        return redirect()->route('dashboard.courses.create',$site_id)->with('success', 'Course Added Successfully!');

    }

    public function edit(Request $request)
    {

        $data['site_id'] = \Route::input('site');
        $data['id'] = \Route::input('course');

        $data['site'] = site::where('id', $data['site_id'])->select('id')->firstOrFail();

        $language = $this->getLanguage();
        if(! $this->languageService->languageStatus('alies', $language)) {
            return redirect()->route('dashboard.courses.index', $data['site_id'])->with('fail', 'Lnaguage not found');
        }

//        $data['fields'] = $data['site']->courses()->findOrFail($data['id']);
        $data['fields'] = course::findOrFail($data['id']);
        $data['translation'] = $data['fields']->translate($language);

        return view ('back.content.courses.edit', $data);

    }

    public function update(Request $request)
    {


        $site_id = \Route::input('site');
        $id = \Route::input('course');

        $request->merge(['alias' => validateAlias(convertToLower(formatNormal($request->alias))) ]);

        // $request->merge(['term_id' => 1 ]);

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'title' => 'required|max:255',
            'alias' =>'required|max:255',
            'logo' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'date' => 'nullable|date',
            'duration' =>'required',
            'video_duration' => 'nullable|date_format:H:i:s',
            'sort' =>  'nullable|integer',
            'format' => 'required|string', // digits:2|
            'orientation' => 'required|string',
            'header' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'exam_approved' => 'nullable',
            'exam_at' => 'required_if:exam_approved,==,1',
            'max_trys' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        if(! $this->languageService->languageStatus('alies', $request->language)){
            throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
        }

        if ($this->courseService->aliasAndLanguageExists($request->alias, $request->language, $id) ){
            throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
        }


        $site = site::where('id',$site_id)->firstOrFail();

        try {
            DB::beginTransaction();

//            $edit = $site->courses()->findOrFail($id);
            $edit = course::findOrFail($id);
            $edit->title = $request->title;
            $edit->date = $request->date;
            $edit->format = $request->format;
            $edit->orientation = $request->orientation;
            $edit->exam_at = $request->exam_at;
            $edit->exam_approved = $request->exam_approved;
            $edit->max_trys = $request->max_trys;
            $edit->sort = $request->sort;
            $edit->status = $request->is_active;
            $edit->updated_by = Auth::id();
            $edit->save();


            $translation = $edit->translations()->updateOrCreate([
               'locale' => $request->language,
            ],[
              'name' => $request->title,
              'alias' => $request->alias,
              'date_at' => $request->date,
              'video_duration' => $request->video_duration,
              'duration' => $request->duration,
              'header' => $request->header,
              'meta_description' => $request->meta_description,
              'meta_keywords' => $request->meta_keywords,
              'trans_status' => $request->is_active,
            ]);




             // save html
             File::delete('storage/app/public/' . $translation->description);
             $translation->description = createHtml(
                 course::FOLDER_HTML ,
                 $request->html,
                 ['recordId' => $translation->id ]
             );
             $translation->save();


            // remove image
            if ($request->has('image_remove')) {
              File::delete('storage/app/public/'.$translation->image_details);
              $translation->image_details = null;
              $translation->save();
            }


            // upload image
            if ($request->hasFile('image')) {
                $path = $this->storeFile($request , [
                    'fileUpload' => 'image', 'folder' => course::FOLDER_IMAGE, 'recordId' => $translation->id,
                ]);
                if (! $path) {
                  return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
                }

                $translation->image_details = $path;
                $translation->save();
            }



            if (! $request->header){
              $translation->header = $request->title.' - '.$site->translate($request->language)->name .' - '. __('core.app_name',[],$request->language);
            }
            if (! $request->meta_description){
              $translation->meta_description = $request->title.' - '.$site->translate($request->language)->name .' - '. __('core.app_name',[],$request->language);
            }
            if (! $request->meta_keywords){
              $translation->meta_keywords = $request->title.' - '.$site->translate($request->language)->name .' - '. __('core.app_name',[],$request->language);
            }
            $translation->save();


            DB::commit();

        } catch (\Exception $ex) {
            DB::rollback();
            dd($ex);
            return redirect()->route('dashboard.courses.create',$site_id)->withinput()->withErrors(['general' => 'general error' ]);
        }


        // redirect
        return redirect()->route('dashboard.courses.index', $site_id)->with('success', 'Course updated Successfully!');

    }

    public function status(Request $request)
    {
        $site_id = \Route::input('site');
        $id = \Route::input('course');
        $status = $request->input('status');

        $site = site::where('id',$site_id)->firstOrFail();

        $course = $site->courses()->findOrFail($id);
        $course->status = intval($status);
        $course->updated_by = Auth::guard('admin')->user()->id;
        $course->save();

        return redirect()->route('dashboard.courses.index',$site_id)->with('success', $status ? 'Course Enabled Successfully!' : 'Course Disabled Successfully!');
    }

    public function destroy(Request $request)
    {
        $site_id = \Route::input('site');
        $id = \Route::input('course');

        $site = site::where('id',$site_id)->firstOrFail();
//        $row = $site->courses()->findOrFail($id);
        $row = course::findOrFail($id);
        // To delete lessons of course
//        $lessons=$row->lessons()->get();
//        if($lessons){
//          foreach ($lessons as $key => $lesson) {
//            // return$lesson;
//              if (!empty($lesson->logo))Storage::delete(public_path('/'.$lesson->logo));
//
//              if (!empty($lesson->description))File::delete('storage/app/public/' . $lesson->description);
//              $lesson->delete();
//              DB::table('lesson_translations')->where('lesson_id',$lesson->id)->delete();
//          }
//        }
        // return 00;
          if (!empty($row->logo))Storage::delete(public_path('/'.$row->logo));

          if (!empty($row->description))File::delete('storage/app/public/' . $row->description);
        $row->forceDelete();
        DB::table('courses_translations')->where('course_id',$row->id)->delete();
        return redirect()->route('dashboard.courses.index',$site_id)->with('success', 'Course deleted Successfully!');
    }

    public function edit_template(Request $request)
    {
        $data['site_alias'] = \Route::input('site');
        $data['course_id'] = \Route::input('course');
        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();
        $data['course'] = $data['site']->courses()->findOrFail($data['course_id']);

        if (!Cache::has('languages'))
        {
            Cache::forever('languages' , language::where('status',1)->pluck('name','alies')->toArray());
        }
        $data['languages'] = [];
        foreach (Cache::get('languages') as $alias => $name)
        {
            if (in_array($alias,$data['course']->languages))
            {
                $data['languages'][$alias] = $name;
            }
        }
        $data['languages']=\LaravelLocalization::getSupportedLocales();

        // dd($data['course']->templet_lang($data['site']->id));
        return view ('back.content.courses.template',$data);
    }

    public function update_template(Request $request)
    {
      // dd( $request->all());
        $site_alias = \Route::input('site');
        $course_id = \Route::input('course');

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'locale' => 'min:2',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dashboard.courses.template.edit',['site' => $site_alias,'course' => $course_id,'tab' => 'lang-'.$request->input('locale')])->withInput()->withErrors($validator);
        }
        $site = site::where('alias',$site_alias)->firstOrFail();
        $save = $site->courses()->findOrFail($course_id);

        $old_templet_for_lang=$save->templet_lang($site->id, $request->locale);
        // dd($old_templet_for_lang);

        if($old_templet_for_lang == null){
          $certificateTemplates = course_site::where( ['site_id' => $site->id, 'course_id' => $course_id ])->first();
          if($certificateTemplates->certificate_template_name == null || $certificateTemplates->certificate_template == '' ){
            $certificateTemplates->certificate_template_name=$request->locale.'-//-'.$request->content;

          }else {
            $certificateTemplates->certificate_template_name= $certificateTemplates->certificate_template_name.'//-//'.$request->locale.'-//-'.$request->content;
          }

            // dd($certificateTemplates);
            $certificateTemplates->save();
          // $certificateTemplates = course_site::where( ['site_id' => $site->id, 'course_id' => $course_id ])->first();
          // $certificateTemplates->certificate_template= $certificateTemplates->certificate_template.'//-//'.$request->locale.'-//-'.$request->content;
          // $certificateTemplates->save();
        }else{
          /// update  template for this language
          $old_templet_for_lang[$request->locale]=$request->content;
          $certificate_template_name='';
          //get old  certificate Templates
          $certificateTemplates = course_site::where( ['site_id' => $site->id, 'course_id' => $course_id ])->first();
          /// loop for  all templates  for Merge
          foreach ($old_templet_for_lang as $key => $value) {

              if( $certificate_template_name != '' ){

                $certificate_template_name=$certificate_template_name.'//-//';
              }
              $certificate_template_name=$certificate_template_name.$key.'-//-'.$value;
          }

          $certificateTemplates->certificate_template_name=$certificate_template_name;
          $certificateTemplates->save();
        }


        return redirect()->route('dashboard.courses.template.edit',['site' => $site_alias,'course' => $course_id,'tab' => 'lang-'.$request->input('locale')])->with('success', 'Course Template updated Successfully!');
    }

    public function updatelink(Request $request)
    {

        $site = site::where('id', $request->site)->select('sites.id')->firstOrFail();
        $course = $site->courses()->select('courses.id','link')->findOrFail($request->course);

        if (! $course->link){
          $course->link = $this->generateLink($course);
        }
        $course->link_ended = $request->link_ended ?? null;

        $course->save();

        return redirect()->back()->with('success', 'Successfully!');

    }

    private function generateLink($stored)
    {
        return Helpers::generateRandomeString(15) . $stored->id;
    }

    public function to_assign_index()
    {

        // get courses
        $data['result'] = course::whereTranslation('locale', app()->getLocale())
          ->with('terms:id,site_id,title,sort,type')
          ->with('terms.site:id,title')
          ->select('id')
          ->orderBy('id', 'ASC')
          ->get();

        $sites = site::whereTranslation('locale', app()->getlocale())->with('terms')->get();
         $data['sitesTree'] = $this->siteService->getSitesTreeRoot($sites);


        return view ('back.content.courses.to_assign_index', $data);
    }

    public function to_assign_index_post(Request $request)
    {

        if (empty($request->term_id)){
            return redirect()->back()->with('MasterErorr', 'One term should be selected at least!');
        }

        $course = course::where('id',$request->course_id)->firstOrFail();

//        $sites_terms = [];
//        foreach ($request->site_id as $site_id => $term_id) {
//          $sites_terms = Arr::add($sites_terms, $site_id,[
//              'term_id' => $term_id,
//              'certificate_template_name' => 'certificate_template_with_sig_new',
//              // 'short_link' => $this->createShortCode() // dont create hort link here to keep the orginal short link as it was
//            ]);
//        }
//        $course->sites()->syncWithoutDetaching($request->site_id);  // WithoutDetaching

        foreach ($request->term_id as $term_id){

            $syncData[$term_id] = ['site_id'=>$request->site_id, 'certificate_template_name'=>'certificate_template_with_sig_new'];
        }

        $course->terms()->sync($syncData);  // WithoutDetaching


        // create short link for newst attached sites
        $newstRecords = course_site::whereNull('short_link')->get();
        foreach ($newstRecords as $course_site){
            $course_site->short_link = $this->createShortCode();
            $course_site->save();
        }

        return redirect()->back()->with('success', 'Course update Successfully!');

    }

    private function getLanguage()
    {
      return request()->query('language') ?? null;
    }

    private function createShortCode()
    {

        $code = '';
        do {
            $code = \Illuminate\Support\Str::random(5);
            $codeExists = course_site::where('short_link', $code)->exists();
        } while ( $codeExists == true );

        return $code;

    }

    public function createTrack($course_id)
    {
        $course  = Course::query()->findOrFail($course_id);
        $lessons = $course->lessons;
        $tests = $course->tests;

        $site_id = $course->site_id;

        $totalItems = count($lessons) + count($tests) ;

//        return view('back.content.courses.tracks.create', compact('course','lessons', 'tests', 'site_id'));
        return view('back.content.courses.tracks.create_new', compact('course','lessons', 'tests', 'site_id', 'totalItems'));
    }

    public function storeTrack(Request $request)
    {
        // To ensure unique sort values
        if (!$this->checkSortUnique($request)){
            return redirect()->back()->with(['fail'=>'يجب أن تكون قيم الترتيب فريدة']);
        }

        $validator = Validator::make($request->all(),[
            'course_id'  => 'required',
            'lesson_ids' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $course = Course::find($request->course_id);

        $this->attachToCourse($request->lesson_ids,$course,'lessons');
        if($request->test_ids){
            $this->attachToCourse($request->test_ids,$course,'tests');
        }

        return redirect()->route('dashboard.courses.getAll',['site'=>$request->site_id])
            ->with('success','Path is Added Successfully');
    }

    public function editTrack($course_id)
    {
        $course  = Course::find($course_id);
        $lessons = $course->lessons;
        $tests = $course->tests;
        $site_id = $course->site_id;

//        foreach ($tests as $test){
////            return $lesson->course;
//            return \App\Models\CourseTrack::where('courseable_type','tests')->where('courseable_id',$test->id)->first()->sort;
//        }


//        return view('back.content.courses.tracks.edit', compact('course','lessons', 'tests','site_id'));
        return view('back.content.courses.tracks.edit_new', compact('course','lessons', 'tests','site_id'));
    }

    public function updateTrack(Request $request)
    {
        // To ensure unique sort values
        if (!$this->checkSortUnique($request)){
            return redirect()->back()->with(['fail'=>'يجب أن تكون قيم الترتيب فريدة']);
        }


        $validator = Validator::make($request->all(),[
            'course_id'  => 'required',
            'lesson_ids' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $course = Course::find($request->course_id);

        $this->deAttachFromCourse($course,'lessons');
        $this->deAttachFromCourse($course,'tests');

        $this->attachToCourse($request->lesson_ids,$course,'lessons');
        $this->attachToCourse($request->test_ids,$course,'tests');

        return redirect()->route('dashboard.courses.getAll',['site'=>$request->site_id])
            ->with('success','Path is updated Successfully');
    }


    private function attachToCourse($ids, $course, $type)
    {
        if($type == 'lessons'){
            foreach ($ids ?? [] as $lesson) {
                if ($lesson['sort'] !== null) {
                    $course->trackLessons()->attach(
                        $lesson['id'], ['sort' => $lesson['sort']]
                    );
                }
            }
        }
        if ($type == 'tests'){
            foreach ($ids ?? [] as $test) {
                if ($test['sort'] !== null) {
                    $course->trackTests()->attach(
                        $test['id'], ['sort' => $test['sort']]
                    );
                }
            }
        }
        return true;
    }

    private function deAttachFromCourse($course, $type)
    {
        if($type == 'lessons')
            $course->trackLessons()->detach();

        if ($type == 'tests')
            $course->trackTests()->detach();

        return true;
    }

    // To ensure unique sort values for lessons and tests in course_track
    public function checkSortUnique($request)
    {
        $lessonSorts = collect($request->lesson_ids)->pluck('sort');
        $testSorts = collect($request->test_ids)->pluck('sort');
        $allSorts = $lessonSorts->merge($testSorts);
        if ($allSorts->count() !== $allSorts->unique()->count()) {
           return false;
        }
        return true;
    }


} //end of class
