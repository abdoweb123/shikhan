<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App,Auth;
use Session;
use App\site;
use App\Teacher;
use Illuminate\Support\Facades\DB;
use App\Mail;
use App\Models\ContactUs;
use App\Models\Faq;
use App\Models\Page;
use App\Models\PageInfo;
use App\helpers\UtilHelper;

class InfoController extends Controller
{

    public function contactUs(Request $request)
    {

        $data = $this->data($request,'teachers');
        $this->seoInfo('page_inf','اتصل بنا');

        $data['msg_types']= DB::Table('msg_types')->get();
        $data['title_page'] = "contactUs";

        $data['data'] = Page::where('inner_name', 'contact_us')->where('is_active', 1)->firstorfail();
        $data['translation'] = $data['data']->translation(app()->getLocale())->where('is_active', 1)->firstorfail();

        return view('front.info.contact_us', $data);
    }

    public function contactUsPost(Request $request)
    {

        // $this->validate(request(),[
        //     'name'=>'required|max:100|string',
        //     'phone'=>'nullable|numeric|digits:10',
        //     'email'=>'required|max:100|email',
        //     'msg_type_id'=>'required|integer|exists:msg_types,id',
        //     'msg_body'=>'required|max:4000|string',
        //     'subject' => 'required|max:100|string',
        //   ],[],['subject'=> 'عنوان الرساله']);



          $validate = $request->validate([
              'title' => 'required|string|max:100',
              'mobile' => 'nullable|numeric',
              'email'=>'required|max:100|email',
              'contact_us_type_id' => 'required|exists:contact_us_types,id',
              'content' => 'required|string|max:2000',
              'user_id' => 'nullable|integer|exists:users,id',
                'g-recaptcha-response' => 'required',
          ]);


         ContactUs::create( array_merge(
            $request->all() , [ 'ip' => getUserIp() ] )
          );
        session()->flash('flashAlerts',[
            'success' => ['msg'=> __('words.success_send')]
          ]);

          return redirect(route('front.page.contact_us'));

    }

    public function ViewFaqs(Request $request)
    {

      $data = $this->data($request,'faqs');
      // $page = Self::getPageOfAlias("الاسئله-الشائعه" );
      $data['data'] = Page::where('inner_name', 'faqs')->where('is_active', 1)->firstorfail();
      $data['translation'] = $data['data']->translation(app()->getLocale())->where('is_active', 1)->firstorfail();

      return view('front.info.template_01', $data );

    }

    public function partners(Request $request)
    {

      $data = $this->data($request,'partners');

      $page = Page::with(['activeTranslation'])->where('inner_name' , 'partners')->firstorfail();

      $this->seoInfo('page_inf',$page->title_general);
      $data['info'] = $page;

      $data['partners'] = \App\Partner::get();

      return view('front.info.partners', $data );

    }

    public function show(Request $request)
    {

        // $data = $this->data($request,'teachers');
        // $page = Self::getPageOfAlias( $request->alias );
        //
        // $this->seoInfo('page_inf',$page->title_general);
        // $data['info'] = $this->getPageOfAlias($request->alias);

        // $data['data'] = Page::where('inner_name', 'privacy_policy')->where('is_active', 1)->firstorfail();
        $alias = $request->alias;
        $data['data'] = Page::wherehas('translation', function($q) use($alias){
          return $q->where('alias', $alias);
        })->where('is_active', 1)->firstorfail();

        $data['translation'] = $data['data']->translation(app()->getLocale())->where('is_active', 1)->firstorfail();

        return view('front.info.template_01', $data );

    }

    public function getPageOfAlias( $pageAlias )
    {
        $alias = UtilHelper::formatNormal($pageAlias);
        return Page::with(['activeTranslation'])->whereHas('activeTranslation' , function($q) use($alias) {
            return $q->where('alias',$alias);
        })->firstorfail();
    }


}
