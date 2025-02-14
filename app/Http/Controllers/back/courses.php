<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\site;
use App\course;
use App\Translations\CourseTranslation;
use App\course_site;

use App\LessonOld;

// use App\language;
use App\Services\CourseService;
use App\Services\LanguageService;


use Illuminate\Support\Facades\Input;
use App\libraries\Helpers;
use Illuminate\Support\Facades\Cache;
use Validator;
use File;
use Storage;
use App\Traits\FileUpload;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class courses extends Controller
{
    use FileUpload;
    private $languageService;
    private $courseService;

    public function __construct(CourseService $courseService, LanguageService $languageService)
    {
        $this->languageService = $languageService;
        $this->courseService = $courseService;
    }

    public function index()
    {
        // $data['site_alias'] = \Route::input('site');
        $data['site_id'] = \Route::input('site');
        $data['site'] = site::where('id', $data['site_id'])->whereTranslation('locale', app()->getlocale())->select('id','title')->firstOrFail();
        $data['result'] = $data['site']->courses()->select('courses.id','courses.title','courses.status','link_ended','link')->orderBy('id', 'ASC')->get();

        return view ('back.content.courses.index',$data);
    }

    public function create()
    {

        $data['site_id'] = \Route::input('site');
        $lang = json_decode(Helpers::defult_language());
        $lang_id = $lang->id;
        $data['site'] = site::where('id', $data['site_id'])->firstOrFail();

        // if (!Cache::has('lessons')){
        //     Cache::forever('lessons' , LessonOld::select('title_general','id')->get());
        // }
        // $data['lessons'] = Cache::get('lessons');

        // if (!Cache::has('languages')){
        //     Cache::forever('languages' , language::where('status',1)->pluck('name','alies')->toArray());
        // }
        // $data['languages'] = Cache::get('languages');

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
            'duration' => 'required',
            'image' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'video_duration' => 'nullable|date_format:H:i:s',
            'date' => 'nullable|date',
            'limit' => 'min:1',
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
                // 'created_by' => Auth::guard('admin')->user()->id,
                // 'subject' => $request->title,
                // 'alias' =>str_ireplace(' ','-',$request->input('alias')),
                // "content" => '',
                // 'date_at' => $request->input('date'),
                // 'video_duration' => $request->input('video_duration'),
                // 'duration' => $request->input('duration'),
                // 'logo' => $logo,
                // 'languages' => ["ar"],//$request->input('languages'),
                // 'category_id' => $request->input('category_id'),
                // 'header' => $request->input('header'),
                // 'meta_description' => $request->input('meta_description'),
                // 'meta_keywords' => $request->input('meta_keywords'),
            ]);

            if (!$stored) {
                return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
            }


            // attach course to site
            $stored->sites()->attach($site->id, [
              'certificate_template_name' => 'certificate_template_with_sig_new',
              'short_link' => $this->createShortCode()
            ]);


            $storedTranslation = CourseTranslation::forceCreate([
              'course_id' => $stored->id,
              'locale' => $request->language,
              'name' => $request->title,
              'alias' => $request->alias,
              // 'subject' => $request->subject,
              // 'description' => $request->validated()['description'],
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

            if($stored->header == null or $stored->meta_description == null  or $stored->meta_keywords == null){
                $description= null;
                if(file_exists('storage/app/public/'.$stored->description) == true){
                    $description= strip_tags(file_get_contents('storage/app/public/'.$stored->description) );
                    $description=str_replace('&nbsp;','',$description);
                    $description=str_replace('▶','',$description);
                }
                $techer = ' ';

                $header="دورات مجانية في".' '.str_replace('دورة','',$stored->name).' - '.$techer.' من '.$site ->title .' - أكاديمية البلدة الطيبة';
                $meta_description=$stored->name.' من  '.$site ->title .' '.str_replace('الشيخ','للشيخ',$techer).' '.' أكاديمية البلدة الطيبة دورات مجانية اون لاين بشهادات معتمدة من الأكاديمية ووزارة الأوقاف اليمنية '.$description  ;
                $meta_keywords ='دورات مجانية' .', '.$site->title.' , '.' , '.$stored->name.' , '.' , '.$techer.' , '.str_replace('-',' , ',$description);

                if($stored->header == null ){
                   $stored->header = $header;
                }
                if($stored->meta_description == null ){
                  $stored->meta_description = $meta_description;
                }
                if($stored->meta_keywords == null ){
                   $stored->meta_keywords = $meta_keywords;
                }
                $stored->save();
            }

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

        $data['fields'] = $data['site']->courses()->findOrFail($data['id']);
        $data['translation'] = $data['fields']->translate($language);

        return view ('back.content.courses.edit', $data);

    }

    public function update(Request $request)
    {

        $site_id = \Route::input('site');
        $id = \Route::input('course');


        $request->merge(['alias' => validateAlias(convertToLower(formatNormal($request->alias))) ]);

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'title' => 'required|max:255',
            'logo' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'date' => 'nullable|date',
            'alias' =>'required|max:255',
            'duration' =>'required',
            'video_duration' => 'nullable|date_format:H:i:s',
            'sort' =>  'nullable|integer',
            // 'limit' => 'min:1',
            'format' => 'required|string', // digits:2|
            'orientation' => 'required|string',
            'header' => 'required|string',
            'meta_description' => 'required|string',
            'meta_keywords' => 'required|string',
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

            $edit = $site->courses()->findOrFail($id);
            $edit->title = $request->title;
            $edit->date = $request->date;
            $edit->format = $request->format;
            $edit->orientation = $request->orientation;
            $edit->exam_at = $request->exam_at;
            $edit->exam_approved = $request->exam_approved;
            $edit->max_trys = $request->max_trys;
            $edit->sort = $request->sort;
            $edit->status = $request->is_active;
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

        return redirect()->route('dashboard.courses.index',$site_alias)->with('success', $status ? 'Course Enabled Successfully!' : 'Course Disabled Successfully!');
    }

    public function destroy(Request $request)
    {
        $site_alias = \Route::input('site');
        $id = \Route::input('course');

        $site = site::where('alias',$site_alias)->firstOrFail();
        $row = $site->courses()->findOrFail($id);
        $lessons=$row->lessons()->get();
        if($lessons){
          foreach ($lessons as $key => $lesson) {
            // return$lesson;
              if (!empty($lesson->logo))Storage::delete(public_path('/'.$lesson->logo));

              if (!empty($lesson->description))File::delete('storage/app/public/' . $lesson->description);
              $lesson->delete();
              DB::table('lesson_translations')->where('lesson_id',$lesson->id)->delete();
          }
        }
        // return 00;
          if (!empty($row->logo))Storage::delete(public_path('/'.$row->logo));

          if (!empty($row->description))File::delete('storage/app/public/' . $row->description);
        $row->forceDelete();
        DB::table('courses_translations')->where('course_id',$row->id)->delete();
        return redirect()->route('dashboard.courses.index',$site_alias)->with('success', 'Course deleted Successfully!');
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

        $site = site::where('id', $request->site)->select('id')->firstOrFail();
        $course = $site->courses()->select('id','link')->findOrFail($request->course);

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
        // $data['site_alias'] = \Route::input('site');
        $data['result'] = course::orderBy('id', 'ASC')->with('sites')->get();
        $data['sites'] = site::get();
        return view ('back.content.courses.to_assign_index', $data);
    }

    public function to_assign_index_post(Request $request)
    {

        $course = course::where('id',$request->course_id)->firstOrFail();
        $deattachedIds = site::whereNotin('id',$request->assign_site)->pluck('id');
        $course->sites()->syncWithoutDetaching($request->assign_site);

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

}
