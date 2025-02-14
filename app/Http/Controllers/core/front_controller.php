<?php
namespace App\Http\Controllers\core;
use Illuminate\Http\Request as Request;
use App;
use Route;
use App\language;
use App\libraries\_commonfn;
use App\Models\Page;
use Illuminate\Support\Facades\View;
use GeniusTS\HijriDate\Translations\Arabic;
use GeniusTS\HijriDate\Date;
use LaravelLocalization;
use Illuminate\Support\Facades\Cache;
use App\Teacher;
class front_controller extends Controller
{
    public function data($request,$page,$meta = [])
    {
        config(['app.meta' => set_meta($page,$meta)]);

        $data = [
            'body_id' => $page,
            'page_name' => config('app.meta.title'),
            'page_key' => $page,
            'search' => [
                'data' =>[],
                // 'fields' => $request->input(),
            ],
        ];

        return $data;
    }

    public static function seoInfo($type, $source, $model = null)
    {

       $siteName = __('core.app_name');

       if($type=="page_inf"){



            $language = app()->getlocale();


            $page=Cache::rememberForever('page_inf_'.$language.'_'.$source, function() use($source, $language) {
             return Page::where('inner_name', $source)->with(['translations' => function($q) use($language){
                return $q->where('is_active', 1)->where('language', $language);
             }])->first();
           });
           $title='';
           $keywords='';
           $description='';



           $image=asset('assets/img/logo2.png');
           if($page?->translations->isNotEMpty()){
             $title=$page->translations->first()->header;
             $keywords=$page->translations->first()->meta_keywords;
             $description=$page->translations->first()->meta_description;
             $image=asset('assets/img/logo2.png');
           }
       }elseif($type=="site"){
            if(! $model){
              $item = App\site::where(['status' => 1,'alias' => $source])->firstOrFail();
            } else {
              $item = $model;
            }
            $title=$item->header;
            $keywords=$item->meta_keywords;
            $description=$item->meta_description;
            $image=url($item->logo_path);
       }elseif($type=="course"){
            if(! $model){
              $item = App\course::where('id' , $source)->firstOrFail();
            } else {
              $item = $model;
            }
            $title=$item->header;
            $keywords=$item->meta_keywords;
            $description=$item->meta_description;
            $image=url($item->logo_path);
       }elseif($type=="post"){
            $item = App\LessonOld::where('id' , $source)->firstOrFail();
            $title=$item->header;
            $keywords=$item->meta_keywords;
            $description=$item->meta_description;
            $image=url($item->logo_path);
       }elseif($type=="tracher"){
            if(! $model){
              $item = App\Teacher::where('id' , $source)->firstOrFail();
            } else {
              $item = $model;
            }
            $title=$item->header;
            $keywords=$item->meta_keywords;
            $description=$item->meta_description;
            $image=url($item->logo_path);
       }elseif($type=="diplom_succeded"){
             $item = App\Translations\SiteTranslation::where('site_id' , $source->id)->firstOrFail();
             $title=$item->header_success;
             $keywords=$item->meta_keywords_success;
             $description=$item->meta_description_success;
             $image='';
       }elseif($type=="prize_page"){
             $title='Mashindano ya kutoka African Islamic Academy ni mashindano ya ELIMIKA NA ZAWADIKA.';
             $keywords='Mashindano ya kutoka African Islamic Academy ni mashindano ya ELIMIKA NA ZAWADIKA.';
             $description='Mashindano ya kutoka African Islamic Academy ni mashindano ya ELIMIKA NA ZAWADIKA.';
             $image='';
       }elseif($type=="regitser"){
             $title='Register';
             $keywords='register- Hii ni Akadimi ya mafunzo na malezi ya Kiafrika ambayo inajishughulisha kusahilisha mafunzo, kusambaza elimu, kuleta karibu masomo ya sharia, kupanua ufahamu na kueleza utamaduni wa kisalamu na lugha ya kiarabu kupitia njia nyepesi na yenye kuvutia.';
             $description='register- Hii ni Akadimi ya mafunzo na malezi ya Kiafrika ambayo inajishughulisha kusahilisha mafunzo, kusambaza elimu, kuleta karibu masomo ya sharia, kupanua ufahamu na kueleza utamaduni wa kisalamu na lugha ya kiarabu kupitia njia nyepesi na yenye kuvutia.';
             $image='';

       }else{
           $title=__('core.home_header') ;
           $keywords=__('core.home_keywords');
           $description=__('core.home_description');
           $image=asset('assets/img/logo2.png');
       }


      $seoInfo = '<title>'. $title. '</title>'.
      '<meta name="keywords" content="'.$keywords.'">'.
      '<meta name="description" content="'.$description.'">'.
      '<meta property="og:title" content="'.$title.'"/>'.
      '<meta property="og:type" content="product"/>'.
      '<meta property="og:image" content="'.$image.'?v=123'.'"/>'.
      '<meta property="og:site_name" content="'.$siteName.'"/>'.
      '<meta property="og:description" content="'. $description .'"/>'.

      '<meta name="twitter:card" content="summary_large_image"/>'.
      '<meta name="twitter:site" content="'.$siteName.'"/>'.
      '<meta name="twitter:title" content="'.$title.'"/>'.
      '<meta name="twitter:description" content="'.$description.'"/>'.
      '<meta name="twitter:image" content="'.$image.'"/>';
      // dd($seoInfo);

      View::share('seo_info', $seoInfo);

    }
}
