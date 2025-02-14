<?php
namespace App\libraries;

use App\language;
use DB;
use App;
use App\menu;
use App\menu_details;
use App\info_page;
use App\category;
use App\category_description;
use App\category_post_selector;
use App\post;
use App\post_description;
use App\info_page_description;
use App\social;
use App\social_info;
use App\frinds;
use App\block_items;
use App\Adv;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Cache;
//use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Input;
use App\post_import;
use App\option_values;
use App\option_value_info;
use App\post_option_value_selector;
use App\post_option;
use Illuminate\Support\Str;
use Session;

class Helpers
{

    public static function storePreviousUrl($type,$table_id)
    {
      // when user enter register page we store pre url in db and in session
      // the prev url still in session until user click register
      // we take the prev url and store that user register from this url

      if(session()->has('came_from_url')){
        $urlPrevious = session()->get('came_from_url');
        // \Illuminate\Support\Facades\Log::emergency($urlPrevious);
      } else {
        $urlPrevious = url()->previous();
        if (str_contains($urlPrevious, 'baldatayiba.com')) {
          return;
        }
      }



      $groupBy='Other';
      if (str_contains($urlPrevious, 'googleads')) {
        $groupBy='google';
      } elseif (str_contains($urlPrevious, 'facebook')) {
        $groupBy='facebook';
      }

      \App\prevUrl::create([ 'type' => $type, 'table_id' => $table_id,'groupBy'=>$groupBy, 'url' => $urlPrevious ]);
      session()->forget('came_from_url');

    }

    public static function generateRandomeString($chr_count)
    {
      return Str::random(40);
    }

    public static function get_options( $lang_id , $post_id)
    {

        // get post_options
        $post_options = DB::table('options')
        ->Join('option_info' , function($join) use ($lang_id)
        {
            $join->on('options.id', '=', 'option_info.option_id') ->where('option_info.language_id', '=', $lang_id);
        })
        ->Join('post_option' , function($join) use ($lang_id, $post_id)
        {
            $join->on('options.id', '=', 'post_option.option_id') ->where('post_option.post_id', '=', $post_id) ->where('post_option.language_id', '=', $lang_id);
        })
        ->select('options.id as options_id' , 'options.order as options_order' , 'options.icon as options_icon','options.type as options_type',
        'option_info.title as option_info_title',
        'post_option.value as post_option_value', 'options.titleGeneral')
        ->get();


        // get post_options_values
        $post_options_values = DB::table('options')
        ->Join('option_info' , function($join) use ($lang_id)
        {
            $join->on('options.id', '=', 'option_info.option_id') ->where('option_info.language_id', '=', $lang_id);
        })
        ->Join('option_values' , function($join)
        {
            $join->on('options.id', '=', 'option_values.option_id');
        })
        ->Join('option_value_info' , function($join) use ($lang_id)
        {
            $join->on('option_values.id', '=', 'option_value_info.option_value_id') ->where('option_value_info.language_id', '=', $lang_id);
        })
        ->Join('post_option_value_selector' , function($join) use ($lang_id, $post_id)
        {
            $join->on('options.id', '=', 'post_option_value_selector.option_id') ->on('post_option_value_selector.option_value_id','option_values.id')->where('post_option_value_selector.post_id', '=', $post_id);
        })
        ->select('options.id as options_id' , 'options.order as options_order' , 'options.icon as options_icon', 'options.type as options_type',
        'option_info.title as option_info_title',
        'option_values.id as option_values_id',
        'option_value_info.title as option_value_info_title',
        'post_option_value_selector.id as post_option_value_selector_id', 'options.titleGeneral' )
        ->get();

        return $post_options->merge($post_options_values);
    }

    public static function get_thisday_History( $lang_id , $date)
    {
        $data = DB::table('post')
        ->join('post_description', 'post.id', 'post_description.post_id')
        ->join('post_option', 'post.id', 'post_option.post_id')
        ->where('post_option.value','like', '%'.$date)
        ->where('post_option.option_id',15) // 15 : Milady Date
        ->where('post_description.language_id',$lang_id)
        ->where('post.status',1)
        ->where('post_description.status',1)
        ->select('post.type_id as type','post_description.title as post_description_title','post_description.alies as post_description_alies',
        'post_description.img as post_description_img','post_description.img_alt as post_description_img_alt','post.id','post_option.value as date_hadas')
        ->get();
        return $data;
    }

