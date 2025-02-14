<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\core\back_controller as Controller;
use Illuminate\Http\Request;
use App;
use App\course;
use App\site;
use App\siteMap;
use App\language;
use App\LessonOld;
use App\Teacher;
use Session;
use Illuminate\Support\Facades\Storage;
use DB;
use File;
class sitemapController extends Controller
{


    public static $path='/home/fadamedia/baldatayiba.com/sitemap/';

    public function create_sitemap()
    {



      // $menu_header=  $this->getMenu('ar');
      // //site menu pages
      // foreach ($menu_header as $item){
      //     if (! isset($item->children) ){
      //
      //        $link =  str_ireplace(
      //          '.com/',
      //          '.com/',
      //          strpos($item->route, 'info') ? route($item->route ,json_decode($item->params,true)) :  route($item->route)
      //        );
      //        dd($link);
      //    }
      // }
      // dd('aaaaaaaaa');

      $domain = config('app.url');






      ////// delete old data.
      siteMap::query()->delete();
      File::deleteDirectory(self::$path); // '/home/alfeqh/public_html/sitemap'

      ///////// create posts sitemaps
      $active_langs = language::where("status",1)->get();

      foreach ($active_langs as $lang) {
        $path = 'sitemap/'.$lang->alies;

        if (!File::isDirectory($path)) {
          File::makeDirectory($path, 0777, true, true);
        }

        $sitemap_id = siteMap::where('language_alies',$lang->alies)->orderByDesc('id')->first();

        $sites = DB::table('sites')->join('sites_translations','sites_translations.site_id', 'sites.id')
                        ->where('sites_translations.locale', $lang->alies)
                        ->where('sites.status','=', 1)
                        ->where('sites.deleted_at','=', null)->select('sites_translations.alias','sites.id','logo')->get();

          $sitemap = siteMap::count();
          $count = $sitemap + 1 ;
          $name = 'sitemap_'.$lang->alies.'_.xml';

          /*
          $post_sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
          $post_sitemap .= '<?xml-stylesheet type="text/xsl" href="https://baldatayiba.com/public/css/sitemap_css/main-sitemap.xsl"?>';
          $post_sitemap .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
          $menu_header=  $this->getMenu($lang->alies );
          */

          $post_sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
          /*$post_sitemap .= '<?xml-stylesheet type="text/xsl" href="'.$pathXsl.'"?>';*/
          $post_sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
          $menu_header=  $this->getMenu($lang->alies );


        //site menu pages
        foreach ($menu_header as $item){
            if (! isset($item->children) ){

                if (! isset($item->route)){
                  continue;
                }

                if ($item->route == "home"){
                  $link = route($item->route);
                } else {
                  $link = str_ireplace('.com/','.com/'.$lang->alies.'/',strpos($item->route, 'info') ? route($item->route , json_decode($item->params,true)) :  route($item->route));
                }


                $post_sitemap .= '<url>';
                $post_sitemap .= '<loc>'.$link.'</loc>';
                $post_sitemap .= '<lastmod>2021-06-21</lastmod>';
                $post_sitemap .= '<changefreq>monthly</changefreq>';
                $post_sitemap .= '<priority>1.0</priority>';
                /*
                $post_sitemap .= ' <image:image>';
                $post_sitemap .= '   <image:loc>https://baldatayiba.com/assets/img/logo2.png </image:loc>';
                $post_sitemap .= '   <image:caption><![CDATA[baldatayiba.com]]></image:caption>';
                $post_sitemap .= '   </image:image>';
                */
                $post_sitemap .= '</url>';

            }else{
                  foreach ($item->children as $subItem){
                    if (! isset($subItem->route)){
                      continue;
                    }

                     $link=  str_ireplace('.com/','.com/'.$lang->alies.'/',route($subItem->route , json_decode($subItem->params,true) ));

                    $post_sitemap .= '<url>';
                    $post_sitemap .= '<loc>'.$link.'</loc>';
                    $post_sitemap .= '<lastmod>2021-06-21</lastmod>';
                    $post_sitemap .= '<changefreq>monthly</changefreq>';
                    $post_sitemap .= '<priority>1.0</priority>';
                    /*
                    $post_sitemap .= ' <image:image>';
                    $post_sitemap .= '   <image:loc>https://baldatayiba.com/assets/img/logo2.png </image:loc>';
                    $post_sitemap .= '   <image:caption><![CDATA[baldatayiba.com]]></image:caption>';
                    $post_sitemap .= '   </image:image>';
                    */
                    $post_sitemap .= '</url>';

                  }
              }
        }

        //site sites
        foreach ($sites as $site)
          {

            $post_sitemap .= '<url>';
            $post_sitemap .= '<loc>'.$domain.'/'.$lang->alies.'/'.$site->alias.'</loc>';
            $post_sitemap .= '<lastmod>2021-06-21</lastmod>';
            $post_sitemap .= '<changefreq>monthly</changefreq>';
            $post_sitemap .= '<priority>1.0</priority>';
            /*
            $post_sitemap .= ' <image:image>';
            $post_sitemap .= '   <image:loc>'.$site->logo != null ? url(\Storage::url($site->logo)): ''.'</image:loc>';
            $post_sitemap .= '   <image:caption><![CDATA[baldatayiba.com]]></image:caption>';
            $post_sitemap .= '   </image:image>';
            */
            $post_sitemap .= '</url>';

            //site courses
            $courses = DB::table('courses')
                  ->join('courses_translations','courses_translations.course_id', 'courses.id')
                  ->join('course_site','course_site.course_id', 'courses.id')
                  ->where('course_site.site_id', $site->id)
                  ->where('courses_translations.locale', $lang->alies)
                  ->where('courses.status',1)
                  ->where('courses.deleted_at', null)->select('courses_translations.alias','courses.id','logo')->get();

            foreach($courses as $course){

                $post_sitemap .= '<url>';
                $post_sitemap .= '<loc>'.$domain.'/'.$lang->alies.'/'.$site->alias.'/'.$course->alias.'</loc>';
                $post_sitemap .= '<lastmod>2021-06-21</lastmod>';
                $post_sitemap .= '<changefreq>monthly</changefreq>';
                $post_sitemap .= '<priority>1.0</priority>';
                /*
                  $post_sitemap .= ' <image:image>';
                  $post_sitemap .= '   <image:loc>'.$course->logo != null ? url(\Storage::url($course->logo)):'https://api.fadamedia.com/assets/images/logo.jpg'.'</image:loc>';
                  $post_sitemap .= '   <image:caption><![CDATA[baldatayiba.com]]></image:caption>';
                  $post_sitemap .= '   </image:image>';
                */
                $post_sitemap .= '</url>';

                  // dd($course->post_ids);
                  $cou =  course::where('id',$course->id)->first();
                  //site lessons
                  //   $lessons = DB::table('lessons')->join('lesson_translations','lesson_translations.lesson_id', 'lessons.id')
                  //                       ->whereIn('lessons.id', $cou->post_ids)
                  //                       ->where('lesson_translations.locale', $lang->alies)->select('lesson_translations.alias','lesson_translations.title','lessons.title_general','lessons.id','image')->get();
                  // // foreach($lessons as $lesson){
                  //
                  //   $post_sitemap .= '<url>';
                  //   $post_sitemap .= '<loc>https://baldatayiba.com/'.$lang->alies.'/'.$site->alias.'/'.$course->alias.'/'.$lesson->alias.'</loc>';
                  //   $post_sitemap .= '<lastmod>2021-06-21</lastmod>';
                  //   $post_sitemap .= '<changefreq>monthly</changefreq>';
                  //   $post_sitemap .= '<priority>1.0</priority>';
                  //     $post_sitemap .= ' <image:image>';
                  //     $post_sitemap .= '   <image:loc>'.$lesson->image != null ? url(\Storage::url($lesson->image)):''.'</image:loc>';
                  //     $post_sitemap .= '   <image:caption><![CDATA[baldatayiba.com]]></image:caption>';
                  //     $post_sitemap .= '   </image:image>';
                  //
                  //   $post_sitemap .= '</url>';
                  // }

            }

          }

          //site teachers
          $teachers=Teacher::get();
          foreach($teachers as $teacher){
          $teacheralias=  str_ireplace(' ','_',$teacher->name);
            $post_sitemap .= '<url>';
            $post_sitemap .= '<loc>'.$domain.'/'.$lang->alies.'/teachers/'.$teacheralias.'</loc>';
            $post_sitemap .= '<lastmod>2021-06-21</lastmod>';
            $post_sitemap .= '<changefreq>monthly</changefreq>';
            $post_sitemap .= '<priority>1.0</priority>';
            /*
              $post_sitemap .= ' <image:image>';
              $post_sitemap .= '   <image:loc>'.$teachers->image != null ? url(\Storage::url($teachers->image)):''.'</image:loc>';
              $post_sitemap .= '   <image:caption><![CDATA[baldatayiba.com]]></image:caption>';
              $post_sitemap .= '   </image:image>';
            */
            $post_sitemap .= '</url>';
          }

          $post_sitemap .= '</urlset>';

          File::put('sitemap/'.$lang->alies.'/'.$name, $post_sitemap);
          $insert_map = new siteMap;
          $insert_map -> file_name = $name;
          $insert_map -> language_alies = $lang->alies;
          $insert_map -> save();

      }


      return 'Done';


    }
    // private function getMenu($language)
    // {
    //
    //     $data = DB::Table('menus')->orderBy('sort')->get();
    //     foreach ($data as $item) {
    //
    //        if ($item->type == 'page'){
    //          $link = DB::Table('page_info')->where('page_id',$item->type_id)->where('language',$language)
    //           ->where('is_active',1)
    //           // ->where('route','!=','home')
    //           ->select('page_info.title','page_info.alias','page_info.route','page_info.params','page_info.image')
    //           ->first();
    //          if ($link){
    //            $item->title = $link->title;
    //            $item->alias = $link->alias;
    //            $item->image = $link->image;
    //            $item->route = $link->route;
    //            $item->params = str_replace("**", $link->alias, $link->params);
    //          }
    //        }
    //
    //     }
    //
    //     return buildTree($data, $parentId = 0, $depth=0);
    //
    //   }

    private function getMenu($language)
	  {

    		$data = DB::Table('menus')->where('is_active',1)->orderBy('sort')->get();

    		foreach ($data as $item) {
    				if ($item->type == 'page'){
    					$link = DB::Table('page_info')->where('page_id',$item->type_id)->where('language',$language)->where('is_active',1)->select('page_info.title','page_info.alias','page_info.route','page_info.params','page_info.image')->first();
    					if ($link){
    						$item->title = $link->title;
    						$item->alias = $link->alias;
    						$item->image = $link->image;
    						$item->route = $link->route;
    						$item->params = str_replace("**", $link->alias, $link->params);
    					}
    				}
    		}

		    return buildTree($data, $parentId = 0, $depth=0);

	   }

}
