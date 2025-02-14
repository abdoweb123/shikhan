<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
// use App\Services\CategoryService;
// use App\Services\ProductService;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
// use App\Models\Category;
use DB;
use Illuminate\Support\Facades\Cache;
use App\Traits\ApiResponse;

class SharedController extends Controller
{

    use ApiResponse;
    // private $categoryServ;
    // private $productService;

    // public function __construct(CategoryService $categoryService, ProductService $productService)
    // {
    //     $this->categoryServ = $categoryService;
    //     $this->productService = $productService;
    //
    // }

    public function index()
    {

        $social = Cache::rememberForever('socials', function () {
            return DB::Table('social')->join('social_translation','social.id','social_translation.social_id')->select('link','icon')->get();
        });

        $menu_header = Cache::rememberForever('menu_header', function () {
            return $this->getMenu( app()->getlocale() );
        });

        $response = [
          'socials' => $social,
          'menu_header' => $menu_header
        ];

        return $this->responseSuccess([
            'data' => $response,
        ]);
    }


    private function getMenu($language)
    {

        $data = DB::Table('menus')->orderBy('sort')->get();
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
