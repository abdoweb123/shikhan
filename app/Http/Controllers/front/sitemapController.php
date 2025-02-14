<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\core\front_controller as Controller;
use Illuminate\Http\Request;
use App;
use App\course;
use App\site;
use App\siteMap;
use App\language;
use App\LessonOld;
use Session;
use Illuminate\Support\Facades\Storage;
use DB;
use File;
use Response;

class sitemapController extends Controller
{

  public function index(Request $request, $lang)
  {

     $sitemap = siteMap::where('language_alies', '=', $lang)->get()->first();
        // $sitemap = siteMap::where('file_name', '=', $request->name)->first();
      if (isset($sitemap->file_name) && $sitemap->file_name !='')
      {
      // return  file_get_contents('sitemap/'.$sitemap->language_alies.'/'.$sitemap->file_name);
        return response(file_get_contents('sitemap/'.$sitemap->language_alies.'/'.$sitemap->file_name), 200, [
            'Content-Type' => 'application/xml'
        ]);
      }
      else{ return redirect('/'); }


    }

    public function index_all(Request $request)
    {
      ////////// create index file.

      $sitemap = siteMap::get();
      $domain = config('app.url');

      $index = '<?xml version="1.0" encoding="UTF-8"?>';
      $index .= '<?xml-stylesheet type="text/xsl" href="'.$domain.'/public/css/sitemap_css/main-sitemap.xsl"?>';
      $index .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';


      foreach($sitemap as $maps)
      {
        $index .= '<sitemap>';
        $index .= '<loc>'.url('/').'/'.$maps->language_alies.'/sitemap.xml </loc>';
        $index .= '</sitemap>';
      }

      $index .= '</sitemapindex>';

      $response = Response::make($index);
      $response->header('Content-Type', 'text/xml');

      return $response;
    }

    public function view_sitemap(Request $request, $name)
    {
      $sitemap = siteMap::where('file_name', '=', $request->name)->first();
      if (isset($sitemap->file_name) && $sitemap->file_name !='')
      {
        return response(file_get_contents('sitemap/'.$sitemap->language_alies.'/'.$sitemap->file_name), 200, [
            'Content-Type' => 'application/xml'
        ]);
      }
      else{ return redirect('/'); }

    }

}
