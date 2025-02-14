<?php

namespace App\Http\Controllers\back;
use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Requests\PageRequest;
use Illuminate\Validation\ValidationException;

use App\Services\PageService;
use App\Services\LanguageService;
use App\Models\Page;
use App\Models\PageInfo;
use App\helpers\UtilHelper;
use App\Traits\FileUpload;
use Auth;
use File;

class PageController extends Controller
{
    use FileUpload;

    public function __construct(private PageService $pageService, private LanguageService $languageService,Request $request)
    {
        //
    }

    public function index(Request $request)
    {
        $data = Page::Details()->get();
        return view('back.content.pages.index', compact(['data']));
    }

    public function create()
    {
         return view('back.content.pages.create');
    }

    public function store(PageRequest $request)
     {
    //   $this->pageService->validateDoublicateTitle( $request->validated()['title'] , $request->validated()['language'] );
    //   $this->pageService->validateDoublicateAlias( $request->validated()['alias'] , $request->validated()['language'] );


      if ($this->pageService->titleAndLanguageExists($request->validated()['title'], $request->validated()['language']) ){
           throw ValidationException::withMessages(['title' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'title' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
      }

      if ($this->pageService->aliasAndLanguageExists($request->validated()['alias'], $request->validated()['language']) ){
           throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
      }




       $active = isset($request->validated()['is_active']) ? $request->validated()['is_active'] : 1;
       // check if parent is inactive
       if ( $request->validated()['parent_id'] != 0){
         $parent = Page::where('id',$request->validated()['parent_id'])->select('is_active')->firstOrFail();
        if ($parent->is_active == 0) {
          $active = 0;
        }
      }


      $data = [
        'title_general' => $request->validated()['title'],
        'parent_id' => $request->validated()['parent_id'],
        'is_active' => $active,
        'ip' => UtilHelper::getUserIp() ,
        'access_user_id' => Auth::id()
      ];

      $stored = Page::forceCreate( $data );
      if (!$stored) {
        return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
      }


      $dataInfo = [
        'page_id' => $stored->id,
        'language' => $request->validated()['language'],
        'title' => $request->validated()['title'],
        'alias' => $request->validated()['alias'],
        'description' => $request->validated()['description'],
        'meta_description' => $request->validated()['meta_description'],
        'meta_keywords' => $request->validated()['meta_keywords'],
        'video' => $request->validated()['video'] ?? '',
        'template_id' => 1 ,
        'template' => 'template_01' ,
        'ip' => UtilHelper::getUserIp() ,
        'access_user_id' => Auth::id()
      ];

      $storedInfo = PageInfo::forceCreate( $dataInfo );
      if (!$storedInfo) {
        return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
      }


      // upload image
      if( $request->hasFile('image') ) {
          $path = $this->storeFile($request , [
              'fileUpload' => 'image', 'folder' => Page::FOLDER, 'recordId' => $storedInfo->id,
          ]);
          if (! $path) {
            $this->flashAlert([ 'faild' => ['msg'=> __('messages.error_upload_image')] ]);
            return redirect()->back();
          }
          $storedInfo->Update(['image' => $path]);
      }

//         return$storedInfo->id;

         // save html
         $storedInfo->description = UtilHelper::createHtml(
             $storedInfo->description,
              Page::FOLDER_HTML ,
              $request->validated()['description'],
              ['recordId' => $storedInfo->id],
              'create'
          );
         $storedInfo->save();

      $this->pageService->clearCacheLanguages();

      return redirect(route('dashboard.pages.index'));

    }

    public function edit(Request $request)
    {
        $data = Page::Details()->where('id',$request->id)->firstorfail();

        $language = $this->getLanguage();
        if(! $this->languageService->languageStatus('alies', $language)) {
            return redirect()->route('dashboard.pages.index')->with('fail', 'Lnaguage not found');
        }

        $translation = $data->translation($language)->first();

        return view('back.content.pages.edit', compact(['data','translation']));
    }

    public function update(PageRequest $request)
    {

      $edit = Page::findorfail($request->id);


      if(! $this->languageService->languageStatus('alies', $request->validated()['language'])) {
          throw ValidationException::withMessages(['language' => __('messages.language_in_active') ]);
      }

      if ( $this->pageService->languageExists( $request->validated()['language'] , $edit->id ) ) {
        throw ValidationException::withMessages(['alias' => __('messages.already_exists' , [ 'var' => __('words.language') ] ) ]);
      }


      if ($this->pageService->titleAndLanguageExists($request->validated()['title'], $request->validated()['language'], $edit->id) ){
           throw ValidationException::withMessages(['title' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'title' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
      }

      if ($this->pageService->aliasAndLanguageExists($request->validated()['alias'], $request->validated()['language'], $edit->id) ){
           throw ValidationException::withMessages(['alias' => __('messages.already_exists', [ 'var' => __('words.title') ] ), 'alias' => __('messages.already_exists' , [ 'var' => __('words.alias') ] )]);
      }


//      return $request;

      $edit->update([
        'parent_id' => $request->validated()['parent_id'],
        'ip' => UtilHelper::getUserIp() ,
        'access_user_id' => Auth::id()
      ]);



//        return $request->validated()['is_active'];

      $translation = $edit->translations()->updateOrCreate([
         'language' => $request->validated()['language'],
      ],[
        'title' => $request->validated()['title'],
        'alias' => $request->validated()['alias'],
        'header' => $request->validated()['header'],
        'meta_keywords' => $request->validated()['meta_keywords'],
        'meta_description' => $request->validated()['meta_description'],
        'template_id' => 1 ,
        'ip' => UtilHelper::getUserIp() ,
        'access_user_id' => Auth::id(),
        'is_active' => $request->validated()['is_active'],
      ]);



      // save html
      // $translation->description = createHtml(
      //     Page::FOLDER_HTML ,
      //     $request->validated()['description'],
      //     ['recordId' => $translation->id]
      // );
      // $translation->save();
      $translation->description = UtilHelper::createHtml(
        $translation->description,
        Page::FOLDER_HTML ,
        $request->validated()['description'],
        [ 'recordId' => $translation->id ],
          'update'
      );
      $translation->save();




      // upload image
      if( $request->hasFile('image') ) {
          File::delete('storage/app/public/'.$translation->image);
          $path = $this->storeFile($request , [
              'fileUpload' => 'image', 'folder' => Page::FOLDER, 'recordId' => $translation->id,
          ]);
          if (! $path) {
            return back()->withinput()->withErrors(['fail' => __('messages.error_upload_image') ]);
          }

          $translation->image = $path;
          $translation->save();
      }



      $this->pageService->clearCacheLanguages();

      return redirect(route('dashboard.pages.index'));

    }

    public function storeTrans(PageRequest $request)
    {

      $edit = Page::findorfail($request->id);

      $this->pageService->validateDoublicateLanguage( $request->validated()['language'] , $edit->id );
      $this->pageService->validateDoublicateTitle( $request->validated()['title'] , $request->validated()['language'] );
      $this->pageService->validateDoublicateAlias( $request->validated()['alias'] , $request->validated()['language'] );


      $active = $request->validated()['is_active'];
      // check if parent is inactive
      if ( $request->validated()['parent_id'] != 0){
        $parent = Page::where('id',$request->validated()['parent_id'])->select('is_active')->firstOrFail();
        if ($parent->is_active == 0) {
          $active = 0;
        }
      }


      $data = [
        'parent_id' => $request->validated()['parent_id'],
        'is_active' => $active,
        'ip' => UtilHelper::getUserIp() ,
        'access_user_id' => Auth::id()
      ];

      $edit->update( $data );


      $dataInfo = [
        'page_id' => $edit->id,
        'language' => $request->validated()['language'],
        'title' => $request->validated()['title'],
        'alias' => $request->validated()['alias'],
        'description' => $request->validated()['description'],
        'meta_description' => $request->validated()['meta_description'],
        'meta_keywords' => $request->validated()['meta_keywords'],
        'template_id' => 1 ,
        'template' => 'template_01' ,
        'ip' => UtilHelper::getUserIp() ,
        'access_user_id' => Auth::id()
      ];

      $storedInfo = PageInfo::forceCreate( $dataInfo );
      if (!$storedInfo) {
        return back()->withinput()->withErrors(['general' => __('messages.added_faild') ]);
      }


      // upload image
      if( $request->hasFile('image') ) {
          $path = $this->storeFile($request , [
              'fileUpload' => 'image', 'folder' => Page::FOLDER, 'recordId' => $storedInfo->id,
          ]);
          if (! $path) {
            $this->flashAlert([ 'faild' => ['msg'=> __('messages.error_upload_image')] ]);
            return redirect()->back();
          }
          $storedInfo->Update(['image' => $path]);
      }

      return redirect(route('back.pages.index'));

    }

    public function setActive(Request $request)
    {

        $data = Page::findorfail($request->id);
        $status = !$data->is_active;

        // if we try to active a category so check the parent of it if the parent is inactive then make it active
        if ($status == 1) {
          $parent = Page::where(['id' => $data->parent_id , 'is_active' => 0 ])->first();
          if ($parent){
            $this->flashAlert([ 'faild' => ['msg'=> __('category.activate_parent') .' - '. $parent->title_general ] ]);
            return back();
          }
        }

        $data->update(['is_active' => $status]);
        PageInfo::where('page_id', $data->id)->update(['is_active' => $status]);
        $this->pageService->clearCacheLanguages();

        return redirect()->route('dashboard.pages.index')->with('success', 'Updated Successfully!');

    }

    public function destroy(Request $request)
    {
        //
        // $delete = $this->categoryServ->destroyAll($request->id);
        // if ( $delete !== true ) {
        //   if ( $request->expectsJson() ) {
        //     return response()->json(['error' => $delete ]);
        //   } else {
        //     return redirect()->back()->withErrors([ $delete ]);
        //   }
        // }
        //
        // $this->flashAlert([ 'success' => ['msg'=> __('messages.deleted') ] ]);
        // return response()->json(['success' =>  __('messages.deleted') ]);

    }



    private function getLanguage()
    {
      return request()->query('language') ?? null;
    }

}
