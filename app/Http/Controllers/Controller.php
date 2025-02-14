<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Page;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function share($data = [])
    {
        \Illuminate\Support\Facades\View::share($data[0], $data[1]) ;
    }

    public static function seoInfo($type,$source)
    {


       if($type=="page_inf"){
           $page=Page::where('title_general',$source)->firstorfail();
           $title=$page->activeTranslation();
           $keywords=' ';
           $description=' ';
           $image=' ';
       }elseif($type=="site"){
           $title=' ';
           $keywords=' ';
           $description=' ';
           $image=' ';
       }elseif($type=="course"){
           $title=' ';
           $keywords=' ';
           $description=' ';
           $image=' ';
       }elseif($type=="post"){
           $title=' ';
           $keywords=' ';
           $description=' ';
           $image=' ';
       }elseif($type=="tracher"){
           $title=' ';
           $keywords=' ';
           $description=' ';
       }else{
           $title=__('core.home_header') ;
           $keywords=__('core.home_keywords');
           $description=__('core.home_description');
       }


        $seoInfo = '<title>'. $title. '</title>'.
        '<meta name="keywords" content="'.$keywords.'">'.
        '<meta name="description" content="'.$description.'">'.
        '<meta property="og:title" content="'.$title.'"/>'.
        '<meta property="og:type" content="product"/>'.
        //   '<meta property="og:image" content="'.'$image'.'"/>'.
        '<meta property="og:site_name" content="'.$siteName.'"/>'.
        '<meta property="og:description" content="'. $description .'"/>'.

        '<meta name="twitter:card" content="summary_large_image"/>'.
        '<meta name="twitter:site" content="'.$siteName.'"/>'.
        '<meta name="twitter:title" content="'.$title.'"/>'.
        '<meta name="twitter:description" content="'.$description.'"/>'.
        //   '<meta name="twitter:image" content="'.$image.'"/>';

        View::share('seo_info', $seoInfo);

    }


}
