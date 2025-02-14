<?php

namespace App\Actions\Queries;

class CoursesNot
{
    public function index()
    {
        $data['site_alias'] = \Route::input('site');
        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();
        //  return   language::on($data['site_alias']);
        // if (!Cache::has('languages-'.$data['site_alias']))
        // {
        //     Cache::forever('languages-'.$data['site_alias'] , language::on($data['site_alias'])->where('status',1)->pluck('name','alies')->toArray());
        // }
        $data['languages'] = Cache::get('languages-'.$data['site_alias']);

        // $data['languages'] = $data['site']->languages;
        $data['result'] = $data['site']->courses()->orderBy('id', 'ASC')->get();
        // $data['results']=course::get();
        // foreach ($data['results'] as  $value) {
        //     LessonOld::wherein('id',$value->post_ids)->update(['course_id'=>$value->id]);
        // }
      // return  "ddd";
        return view ('back.content.courses.index',$data);

    }

    public function to_assign_index()
    {
        $data['site_alias'] = \Route::input('site');
        $data['result'] = course::orderBy('id', 'ASC')->with('sites')->get();
        $data['sites'] = site::get();
        return view ('back.content.courses.to_assign_index',$data);
    }

    public function to_assign_index_post(Request $request)
    {

        $course = course::where('id',$request->course_id)->firstOrFail();
        $deattachedIds = site::whereNotin('id',$request->assign_site)->pluck('id');
        $course->sites()->syncWithoutDetaching($request->assign_site);

        return redirect()->back()->with('success', 'Course update Successfully!');

    }

    public function create()
    {
        // return "dff";
        $data['site_alias'] = \Route::input('site');
        $lang = json_decode(Helpers::defult_language());
        $lang_id = $lang->id;
        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();

        if (!Cache::has('lessons'))
        {
            Cache::forever('lessons' , Lesson::select('title_general','id')->get());
        }
        $data['lessons'] = Cache::get('lessons');

        if (!Cache::has('languages'))
        {
            Cache::forever('languages' , language::where('status',1)->pluck('name','alies')->toArray());
        }
        $data['languages'] = Cache::get('languages');
        return view ('back.content.courses.create',$data);
    }

