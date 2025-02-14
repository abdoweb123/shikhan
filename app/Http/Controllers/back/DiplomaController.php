<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\site;
use App\Translations\SiteTranslation;
use App\Services\SiteService;
use App\Services\LanguageService;
use App\Http\Requests\siteAdminRequest;
use App\helpers\UtilHelper;
use App\Traits\FileUpload;
use File;
use Auth;
use DB;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;


class DiplomaController extends Controller
{
    use FileUpload;

    private $languageService;
    private $siteService;

    public function __construct(SiteService $siteService, LanguageService $languageService)
    {
        $this->languageService = $languageService;
        $this->siteService = $siteService;
    }

    public function index(Request $request)
    {
        return view('back.content.diplomas.index');
    }

    public function create(Request $request)
    {
        return view('back.content.diplomas.create');
    }

    public function store(siteAdminRequest $request)
    {


        if(! $this->languageService->languageStatus('alies', $request->language)) {
            throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
        }

        if ($this->siteService->aliasAndLanguageExists($request->validated()['alias'], $request->language) ){
            throw ValidationException::withMessages(['title' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
        }

        $stored = site::forceCreate([
          'parent_id' => $request->validated()['parent_id'],
          'title' => $request->validated()['title'],
          'status' => $request->validated()['is_active'],
          'sort' => $request->validated()['sort'],
          'created_by' => Auth::id(),
          'short_link' => $this->createShortCode(),
          'certificate_template' => $this->getCertificateTemplateDefaultValue()
        ]);

        if (! $stored) {
          return back()->withinput()->withErrors(['general' => __('messages.added_faild')]);
        }



        $storedTranslation = SiteTranslation::forceCreate([
          'site_id' => $stored->id,
          'locale' => $request->language,
          'name' => $request->validated()['title'],
          // 'alias' => $request->validated()['alias'],
          'slug' => $request->validated()['alias'],
          'brief' => $request->validated()['brief'],
          'description' => $request->validated()['description'],
          'header' => $request->validated()['header'],
          'meta_description' => $request->validated()['meta_description'],
          'meta_keywords' => $request->validated()['meta_keywords'],
          'trans_status' => $request->validated()['is_active'],
        ]);





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


        session()->flash('success', __('messages.added'));
        return redirect(route('dashboard.diplomas.index'));


    }

    public function edit(Request $request)
    {

      $data = site::where('id', $request->id)->firstorfail();

      $language = $this->getLanguage();
      if(! $this->languageService->languageStatus('alies', $language)) {
          return redirect()->route('dashboard.diplomas.index', $request->id)->with('fail', 'Lnaguage not found');
      }

      $translation = $data->translate($language);

      $sitesTreeExcept= $this->siteService->getSitesTreeRoot(site::get(), 0, $data->id);



      return view('back.content.diplomas.edit', compact(['data','translation','sitesTreeExcept']));

    }

    public function update(siteAdminRequest $request)
    {

         $edit = site::findorfail($request->id);


         if ($this->siteService->aliasAndLanguageExists($request->validated()['alias'], $request->language, $request->id) ){
             throw ValidationException::withMessages(['title' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
         }


         $edit->parent_id = $request->validated()['parent_id'];
         $edit->sort = $request->validated()['sort'];
         $edit->updated_by = Auth::id();
         $edit->save();


         $translation = $edit->translations()->updateOrCreate([
            'locale' => $request->language,
         ],[
           'name' => $request->validated()['title'],
           'alias' => $request->validated()['alias'],
           'slug' => $request->validated()['alias'],
           'brief' => $request->validated()['brief'],
           'header' => $request->validated()['header'],
           'meta_description' => $request->validated()['meta_description'],
           'meta_keywords' => $request->validated()['meta_keywords'],
           'description' => $request->validated()['description'],
           'trans_status' => $request->validated()['is_active'],
         ]);


        // upload image
        if ($request->has('image_remove')) {
          File::delete('storage/app/public/'.$translation->image_details);
          $translation->image_details = null;
          $translation->save();
        }

        if ($request->hasFile('image')) {
            $path = $this->storeFile($request , [
                'fileUpload' => 'image', 'folder' => site::FOLDER_IMAGE, 'recordId' => $translation->id,
            ]);
            if (! $path) {
              return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
            }

             $translation->image_details = $path;
             $translation->save();
        }

        session()->flash('success', __('messages.updated'));
        return redirect(route('dashboard.diplomas.index'));

    }

    public function setActive(Request $request)
    {

        $data = site::findorfail($request->id);
        $status = !$data->status;

        // if we try to active a site so check the parent of it if the parent is inactive then make it active
        // if ($status == 1) {
        //   $parent = site::where(['id' => $data->parent_id, 'status' => 0 ])->first();
        //   if ($parent){
        //       if ($request->ajax()) {
        //         return response()->json(['status'=>'error', 'msg'=>__('site.activate_parent') .' - '. $parent->title, 'alert'=>'swal' ]);
        //       }
        //       return back()->withinput()->withErrors(['fail' => __('site.activate_parent') .' - '. $parent->title ]);
        //   }
        // }

        $data->update(['status' => $status]);

        if ($request->ajax()) {
          return response()->json(['status'=>'success', 'msg'=>__('messages.updated'), 'alert'=>'swal' ]);
        }

        session()->flash('success', __('messages.updated'));
        return redirect( route('dashboard.diplomas.index') );

    }

    public function destroy(Request $request)
    {

        $ids = [] ;
        if ($request->ids) {
            $ids = explode(",", $request->ids);
        }

       $delete = site::whereIn('id',$ids)->delete();
        // if ( $delete !== true ) {
        //   if ( $request->expectsJson() ) {
        //     return response()->json(['error' => $delete ]);
        //   } else {
        //     return redirect()->back()->withErrors([ $delete ]);
        //   }
        // }

        if ( $request->expectsJson() ) {
          return response()->json(['success' =>  __('messages.deleted') ]);
        } else {;
          session()->flash('success', __('messages.deleted'));
          return redirect()->back();
        }

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
            $codeExists = site::where('short_link', $code)->exists();
        } while ( $codeExists == true );

        return $code;
    }

    private function getCertificateTemplateDefaultValue()
    {
        return json_encode([
                "site_certificate" => [
                  "jpg" => "site_certificate_template_with_sig_jpg",
                  "pdf" => "site_certificate_template_with_sig_pdf"
                ],
                "site_certificate_courses" => [
                    "jpg" => "certificate_template_site_courses_degree_jpg",
                    "pdf" => "certificate_template_site_courses_degree_pdf"
                ]
              ]);
    }



    // public function getLookUp()
    // {
    //     $siteTypes = $this->dataServ->getActivesiteTypes();
    //     $grammerContents = $this->dataServ->getActiveGrammerContentTreeRoot(0);
    //     $skills = $this->skillServ->getActiveSkills();
    //     $trainingTypes = $this->dataServ->getActiveTrainingTypes();
    //     return compact(['siteTypes','grammerContents','skills','trainingTypes']);
    // }


     //   public function filter_index(Request $request)
     //  {
     //    return $courses = course::with('subscribers')->paginate(5);
     //    return view('back.content.diplomas.filter',compact(['data']));
     //  }
     //  static function filter($request)
     // {
     //   $site = site::where('alias',)->firstOrFail();
     //   $course = $data['site']->courses()->findOrFail($data['course_id']);
     //   $result= $data['course']->subscribers()->orderBy('id', 'ASC')->paginate(500);
     //
     // }
}