    public static function hadas()
    {
        $lang_id=$_GET['lang_id'];
        $date=$_GET['date'];
        $category_cash = collect(json_decode(Helpers::category_cash($lang_id)));
        $hadas_category = $category_cash->where('id', 17980);

        $hadas = DB::table('post')
        ->join('post_description', 'post.id', 'post_description.post_id')
        ->join('post_option', 'post.id', 'post_option.post_id')
        ->where('post_option.value','like', '%'.$date)
        ->where('post_option.option_id',15) // 15 : Milady Date
        ->where('post_description.language_id',$lang_id)
        ->where('post.status',1)
        ->where('post_description.status',1)
        ->select('post.type_id as type','post_description.title as post_description_title','post_description.alies as post_description_alies',
        'post_description.img as post_description_img','post_description.img_alt as post_description_img_alt','post.id','post_option.value as date_hadas')
        ->get();
        return  response()->json(['hadas'=>$hadas,'hadas_category'=>$hadas_category]);
    }

    public static function post_error_order()
    {

        $po=post::where('releaseNo','55555')->select('id','RelatedID')->get();
        foreach ($po as $p)
        {
            $cu=post::where('order',$p->RelatedID)->select('id')->first();
            $p->RelatedID=$cu->id;
            $p->save();
        }
    }

    public static function insert_authors($language )
    {
        return;
        $a="";
        $authors=DB::table('authors')->get();

        foreach ($authors as $author)
        {
            if ($author->title)
            {
                $srch=option_values::where('titleGeneral','like' , $author->title)->first();
                if (!$srch)
                {
                    $option_values=new option_values();
                    $option_values->option_id=2;  // id for option Author in options Table
                    $option_values->titleGeneral=$author->title;
                    $option_values->parent_id=0;
                    $option_values->save();
                    if ($option_values)
                    {
                        $option_value_info=new option_value_info();
                        $option_value_info->option_value_id=$option_values->id;
                        $option_value_info->language_id=$language;
                        $option_value_info->title=$author->title;
                        $option_value_info->save();
                    }
                }
            }
        }
    }

    public static function timeline($lang_id)
    {
        $url=url('/');
        //Redis::flushDB();
        if (!Cache::has($url.'timeline_all_cash'.$lang_id))
        {
            $cash = category::with(['category_description' => function($q) use ($lang_id)
            {
                $q->where('language_id','=',$lang_id);
                $q->select('id','category_id','title','alies');
            }
            ,'post' => function($q)
            { $q->select('post.id','category_post_selector.category_id','category_post_selector.post_id');}
            ,'post.post_description' => function($q) use ($lang_id)
            {
                $q->where('language_id','=',$lang_id);
                $q->select('id','post_id','title','alies');
            }
            ])->where('id',126)->select('category.id')->get();
            Cache::forever($url.'timeline_all_cash'.$lang_id,json_encode($cash));
        }
        return Cache::get($url.'timeline_all_cash'.$lang_id);
    }

    public static function defult_language()
    {
        if (!Cache::has('defult_language_cash'))
        {
            $cash = language::where('defualt',1)->first();
            Cache::forever('defult_language_cash',json_encode($cash));
        }
        return Cache::get('defult_language_cash');
    }

    public static function category_cash($lang_id)
    {
        //Cache::flush();
        $url=url('/');
        //Redis::flushDB();
        if (!Cache::has($url.'category_cash'.$lang_id))
        {
            // Cache::flush();
            $cash = DB::Table('category')
            ->join('category_description','category_description.category_id','category.id')
            //->join('home_setting','home_setting.cat_id','category.id')
            ->where('category_description.language_id',$lang_id)
            // ->where('home_setting.lang_id',$lang_id)
            ->where("category.default",0)
            // ->where("category.status",1)
            ->where("category_description.status",1)
            ->select('category.id', 'category_description.id as cd_id','category.parent_id','category_description.title', 'category_description.alies','category.order')->get();
            Cache::forever($url.'category_cash'.$lang_id , json_encode($cash));
        }
        return Cache::get($url.'category_cash'.$lang_id);
    }

    public static function repeate_section(array $cat_ids,$lang_id)
    {
        $url=url('/');
        //Redis::flushDB();
        if (!Cache::has($url.'repeate_sec_category'.$lang_id))
        {
            $cash = DB::Table('category')
            ->join('category_description','category_description.category_id','category.id')
            ->wherein('category.id',array_values($cat_ids))
            ->where('category_description.language_id',$lang_id)
            ->orwherein('category.parent_id',array_values($cat_ids))
            ->where('category_description.language_id',$lang_id)
            ->where("category.default",0)->where("category.status",1)
            ->where("category_description.status",1)
            ->select('category.id','category.parent_id','category_description.title','category_description.alies')
            ->orderBy('category_description.order', 'asc')->get();
            Cache::forever($url.'repeate_sec_category'.$lang_id , json_encode($cash));
        }
        return Cache::get($url.'repeate_sec_category'.$lang_id);
    }










