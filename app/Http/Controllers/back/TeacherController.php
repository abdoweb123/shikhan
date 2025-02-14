<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

use App\Teacher;
use App\Translations\TeacherTranslation;
use App\Services\TeacherService;
use App\Services\LanguageService;
// use App\Services\GlobalService;
use App\Services\CountryService;
use App\helpers\UtilHelper;
use App\Traits\FileUpload;
use File;
use Auth;
use Validator;

class TeacherController extends Controller
{
    use FileUpload;
    private $languageService;
    private $teacherService;
    private $countryService;

    public function __construct(TeacherService $teacherService, LanguageService $languageService, CountryService $countryService)
    {
        $this->languageService = $languageService;
        $this->teacherService = $teacherService;
        $this->countryService = $countryService;
    }

    public function index(Request $request)
    {
        $data = Teacher::get();
        return view('back.content.teachers.index',compact(['data']));
    }

    public function create()
    {
        $countries = $this->getAllCountries();

        return view('back.content.teachers.create', compact(['countries']));
    }

    public function store(Request $request)
    {

        $request->merge(['alias' => validateAlias(convertToLower(formatNormal($request->alias))) ]);

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'name' => 'required|max:255',
            'email' => 'email|required|unique:teachers',
            'password' => 'required',
            'alias' => 'required|max:255',
            'image' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'header' => 'required|string',
            'meta_description' => 'required|string',
            'meta_keywords' => 'required|string',
            'birthdate' => 'nullable|date',
            'country' => 'required|integer|exists:countries,id',
            'qualification' => 'nullable|string|max:1000',
            'specialization' => 'nullable|string|max:1000',
            'position' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        if(! $this->languageService->languageStatus('alies', $request->language)) {
            throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
        }


        if ($this->teacherService->aliasAndLanguageExists($request->alias, $request->language) ){
            throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
        }



        $stored = Teacher::forceCreate([
          'name' =>  $request->name,
          'email' =>  $request->email,
          'password' =>  Hash::make($request->password),
          'birthdate' =>  $request->birthdate,
          'country_id' => $request->country,
          'is_active' => $request->is_active,
        ]);

        if (!$stored) {
          return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
        }


        $storedTranslation = TeacherTranslation::forceCreate([
          'teacher_id' => $stored->id,
          'locale' => $request->language,
          'title' => $request->name,
          'alias' =>  $request->alias,
          'header' => $request->header,
          'meta_keywords' => $request->meta_keywords,
          'meta_description' => $request->meta_description,
          'qualification' => $request->qualification,
          'specialization' => $request->specialization,
          'position' => $request->position,
        ]);


        if (!$storedTranslation) {
          return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
        }


        // upload image
        if( $request->hasFile('image') ) {
            $path = $this->storeFile($request , [
                'fileUpload' => 'image', 'folder' => Teacher::FOLDER_IMAGE, 'recordId' => $stored->id,
            ]);
            if (! $path) {
              return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
            }

            $stored->image = $path;
            $stored->save();
        }







        // save html
        $storedTranslation->description = createHtml(
            Teacher::FOLDER_HTML ,
            $request->html,
            ['recordId' => $stored->id]
        );
        $storedTranslation->save();
        // $stored->description = createHtml(
        //     IsTeacher::FOLDER_HTML ,
        //     $request->html,
        //     [ 'recordId' => $stored->id ]
        //   );
        //   $stored->save();






        return redirect(route('dashboard.teachers.index'));


    }

    public function edit(Request $request)
    {

      $data = Teacher::where('id',$request->id)->firstorfail();

      $language = $this->getLanguage();
      if(! $this->languageService->languageStatus('alies', $language)) {
          return redirect()->route('dashboard.teachers.index')->with('fail', 'Lnaguage not found');
      }

      $translation = $data->translate($language);
      $countries = $this->getAllCountries();

      return view('back.content.teachers.edit', compact(['data','translation','countries']));

    }

    public function update(Request $request)
    {

         $id = \Route::input('id');

         $request->merge(['alias' => validateAlias(convertToLower(formatNormal($request->alias))) ]);

         $validator = Validator::make($request->all(), [
              'language' => 'required',
              'name' => 'required|max:255',
              'alias' => 'required|max:255',
              'image' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
              'header' => 'required|string',
              'meta_description' => 'required|string',
              'meta_keywords' => 'required|string',
              'birthdate' => 'nullable|date',
              'country' => 'required|integer|exists:countries,id',
              'qualification' => 'nullable|string|max:1000',
              'specialization' => 'nullable|string|max:1000',
              'position' => 'nullable|string|max:1000',
         ]);

         if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
         }



         if(! $this->languageService->languageStatus('alies', $request->language)){
              throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
         }

         if ($this->teacherService->aliasAndLanguageExists($request->alias, $request->language, $id) ){
              throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
         }




          $edit = Teacher::findorfail($request->id);

          // $edit->name = $request->name;
          $edit->birthdate = $request->birthdate;
          $edit->country_id = $request->country;
          $edit->is_active = $request->is_active;
          $edit->save();





          $translation = $edit->translations()->updateOrCreate([
             'locale' => $request->language,
          ],[
            'title' => $request->name,
            'alias' =>  $request->alias,
            'header' => $request->header,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'qualification' => $request->qualification,
            'specialization' => $request->specialization,
            'position' => $request->position,
          ]);




          // save html
          File::delete('storage/app/public/' . $translation->description);
          $translation->description = createHtml(
              Teacher::FOLDER_HTML ,
              $request->html,
              ['recordId' => $translation->id ]
            );
          $translation->save();



          // remove image
          if ($request->has('image_remove')) {
              File::delete('storage/app/public/'.$edit->image);
              $edit->image = null;
              $edit->save();
          }



          // upload image
          if( $request->hasFile('image') ) {
              File::delete('storage/app/public/'.$edit->image);
              $path = $this->storeFile($request , [
                  'fileUpload' => 'image', 'folder' => Teacher::FOLDER_IMAGE, 'recordId' => $edit->id,
              ]);
              if (! $path) {
                return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
              }

              $edit->image = $path;
              $edit->save();
          }










        // save html
        File::delete('storage/app/public/' . $translation->html);
        $translation->description = createHtml(
            Teacher::FOLDER_HTML ,
            $request->html,
            ['recordId' => $translation->id]
        );
        $translation->save();



        // redirect
        return redirect()->route('dashboard.teachers.index')->with('success', 'Updated Successfully!');

    }

    public function destroy(Request $request)
    {

        $ids = [] ;
        if ($request->ids) {
            $ids = explode(",", $request->ids);
        }

       $delete = Teacher::whereIn('id',$ids)->delete();
        // if ( $delete !== true ) {
        //   if ( $request->expectsJson() ) {
        //     return response()->json(['error' => $delete ]);
        //   } else {
        //     return redirect()->back()->withErrors([ $delete ]);
        //   }
        // }

        if ( $request->expectsJson() ) {
          return response()->json(['success' =>  __('messages.deleted') ]);
        } else {
          $this->flashAlert([ 'success' => ['msg'=> __('messages.deleted') ] ]);
          return redirect()->back();
        }

    }




    private function getAllCountries()
    {
      return $this->countryService->filter();
    }

    private function getLanguage()
    {
      return request()->query('language') ?? null;
    }

}