    public function store(Request $request)
    {
        $site_alias = \Route::input('site');


        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'alias' => 'required|max:255',
            'duration' => 'required',
            'logo' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
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

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        // return $request->all();
        $logo = '';
        if (!empty($request->logo))
        {
            $logo = $request->logo->store('courses');
        }
        $site = site::where('alias',$site_alias)->firstOrFail();
        $active = $request->input('is_active');
        $exam_approved = $request->input('exam_approved');

        // $post_ids=[];
        // if($request->input('post_ids',[])){
        //   $post_ids=$request->input('post_ids');
        // }
        $data = [
          // 'site_id'=>$site->id,
          //   'siteid'=>$site->id,
            'title' => $request->input('title'),
            'name' => $request->input('title'),
            'subject' => $request->input('title'),
            'sort' => $request->input('sort'),

            'alias' =>str_ireplace(' ','-',$request->input('alias')),
            "content" => '',
            'date' => $request->input('date'),
            'exam_approved' => $exam_approved,
            'exam_at' =>$request->input('exam_at'),
            'date_at' => $request->input('date'),
            'duration' => $request->input('duration'),

            'format' => $request->input('format'),
            'orientation' => $request->input('orientation'),
            // 'limit' => $request->input('limit'),
            'logo' => $logo,
            'languages' => ["ar"],//$request->input('languages'),
            'category_id' => $request->input('category_id'),
            // 'post_ids' => $post_ids,
            'created_by' => Auth::guard('admin')->user()->id,
            'header' => $request->input('header'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'status' => $active,

        ];
         $stored = course::forceCreate( $data );
          if (!$stored) {
            return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
          }
          // save site relation
          $stored->sites()->attach($site->id);
         // save html
          $stored->description = createHtml(
            course::FOLDER_HTML ,
            $request->html,
            [ 'recordId' => $stored->id ]
          );
          $stored->save();

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
               // dd($header);
               // dd($meta_description);
               // dd($meta_description);
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


             $stored->link = $this->generateLink($stored);
             $stored->save();


        return redirect()->route('dashboard.courses.create',$site_alias)->with('success', 'Course Added Successfully!');
    }

    public function updatelink (Request $request)
    {
      $site = site::where('alias',$request->site)->firstOrFail();
      $save = $site->courses()->findOrFail($request->course);

      if (!$save->link){
        $save->link = $this->generateLink($save);
      }
      $save->link_ended = $request->link_ended ?? null;
      $save->link;
      $save->save();
      return redirect()->back()->with('success', 'Successfully!');
    }
    private function generateLink($stored)
    {
        return Helpers::generateRandomeString(15) . $stored->id;
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request)
    {
        $data['site_alias'] = \Route::input('site');
        $data['id'] = \Route::input('course');


        $data['site'] = site::where('alias',$data['site_alias'])->firstOrFail();

        if (!Cache::has('lessons'))
            {
                Cache::forever('lessons' , Lesson::select('title_general','id')->get());
            }
        $data['lessons'] = Cache::get('lessons');


        $data['fields'] = $data['site']->courses()->findOrFail($data['id']);
        return view ('back.content.courses.edit',$data);
    }

    public function update(Request $request)
    {
        $site_alias = \Route::input('site');
        $id = \Route::input('course');
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'logo' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'date' => 'nullable|date',
            'alias' =>'required|max:255',
            'duration' =>'required',
              'sort' =>  'nullable|integer',
            // 'limit' => 'min:1',
            'format' => 'required|string', // digits:2|
            'orientation' => 'required|string',
            'header' => 'required|string',
            'meta_description' => 'required|string',
            'meta_keywords' => 'required|string',
            'exam_approved' => 'nullable',
            'exam_at' => 'required_if:exam_approved,==,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
          $exam_approved = $request->input('exam_approved');
        // $post_ids=[];
        // if($request->input('post_ids')){
        //   $post_ids=$request->input('post_ids');
        // }
        $active = $request->input('is_active');
        $site = site::where('alias',$site_alias)->firstOrFail();
        $save = $site->courses()->findOrFail($id);
        $save->name = $request->input('title');
        $save->date_at = $request->input('date');
        $save->duration = $request->input('duration');
        $save->format = $request->input('format');
        $save->sort = $request->input('sort');
        $save->exam_approved = $exam_approved;

        $save->exam_at = $request->input('exam_at');
        $save->orientation = $request->input('orientation');
        $save->alias = str_ireplace(' ','-',$request->input('alias'));
        $save->status = $active;
        // $save->post_ids = $post_ids;
        $save->header = $request->input('header');
        $save->meta_description = $request->input('meta_description');
        $save->meta_keywords = $request->input('meta_keywords');

        if (!empty($request->logo))
        {
            if (!empty($save->logo))Storage::delete(public_path('/'.$save->logo));



            $save->logo = $request->logo->store('courses');
        }

        $save->updated_by = Auth::guard('admin')->user()->id;
        $save->save();

         // save html
        File::delete('storage/app/public/' . $save->description);
         $save->description = createHtml(
            course::FOLDER_HTML ,
            $request->html,
            [ 'recordId' => $save->id ]
          );
        $save->save();
        // redirect
        return redirect()->route('dashboard.courses.index',$site_alias)->with('success', 'Course updated Successfully!');
    }

    public function status(Request $request)
    {
        $site_alias = \Route::input('site');
        $id = \Route::input('course');
        $status = $request->input('status');

        $site = site::where('alias',$site_alias)->firstOrFail();
        $save = $site->courses()->findOrFail($id);
        $save->status = intval($status);
        $save->updated_by = Auth::guard('admin')->user()->id;
        $save->save();

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
          if($certificateTemplates->certificate_template == null || $certificateTemplates->certificate_template == '' ){
            $certificateTemplates->certificate_template=$request->locale.'-//-'.$request->content;

          }else {
            $certificateTemplates->certificate_template= $certificateTemplates->certificate_template.'//-//'.$request->locale.'-//-'.$request->content;
          }

            // dd($certificateTemplates);
            $certificateTemplates->save();
          // $certificateTemplates = course_site::where( ['site_id' => $site->id, 'course_id' => $course_id ])->first();
          // $certificateTemplates->certificate_template= $certificateTemplates->certificate_template.'//-//'.$request->locale.'-//-'.$request->content;
          // $certificateTemplates->save();
        }else{
          /// update  template for this language
          $old_templet_for_lang[$request->locale]=$request->content;
          $certificate_templates_new='';
          //get old  certificate Templates
          $certificateTemplates = course_site::where( ['site_id' => $site->id, 'course_id' => $course_id ])->first();
          /// loop for  all templates  for Merge
          foreach ($old_templet_for_lang as $key => $value) {

              if( $certificate_templates_new != '' ){

                $certificate_templates_new=$certificate_templates_new.'//-//';
              }
              $certificate_templates_new=$certificate_templates_new.$key.'-//-'.$value;
          }

          $certificateTemplates->certificate_template=$certificate_templates_new;
          $certificateTemplates->save();
        }


        return redirect()->route('dashboard.courses.template.edit',['site' => $site_alias,'course' => $course_id,'tab' => 'lang-'.$request->input('locale')])->with('success', 'Course Template updated Successfully!');
    }
}