     public static function secstring($string)
     {

         //$string = '<a style="width:50px;" onmouseover="<script>alert(1)</script></a>" href="https://www.maliciouswebsite.evil/search.php?PHPSESSID=">aqqaaaaaaaaaq¡™£¢∞§¶qqqqqqbcee | * <br> njjj<script>alert(1)</script></a>';

         // use htmlspecialchars , htmlentities in output not in input
         // $string = htmlentities( $string, ENT_QUOTES, "utf-8" ); // it transform (™£¢∞§¶) to words
         // $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8'); // // it transform (™£¢∞§¶) to it self nothing chnges

         $string = strip_tags($string);
         $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
         $string = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $string);

         return $string;

     }

    public static function all_menu($lang_id)
    {

      Cache::flush();
        $url=url('/');
        if (!Cache::get($url.'menu_cash'.$lang_id))
        {
            $info = DB::Table('menu_details')
                ->join('info_page','menu_details.info_id','info_page.id')
                ->join('info_page_description','info_page.id','info_page_description.info_page_id')
                ->where('info_page.status',1)
                ->where('info_page_description.status',1)
                ->where('info_page_description.language_id',$lang_id)
                ->where("type" ,"info_page")
                ->select('menu_details.info_id as id','menu_details.type as type','menu_details.menu_id as menu_id','info_page_description.title as title','info_page_description.alies as alies','info_page_description.img as img','info_page_description.img_alt as img_alt')
                ->orderBy("menu_details.order","asc")->get();
            $cat = DB::Table('menu_details')
                ->join('category','menu_details.cat_id','category.id')
                ->join('category_description','category.id','category_description.category_id')
                ->leftJoin('category as c2', 'c2.parent_id', 'category.id')
                ->leftJoin('category_description as cd2' , function($join) use ($lang_id)
                     { $join->on('c2.id', '=', 'cd2.category_id')->where('cd2.language_id', '=', $lang_id); })
                ->where('category_description.status',1)
                ->where('category_description.language_id',$lang_id)
                ->where("type" ,"category")
                ->select('menu_details.cat_id as id','menu_details.type as type','menu_details.menu_id as menu_id','category_description.title as title','category_description.alies as alies','category_description.img as img','category_description.img_alt as img_alt',
                         'cd2.title as title_sub','cd2.alies as alies_sub','cd2.img as img_sub','cd2.img_alt as img_alt_sub','category.parent_id','c2.parent_id as parent_id_sub')
                ->orderBy("menu_details.order","asc")->get();
            $cash = array_collapse([$info, $cat]);
            Cache::forever($url.'menu_cash'.$lang_id , json_encode($cash));
        }
        return Cache::get($url.'menu_cash'.$lang_id);

        // Cache::flush();
        // $url=url('/');
        // if (!Cache::get($url.'menu_cash'.$lang_id))
        // {
        //     $cash = DB::Table('menu_details')
        //                 ->join('info_page','menu_details.info_id','info_page.id')
        //                 ->join('info_page_description','info_page.id','info_page_description.info_page_id')
        //                 ->where('info_page.status',1)
        //                 ->where('info_page_description.status',1)
        //                 ->where('info_page_description.language_id',$lang_id)
        //                 ->where("type" ,"info_page")
        //                 ->select('menu_details.type as type','menu_details.menu_id as menu_id','info_page_description.title as title','info_page_description.alies as alies','info_page_description.img as img','info_page_description.img_alt as img_alt',
        //                          'a','b','c','d')
        //                 ->orderBy("menu_details.order","asc")
        //     ->UNION(DB::Table('menu_details')
        //                 ->join('category','menu_details.cat_id','category.id')
        //                 ->join('category_description','category.id','category_description.category_id')
        //                 ->leftJoin('category as c2', 'c2.parent_id', 'category.id')
        //                 ->Join('category_description as cd2' , function($join) use ($lang_id)
        //                      {
        //                          $join->on('c2.id', '=', 'cd2.category_id')->where('cd2.language_id', '=', $lang_id);
        //                      })
        //                 ->where('category_description.status',1)
        //                 ->where('category_description.language_id',$lang_id)
        //                 ->where("type" ,"category")
        //                 ->select('menu_details.type as type','menu_details.menu_id as menu_id','category_description.title as title','category_description.alies as alies','category_description.img as img','category_description.img_alt as img_alt',
        //                          'cd2.title as title_sub','cd2.alies as alies_sub','cd2.img as img_sub','cd2.img_alt as img_alt_sub')
        //                 ->orderBy("menu_details.order","asc") )->get();
        //     Cache::forever($url.'menu_cash'.$lang_id , json_encode($cash));
        // }
        //
        // return Cache::get($url.'menu_cash'.$lang_id);

    }
    ////cat_new
    public static function Cat_home($lang_id)
    {
				Cache::flush();
        $url=url('/');
        //Redis::flushDB();
        if (!Cache::has($url.'home_setting_cash'.$lang_id))
        {
            $cash = DB::table('home_setting')->where('lang_id','=',$lang_id)->select('cat_id')
				->orderBy("home_setting.section_order","asc")
				->get();

            Cache::forever($url.'home_setting_cash'.$lang_id, json_encode($cash));
        }
        return Cache::get($url.'home_setting_cash'.$lang_id);
    }
    /////end cat_new
    public static function social_media($lang_id)
    {
        //Cache::flush();
        $url=url('/');
        // dd($url);
        //   Redis::flushDB();
        if (!Cache::has($url.'social_cash'.$lang_id))
        {
            $cash = DB::table('social')
                        ->join('social_info', 'social.id', '=', 'social_info.social_id')
                        ->where('language_id','=', $lang_id)
                        ->where('status','=', 1)
                        ->select('social.title', 'social.icon','social_info.link')
                        ->get();
            Cache::forever($url.'social_cash'.$lang_id , json_encode($cash));
        }
        return Cache::get($url.'social_cash'.$lang_id);


        // $social = Cache::rememberForever('social_cash'.$lang_id, function() use ($lang_id) {
        //     return $social = DB::table('social')
        //                     ->join('social_info', 'social.id', '=', 'social_info.social_id')
        //                     ->where('language_id','=', $lang_id)
        //                     ->where('status','=', 1)
        //                     ->get();
        // });
        // return $social;

    }

    public static function googleAnalytic($language_id)
    {
        $url=url('/');
      //  Redis::flushDB();
		 if (!Cache::has($url.'googleAnaytic_cash'.$language_id))
        {

            $cash = DB::table('setting')->select('value')->where('language_id', $language_id)
                            ->where('title', "googleAnaytic")->first();
			 if (count($cash)){
			//$cash=$cash->value;
            Cache::forever($url.'googleAnaytic_cash'.$language_id , json_encode($cash));
			 }
        }
        return Cache::get($url.'googleAnaytic_cash'.$language_id);


    }


	public static function AmpAnalytic($language_id)
    {
        $url=url('/');
        if (!Cache::has($url.'ampAnaytic_cash'.$language_id))
        {
            $cash = DB::table('setting')->select('value')->where('language_id', $language_id)
                ->where('title', "googleAnaytic")->first();
                if($cash){
           $last=last(explode('UA-', $cash->value));
            $last=(explode(');', $last));
            $ampa="'UA-".$last[0];
            $ampa=str_replace("'","",$ampa);
            $ampanaly='<amp-analytics type="googleanalytics">
       <script type="application/json">
         {
         "vars": {
        "account": "'.$ampa.'"
                  },
            "triggers": {
           "trackPageview": {
         "on": "visible",
         "request": "pageview"
         }
            }
            }
            </script>
              </amp-analytics>';
        }
         //dd($ampanaly);
            if (count($cash)){
                Cache::forever($url.'ampAnaytic_cash'.$language_id , json_encode($ampanaly));
            }
        }
        return Cache::get($url.'ampAnaytic_cash'.$language_id);
    }




	public static function socialAnalytic($language_id)
    {
        $url=url('/');
        //Redis::flushDB();
        if (!Cache::has($url.'socialAnaytic_cash'.$language_id))
        {
            $cash = DB::table('setting')->select('value')->where('language_id', $language_id)
                                ->where('title', "socialcount")->first();
            Cache::forever($url.'socialAnaytic_cash'.$language_id , json_encode($cash));
        }
        return Cache::get($url.'socialAnaytic_cash'.$language_id);
    }



	public static function get_breadcraumb( $id , $type , $lang , $parent_id)
    {
        $category_cash = collect(json_decode(Helpers::category_cash($lang)));
        $breadcraumb=[];
        if ($type=='category')   //in category page we began with parent and ignor current category because currnt catgory alridy exist in catgory page and we gust put it from same page no need extra rexuest
        {
            $current_cat= collect();
            $all_parent_cat= collect();
            $current_cat=$category_cash->Where('id',$id)->first();
            if (count ($current_cat))
            {
            $current_parent=$current_cat->parent_id;
             while ( $current_parent != 0 )
                {
                 $all_parent_cat=$category_cash->where('id','=',$current_parent)->first();
                 if(count($all_parent_cat)){
                 $breadcraumb[]=array('category',$all_parent_cat->title,$all_parent_cat->alies);
                 $current_parent=$all_parent_cat->parent_id;}
                 else{
                   $current_parent=0;
                 }
                }
            }

        }


        if ($type=='post')   //in post page we well get data of currnet category  and make llop to get all parents because data of current category dosnt exist in program page
        {
        // Get parent post
			if ($parent_id !=0)
        {
                $cur=post::with(['post_description' =>function($query)  use ($lang){
                $query->where('language_id','=', $lang)
                                           ->select('post_id','title','alies')
                                           ->where('status',"=",1);
                                }])->where('id','=',$parent_id)->select('id')->first();

                if (count($cur->post_description))
                { $breadcraumb[]=array('post',$cur->post_description[0]->title,$cur->post_description[0]->alies); }
        }

        //        get category of that post
                 $current_cat=$category_cash->where('id','=',$id)->first();
			if (count($current_cat)){
                 $breadcraumb[]=array('category',$current_cat->title,$current_cat->alies);
 	         $cur_parent=$current_cat->parent_id;

                 //        get category_parent of that post
                while ( $cur_parent != 0 ) {
                     $all_parent_cat=$category_cash->where('id','=',$cur_parent)->first();
                     if(count($all_parent_cat)){
                 $breadcraumb[]=array('category',$all_parent_cat->title,$all_parent_cat->alies);
                 $cur_parent=$all_parent_cat->parent_id;}
                 else{
                   $cur_parent=0;
                 }
				}
                }


        }
        return array_reverse($breadcraumb);

    }


    public static function get_lang($path)
    {
        $url=url('/');
        //Redis::flushDB();
        if (!Cache::has($url.'langBar_cash'))
        {
            $cash = language::where('language.status',1)->select('id','name','alies','dir')->get();
            Cache::forever($url.'langBar_cash',json_encode($cash));
        }
        $cash = Cache::get($url.'langBar_cash');

        $langBar = array();
        foreach(json_decode($cash) as $row) {
            array_push($langBar,['path'=>$path.'/'.$row->alies,'language'=>$row->name,'alies'=>$row->alies,'status'=>1,'id'=>$row->id]);
        }
        return $langBar;

    }

    public static function frinds($lang_id)
    {
        $frinds=frinds::where('language_id','=', $lang_id)->get();
        //        dd($frinds);
        return $frinds;
    }


    public static function  buildTree(array $objects,$dont=null,array &$result=array(), $parent=null , $depth=0)
    {
        //          dd($parent);
        foreach ($objects as $key => $object) {
        //dd($object['parent_id']);
        if (($object['parent_id'] == $parent) && ($dont!=$object['id'])){

            $object['depth'] = $depth;

            array_push($result,$object);

            unset($objects[$key]);

           self::buildTree($objects, $dont ,$result,$object['id'], $depth + 1);

        }

        }
        //        dd($result);
        return $result;

    }

    public static function  main_section_cat($cat_id, $lang_id)
    {

        //get the all post of main category
		$all_post_category = DB::table('post_description')
	    ->join('post', 'post.id', 'post_description.post_id')
            ->join('category_post_selector', 'category_post_selector.post_id', 'post_description.post_id')
            ->join('category_description', 'category_post_selector.category_id', 'category_description.category_id')
            ->select('post.type_id as type','post_description.title as post_description_title','category_description.title as category_description_title',
                     'post_description.alies as post_description_alies','category_description.alies as category_description_alies',
                     'post_description.img as post_description_img','post_description.img_alt as post_description_img_alt','post.id')
            ->where('category_post_selector.category_id',$cat_id)
            ->where('post_description.language_id',$lang_id)->where('post_description.status',1)
            ->where('category_description.language_id',$lang_id)->where('category_description.status',1)
				->where('post.status',1)->orderBy("post.order","asc")->orderBy('post.id', 'ASC')->paginate(12);

        return $all_post_category;

    }


	 public static function  One_cat_gallery($cat_id, $lang_id,$alias)
    {
            $cat_gallery= DB::table("category_description")
                ->join("gallery_items","category_description.id","gallery_items.item_id")
                ->join("gallery_images","gallery_images.id","gallery_items.gallery_images_id")
                ->where("category_description.alies",$alias)
                ->where("category_description.language_id",$lang_id)
                ->where("category_description.status",1)
                 ->where("gallery_images.language_id",$lang_id)
                ->select("gallery_images.path_image as img",
                         "gallery_images.description as desc",
                         "category_description.title as category_title",
                         "category_description.alies as category_alies",
                         "gallery_images.id as img_id" )
                ->paginate(12);



        return $cat_gallery;

    }


    public static function getPostsTypes()
    {
        $url=url('/');
        //Redis::flushDB();
        if (!Cache::has($url.'post_types_cash'))
        {
            $cash = DB::table('post_type')->get();
            Cache::forever($url.'post_types_cash',json_encode($cash));
        }
        return Cache::get($url.'post_types_cash');


        // $post_types = Cache::rememberForever('post_types_cash', function() {
        //     return $posttypes = DB::table('post_type')->get();
        // });
        // return $post_types;

    }


    public static function left_side_bar($lang_id)
    {

         $block_list=[];
        $section=[];
        $Main=[];
        $col_order2=[];
        $section_orders_all=[];
        $col_orders_all=[];
        $section_orders=[];
        $col_orders=[];
        $block_items= block_items::where("section_type","left_side_bar")->where("language_id",$lang_id)->where("page_name","left_side_bar")->orderBy("section_order","asc")->orderBy("col_order","asc")->orderBy("item_order","asc")->get();

        foreach ($block_items as $block_item) {
        if(count($block_item)){
         $section_orders_all[]=$block_item->section_order;
         $col_orders_all[]=$block_item->col_order;
        }
        }
        $section_orders= array_unique($section_orders_all);
        $col_orders= array_unique($col_orders_all);

        foreach ($section_orders as $section_order) {
        foreach ($col_orders as $col_order) {
            foreach($block_items as $block_item){


                    if(($block_item->section_order==$section_order)&&($block_item->col_type=="related_adv")&&($block_item->col_order==$col_order)){
                          $list = array("img"=>$block_item->img,"img_alt"=>$block_item->img_alt,"post_title"=>$block_item->post_title,"cat_title"=>$block_item->cat_title,"post_alias"=>$block_item->post_alias,"img_title"=>$block_item->img_title,"cat_alias"=>$block_item->cat_alias);
                          $block_list[]=array($list);
                          $col_type=$block_item->col_type;
                          $section_type=$block_item->section_type;
                          $section_name=$block_item->section_name;
                          $part_name=$block_item->part_name;

                    }
                     if(($block_item->section_order==$section_order)&&($block_item->col_type=="Adv_square")&&($block_item->col_order==$col_order)){
                          $list = array("img"=>$block_item->img,"adv_link"=>$block_item->adv_link);
                          $block_list[]=array($list);
                          $col_type=$block_item->col_type;
                          $section_type=$block_item->section_type;
                          $section_name=$block_item->section_name;
                          $part_name=$block_item->part_name;

                    }

                    if(($block_item->section_order==$section_order)&&($block_item->col_type=="wide_adv")&&($block_item->col_order==$col_order)){
                          $list = array("img"=>$block_item->img,"adv_link"=>$block_item->adv_link);
                          $block_list[]=array($list);
                          $col_type=$block_item->col_type;
                          $section_type=$block_item->section_type;
                          $section_name=$block_item->section_name;
                          $part_name=$block_item->part_name;

                    }
                }//end foreach(block_items)
                $col_order2[]=array("col_order"=>$col_order,"col_type"=>$col_type,"block_list"=>$block_list,"part_name"=>$part_name);
                $block_list=[];
            }//end foreach(col_orders)
                $section[]=array("section_order"=>$section_order,"col_order_list"=>$col_order2,"section_name"=>$section_name,"section_type"=>$section_type);
                $col_order2=[];
        }//end foreach(section_orders)
                $Main[]=$section;
                return $Main;
    }


    public static function left_side_bar_Related($lang_id)
    {

		 $post = DB::table('post_description')
            ->join('post', 'post.id', 'post_description.post_id')
            ->leftJoin('post as p2', 'p2.id', 'post.parent_id')
            ->leftJoin('post_description as ps2', 'p2.id', 'ps2.post_id')
            ->join('category_post_selector', 'category_post_selector.post_id', 'post_description.post_id')
            ->join('category_description', 'category_post_selector.category_id', 'category_description.category_id')
            ->select('post_description.title as post_description_title','category_description.title as category_description_title',
                     'post_description.alies as post_description_alies','category_description.alies as category_description_alies',
                     'post_description.img as post_description_img','post_description.img_alt as post_description_img_alt','post.id','post.parent_id','p2.id as parent','ps2.alies as ps2_alies','ps2.title as ps2_title' )
            ->where('ps2.language_id',$lang_id)
            ->where('post_description.language_id',$lang_id)->where('post_description.status',1)
            ->where('category_description.language_id',$lang_id)->where('category_description.status',1)
	    ->where('post.status',1)->orderByRaw('RAND()')->limit(8)->orderby('post.order')->get();

                 return $post;
    }


    public static function slider_Related_cash($lang_id,$category_id)
    {


            $post = DB::table('post_description')
              ->join('post', 'post.id', 'post_description.post_id')
              ->leftJoin('post as p2', 'p2.id', 'post.parent_id')
              ->leftJoin('post_description as ps2' , function($join) use ($lang_id)
                   {
                       $join->on('p2.id', '=', 'ps2.post_id')
                       ->where('ps2.language_id', '=', $lang_id);
                   })
              ->join('category_post_selector', 'category_post_selector.post_id', 'post_description.post_id')
              ->join('category_description', 'category_post_selector.category_id', 'category_description.category_id')
              ->select('post_description.title as post_description_title','category_description.title as category_description_title',
               'post_description.alies as post_description_alies','category_description.alies as category_description_alies',
               'post_description.img as post_description_img','post_description.img_alt as post_description_img_alt','post.id','post.parent_id','p2.id as parent','ps2.alies as ps2_alies','ps2.title as ps2_title' )
               ->where('post_description.language_id',$lang_id)->where('post_description.status',1)
               ->where('category_description.category_id',$category_id)
               ->where('category_description.language_id',$lang_id)->where('category_description.status',1)
               ->where('post.status',1)->orderByRaw('RAND()')->limit(8)->orderby('post.order')->get();

        return $post;

    }


    public static function slider_Related($lang_id)
    {

		    $url=url('/');
		      $post=null;
        if (Cache::has($url.'slider_Related'.$lang_id))
            {
              $post=Cache::get($url.'slider_Related'.$lang_id);
            }
		          else{
		              $post = DB::table('post_description')
            ->join('post', 'post.id', 'post_description.post_id')
            ->leftJoin('post as p2', 'p2.id', 'post.parent_id')
			      ->leftJoin('post_description as ps2' , function($join) use ($lang_id)
                         {
                             $join->on('p2.id', '=', 'ps2.post_id')
							      ->where('ps2.language_id', '=', $lang_id);
                         })
            ->join('category_post_selector', 'category_post_selector.post_id', 'post_description.post_id')
            ->join('category_description', 'category_post_selector.category_id', 'category_description.category_id')
			         //->join('category','category_description.category_id','category.id')
            ->select('post_description.title as post_description_title','category_description.title as category_description_title',
                     'post_description.alies as post_description_alies','category_description.alies as category_description_alies',
                     'post_description.img as post_description_img','post_description.img_alt as post_description_img_alt','post.id','post.parent_id','p2.id as parent','ps2.alies as ps2_alies','ps2.title as ps2_title' )

            //->where('category.default',1)
            ->where('post_description.language_id',$lang_id)->where('post_description.status',1)
            ->where('category_description.language_id',$lang_id)->where('category_description.status',1)
	           ->where('post.status',1)->orderByRaw('RAND()')->limit(8)->orderby('post.order')->get();
			   Cache::put($url.'slider_Related'.$lang_id, $post, 360);
		    }
      return $post;



    }




    public static function Posts_Same_Category($lang_id,$category_id)
    {



            $posts = DB::table('post')
            ->leftJoin('post as p2', 'p2.id', 'post.parent_id')
            ->leftJoin('post_description as ps2' , function($join) use ($lang_id)
                         {
                             $join->on('p2.id', '=', 'ps2.post_id')
			          ->where('ps2.language_id', '=', $lang_id);
                         })
            ->join('post_description', 'post.id', 'post_description.post_id')
            ->join('category_post_selector', 'category_post_selector.post_id', 'post.id')
            ->join('category', 'category.id', 'category_post_selector.category_id')
            ->join('category_description', 'category.id', 'category_description.category_id')

	    ->where('post_description.language_id', $lang_id)
            ->where('category_description.language_id', $lang_id)
            ->where('category.id', $category_id)
            ->where('post.status', 1)
            ->where('post_description.status', 1)
            ->where('category.status', 1)
            ->where('category_description.status', 1)

            ->select('post.id',
                'post_description.title',
                'post_description.img',
                'post_description.img_alt',
                'post_description.alies',
                'category_description.title as category_title',
                'category_description.alies as category_alias',
                    'p2.id as parent'
                    ,'ps2.alies as ps2_alies',
                    'ps2.title as ps2_title')
                ->orderBy(DB::raw('RAND()'))->take(3)->get();
        return $posts;

    }


    public static function Home_info($lang_id)
    {
$url=url('/');
// Cache::flush();
        //Redis::flushDB();
        if (!Cache::has($url.'home_info_cash'.$lang_id))
        {
            $cash = info_page_description::
                    leftJoin('info_page', 'info_page.id', 'info_page_description.info_page_id')
                    ->where('info_page.name', 'home')
                    ->where('info_page_description.language_id', $lang_id)
                    ->where('info_page_description.status', 1)
                    ->select('info_page_description.title', 'info_page_description.meta_keywards',
                            'info_page_description.header', 'info_page_description.meta_description')->first();
            Cache::forever($url.'home_info_cash'.$lang_id , json_encode($cash));
        }
        return Cache::get($url.'home_info_cash'.$lang_id);


    }

    /////////////////////////////Mustafa->view////////////////////////////////////////////////////
    public static function views($id)
    {
        $post_view = post_description::where("post_id", "=", $id)->select('views')->first();
        // dd($post_view);
        // return $post_view;
        $i = $post_view->views;

        $i += 1;
        $post_view->views = $i;
        $post_view->save();
        return $i;
    }
    /////////////////////////////Mustafa->view////////////////////////////////////////////////////

 public static function select_Adv_all($lang,$squr_limit,$wide_limit,$long_limit){
       $all_adv= Adv::where("type","square")->where("language_id",$lang)->select("image","link","title","type")->orderByRaw('RAND()')->limit($squr_limit)
                ->UNION(Adv::where("type","wide")->where("language_id",$lang)->select("image","link","title","type")->orderByRaw('RAND()')->limit($wide_limit))
                ->UNION(Adv::where("type","long")->where("language_id",$lang)->select("image","link","title","type")->orderByRaw('RAND()')->limit($long_limit))
                ->get();
              return $all_adv;
     }


	 public function rate(Request $request)
    {
       $pa=Input::get('pa');
       $clicks=Input::get('clicks');
       // $postD_id1=Input::get('postD_id');
	   $postD_id=$request->postD_id;
       $postD_alisess=Input::get('postD_alisess');
       $value=Input::get('value');

        $post_rate = post_description::find($postD_id);
		 //dd($post_id);
        $prevalue=$post_rate->rate;
        $ints =array_map('intval', explode('|', $prevalue ));
        $val1=$ints[0];
        $clk1=$ints[1];
        $val2=$value+$val1;
        $clk2=$clk1+1;
        $rate=$val2.'|'.$clk2;
        $rate1=$val2/$clk2;
        if($post_rate) {
            $post_rate->rate = $rate;
            $post_rate->save();
        }
       // dd();
        return round($rate1,1);
    }

	 public static function get_Trans($for,$id)
    {


      switch ($for) {
               case "home":
                    $trans=DB::table('language')->where('language.status',1)
                    ->select('language.alies as lang_alies',"language.name as lang_name")
                    ->get();

                    break;
               case "category":
                    $trans=DB::table('language')
                        ->leftJoin('category_description' , function($join) use ($id)
                         {
                             $join->on('language.id','category_description.language_id')

				    ->where('category_description.status',1)
			           ->where("category_id","=", $id);

                         })
                    ->where('language.status',1)
                    ->select('category_description.alies as alies','language.alies as lang_alies',"language.name as lang_name")

                    ->get();


                    break;
                case "post":
                    $trans=DB::table('language')
                        ->leftJoin('post_description' , function($join) use ($id)
                         {
                             $join->on('language.id','post_description.language_id')
								 ->where('post_description.status',1)
			           ->where("post_id","=", $id);
                         })
                    ->where('language.status',1)
                    ->select('post_description.alies as alies','language.alies as lang_alies',"language.name as lang_name")

                    ->get();

                    break;
                case "info":
                    $trans=DB::table('language')->leftJoin('info_page_description' , function($join) use ($id)
                         {
                             $join->on('language.id','info_page_description.language_id')
			           ->where("info_page_id","=", $id)
                 ->where('info_page_description.status',1);
                         })
                    ->where('language.status',1)

                    ->select('info_page_description.alies as alies','language.alies as lang_alies',"language.name as lang_name")
                    ->get();
                    break;

                  case "gallery":
                     $trans=DB::table('language')
                        ->leftJoin('category_description' , function($join) use ($id)
                         {
                             $join->on('language.id','category_description.language_id')
								 ->where('category_description.status',1)
			           ->where("category_id","=", $id);
                         })


                    ->where('language.status',1)
                    ->select('category_description.alies as alies','language.alies as lang_alies',"language.name as lang_name")
                    ->get();

                    break;
		case "timeline":
                      $trans=DB::table('language')->leftJoin('info_page_description' , function($join) use ($id)
                         {
                             $join->on('language.id','info_page_description.language_id')
			           ->where("info_page_id","=", $id);
                         })
                        ->where('language.status',1)
						                          ->where('info_page_description.status',1)
                        ->select('language.alies as lang_alies','language.name as lang_name','info_page_description.alies as alies')
                        ->get();

                   break;

        }
        return $trans;


    }

}
