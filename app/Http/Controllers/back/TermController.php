<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Http\Requests\siteAdminRequest;
use App\Http\Requests\TermAdminRequest;
//use App\Models\Term;
use App\Services\LanguageService;
use App\Services\SiteService;
use App\site;
use App\Term;
use App\Traits\FileUpload;
use App\Translations\SiteTranslation;
use App\Translations\TermTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TermController extends Controller
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
        $terms = \App\Term::all();

//        foreach ($terms as $item){
//            $termTranslation = TermTranslation::query()->where('locale',app()->getLocale())->where('term_id',$item->id)->first();
//            echo isset($termTranslation) ? $termTranslation->name : $item->title;
//        }
//        return 'f';

        return view('back.content.terms.index',compact('terms'));
    }


    public function create(Request $request)
    {
        $sites = DB::table('sites')->select('id','title')->get();
        return view('back.content.terms.create', compact('sites'));
    }

    public function store(TermAdminRequest $request)
    {
        $term = Term::forceCreate([
            'title' => $request->validated()['title'],
            'status' => $request->validated()['is_active'],
            'sort' => $request->validated()['sort'],
            'site_id' => 1,
        ]);

        if (! $term) {
            return back()->withinput()->withErrors(['general' => __('messages.added_faild')]);
        }



        $termTranslation = TermTranslation::forceCreate([
            'term_id' => $term->id,
            'locale' => $request->language,
            'name' => $request->validated()['title'],
            'trans_status' => $request->validated()['is_active'],
        ]);



        session()->flash('success', __('messages.added'));
        return redirect(route('dashboard.terms.index'));


    }

    public function edit($id,Request $request)
    {

        $term = Term::where('id', $id)->firstorfail();
        $language = $this->getLanguage();
        if(! $this->languageService->languageStatus('alies', $language)) {
            return redirect()->route('dashboard.terms.index', $request->id)->with('fail', 'Lnaguage not found');
        }


        $termTranslation = TermTranslation::query()->where('locale',$language)->where('term_id',$id)->first();

        $sitesTreeExcept= $this->siteService->getSitesTreeRoot(Term::get(), 0, $term->id);


        return view('back.content.terms.edit', compact(['term','termTranslation','sitesTreeExcept']));

    }

    public function update($id,TermAdminRequest $request)
    {

        $term = Term::findorfail($id);


//        $term->title = $request->validated()['title'];
        $term->status = $request->validated()['is_active'];
        $term->sort = $request->validated()['sort'];
        $term->save();


        $termTranslation = TermTranslation::updateOrCreate([
            'locale' => $request->language,
            'term_id' => $term->id,
        ],[
            'term_id' => $term->id,
            'locale' => $request->language,
            'name' => $request->validated()['title'],
            'trans_status' => $request->validated()['is_active'],
        ]);



        session()->flash('success', __('messages.updated'));
        return redirect(route('dashboard.terms.index'));

    }


    public function setActive($id,Request $request)
    {

        $term = Term::findorfail($id);
        $status = !$term->status;

        $term->status = $status;
        $term->update();

        if ($request->ajax()) {
            return response()->json(['status'=>'success', 'msg'=>__('messages.updated'), 'alert'=>'swal' ]);
        }

        session()->flash('success', __('messages.updated'));
        return redirect( route('dashboard.terms.index') );

    }

    public function destroy(Request $request)
    {

        $ids = [] ;
        if ($request->ids) {
            $ids = explode(",", $request->ids);
        }

        $delete = Term::whereIn('id',$ids)->delete();

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


} //end of class
