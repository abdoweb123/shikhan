<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\siteAdminRequest;
use App\Setting;
use App\helpers\UtilHelper;
use App\Traits\FileUpload;
use File;
use Auth;
use Validator;


class SettingsController extends Controller
{
    use FileUpload;

    public function index(Request $request)
    {

        $data = Setting::get();
        return view('back.content.settings.index',compact(['data']));
    }

    public function create()
    {
        return view('back.content.settings.create');
    }

    public function store(Request $request)
    {


      return $request->all();

      $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'image' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
            'header' => 'required|string',
            'meta_description' => 'required|string',
            'meta_keywords' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $validate = Teacher::where('name',$request->name)->get();

          if ( $validate->count() >= 1 ) {
            throw ValidationException::withMessages(['name' => __('messages.already_exists' , [ 'var' => __('words.name') ] )]);
          }
      $active = $request->input('is_active');
      // check if parent is inactive or parent levele is inactive


      $data = [
        'name' =>  $request->input('name'),
        'header' => $request->input('header'),
        'meta_description' => $request->input('meta_description'),
        'meta_keywords' => $request->input('meta_keywords'),
      ];


      $stored = Teacher::forceCreate( $data );
      if (!$stored) {
        return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
      }



      // upload image
      if( $request->hasFile('image') ) {
          $path = $this->storeFile($request , [
              'fileUpload' => 'image', 'folder' => Teacher::FOLDER_IMAGE, 'recordId' => $stored->id,
          ]);
          if (! $path) {
            $this->flashAlert([ 'faild' => ['msg'=> __('messages.error_upload_image')] ]);
            return redirect()->back();
          }
       $stored->image = $path;
        $stored->save();
      }



        $stored->description = createHtml(
            Teacher::FOLDER_HTML ,
            $request->html,
            [ 'recordId' => $stored->id ]
          );
          $stored->save();


      return redirect(route('dashboard.teachers.index'));



    }

    public function edit(Request $request)
    {
      $data = Setting::where('id',$request->id)->firstorfail();

      return view('back.content.settings.edit', compact(  'data'));

    }

    public function update(Request $request)
    {

        return $request->all();

         $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'image' => 'image|mimes:jpg,jpeg,bmp,png|max:2000',
                'header' => 'required|string',
                'meta_description' => 'required|string',
                'meta_keywords' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $edit = Teacher::findorfail($request->id);
            $validate = Teacher::where('id','!=',$edit->id)->where('name',$request->name)->get();

            if ( $validate->count() >= 1 ) {
                throw ValidationException::withMessages(['name' => __('messages.already_exists' , [ 'var' => __('words.name') ] )]);
              }



        // check if parent is inactive or parent levele is inactive




        // check if parent is free


        $data = [
          'name' =>  $request->input('name'),
          'header' => $request->input('header'),
          'meta_description' => $request->input('meta_description'),
          'meta_keywords' => $request->input('meta_keywords'),
        ];
        $edit->update($data);


        // upload image
        if ($request->image_remove) {
          File::delete('storage/app/public/' . $edit->image);
          $edit->image = null;
          $edit->save();
        }
        if( $request->hasFile('image') ) {
            $path = $this->storeFile($request , [
                'fileUpload' => 'image', 'folder' => site::FOLDER_IMAGE, 'recordId' => $edit->id,
            ]);
            if (! $path) {
              $this->flashAlert([ 'faild' => ['msg'=> __('messages.error_upload_image')] ]);
              return redirect()->back();
            }

             $edit->image = $path;
           $edit->save();
        }

        // save html
        File::delete('storage/app/public/' . $edit->html);
         $edit->description = createHtml(
            Teacher::FOLDER_HTML ,
            $request->html,
            [ 'recordId' => $edit->id ]
          );
      $edit->save();
        return redirect(route('dashboard.teachers.index'));

    }



    public function destroy(Request $request)
    {

        $ids = [] ;
        if ($request->ids) {
            $ids = explode(",", $request->ids);
        }

       $delete = Setting::whereIn('id',$ids)->delete();
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


    public function getLookUp()
    {
        $siteTypes = $this->dataServ->getActivesiteTypes();
        $grammerContents = $this->dataServ->getActiveGrammerContentTreeRoot(0);
        $skills = $this->skillServ->getActiveSkills();
        $trainingTypes = $this->dataServ->getActiveTrainingTypes();
        return compact(['siteTypes','grammerContents','skills','trainingTypes']);
    }

}
