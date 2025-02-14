<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\siteAdminRequest;
use App\Social;
use App\helpers\UtilHelper;
use App\Traits\FileUpload;
use File;
use Auth;
use Validator;


class socialController extends Controller
{
    use FileUpload;

    public function index(Request $request)
    {
        $data = Social::get();
        return view('back.content.social.index',compact(['data']));
    }

    public function create()
    {
        return view('back.content.social.create', ['languages' => getActiveLanguages()]);
    }

    public function store(Request $request)
    {

      // return $request->all();

      $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'link' => 'url|string',
            'icon' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


      // check if parent is inactive or parent levele is inactive


      $data = [
        'titel_org' =>  $request->input('title'),
        'title' =>  $request->input('title'),
        'link' => $request->input('link'),
        'icon' => $request->input('icon'),
      ];


      $stored = Social::forceCreate( $data );
      if (!$stored) {
        return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
      }



      // // upload image
      // if( $request->hasFile('image') ) {
      //     $path = $this->storeFile($request , [
      //         'fileUpload' => 'image', 'folder' => IsTeacher::FOLDER_IMAGE, 'recordId' => $stored->id,
      //     ]);
      //     if (! $path) {
      //       $this->flashAlert([ 'faild' => ['msg'=> __('messages.error_upload_image')] ]);
      //       return redirect()->back();
      //     }
      //  $stored->image = $path;
      //   $stored->save();
      // }

      return redirect(route('dashboard.social.index'));



    }

    public function edit(Request $request)
    {
      $data = Social::where('id',$request->id)->firstorfail();

      return view('back.content.social.edit', compact(  'data'));

    }

    public function update(Request $request)
    {

        // return $request->all();
          $edit = Social::findorfail($request->id);
        $validator = Validator::make($request->all(), [
              'title' => 'required|max:255',
              'link' => 'url|string',
              'icon' => 'nullable|string',
          ]);

          if ($validator->fails()) {
              return redirect()->back()->withInput()->withErrors($validator);
          }


        // check if parent is inactive or parent levele is inactive
          $edit->title =  $request->input('title');
          $edit->link = $request->input('link');
          $edit->icon = $request->input('icon');
      $edit->save();
        return redirect(route('dashboard.social.index'));

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
