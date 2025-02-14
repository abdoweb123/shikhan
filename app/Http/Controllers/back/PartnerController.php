<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

use App\Partner;
use App\Translations\PartnerTranslation;
use App\Services\PartnerService;
use App\Services\LanguageService;
// use App\Services\GlobalService;
use App\Services\CountryService;
use App\helpers\UtilHelper;
use App\Traits\FileUpload;
use File;
use Auth;
use Validator;

class PartnerController extends Controller
{
    use FileUpload;
    private $languageService;
    private $partnerService;
    private $countryService;

    public function __construct(PartnerService $partnerService, LanguageService $languageService, CountryService $countryService)
    {
        $this->languageService = $languageService;
        $this->partnerService = $partnerService;
        $this->countryService = $countryService;
    }

    public function index(Request $request)
    {
        $data = Partner::get();
        return view('back.content.partners.index',compact(['data']));
    }

    public function create()
    {
        return view('back.content.partners.create');
    }

    public function store(Request $request)
    {

        $request->merge(['alias' => validateAlias(convertToLower(formatNormal($request->alias))) ]);

        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'name' => 'required|max:255',
            'alias' => 'required|max:255',
            'image' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'header' => 'required|string',
            'meta_description' => 'required|string',
            'meta_keywords' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        if(! $this->languageService->languageStatus('alies', $request->language)) {
            throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
        }


        if ($this->partnerService->aliasAndLanguageExists($request->alias, $request->language) ){
            throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
        }



        $stored = Partner::forceCreate([
          'name' =>  $request->name,
          'status' => $request->is_active,
        ]);

        if (!$stored) {
          return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
        }


        $storedTranslation = PartnerTranslation::forceCreate([
          'partner_id' => $stored->id,
          'locale' => $request->language,
          'title' => $request->name,
          'alias' =>  $request->alias,
          'header' => $request->header,
          'meta_keywords' => $request->meta_keywords,
          'meta_description' => $request->meta_description,
        ]);


        if (!$storedTranslation) {
          return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
        }


        // upload image
        if( $request->hasFile('image') ) {
            $path = $this->storeFile($request , [
                'fileUpload' => 'image', 'folder' => Partner::FOLDER_IMAGE, 'recordId' => $stored->id,
            ]);
            if (! $path) {
              return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
            }

            $stored->logo = $path;
            $stored->save();
        }







        // save html
        $storedTranslation->description = createHtml(
            Partner::FOLDER_HTML ,
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






        return redirect(route('dashboard.partners.index'));


    }

    public function edit(Request $request)
    {

      $data = Partner::where('id',$request->id)->firstorfail();

      $language = $this->getLanguage();
      if(! $this->languageService->languageStatus('alies', $language)) {
          return redirect()->route('dashboard.partners.index')->with('fail', 'Lnaguage not found');
      }

      $translation = $data->translate($language);

      return view('back.content.partners.edit', compact(['data','translation']));

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
         ]);

         if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
         }



         if(! $this->languageService->languageStatus('alies', $request->language)){
              throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
         }

         if ($this->partnerService->aliasAndLanguageExists($request->alias, $request->language, $id) ){
              throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
         }




          $edit = Partner::findorfail($request->id);

          // $edit->name = $request->name;
          $edit->status = $request->is_active;
          $edit->save();





          $translation = $edit->translations()->updateOrCreate([
             'locale' => $request->language,
          ],[
            'title' => $request->name,
            'alias' =>  $request->alias,
            'header' => $request->header,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
          ]);




          // save html
          File::delete('storage/app/public/' . $translation->description);
          $translation->description = createHtml(
              Partner::FOLDER_HTML ,
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
                  'fileUpload' => 'image', 'folder' => Partner::FOLDER_IMAGE, 'recordId' => $edit->id,
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
            Partner::FOLDER_HTML ,
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

       $delete = Partner::whereIn('id',$ids)->delete();
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



    // public function generatePartnerCode()
    // {
    //     for($i = 1; $i<=100; $i++){
    //       $code = '';
    //       do {
    //           $code = \Illuminate\Support\Str::random(10);
    //           $codeExists = DB::Table('partner_codes')->where('code', $code)->exists();
    //       } while ( $codeExists == true );
    //
    //       DB::Table('partner_codes')->insert([
    //           'partner_id' => 2,
    //           'code' => $code
    //       ]);
    //     }
    // }


}
