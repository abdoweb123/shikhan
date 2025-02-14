<?php



function maxTests(){ return 2;}

function maxTestsPerDay(){ return 5;}

function pointOfSuccess(){ return 50;}

function ejazaPointsOfSuccess(){ return 85;}

function advancedSiteCertificateCondition(){ return [6,20];}

function calculateCourseRate($degree)
{
    return $degree >= 90 && $degree <= 100 ? 5 : ($degree >= 80 && $degree < 90 ? 4 : ($degree >= 70 && $degree < 80 ? 3 : ($degree >= 60 && $degree < 70 ? 2 : ($degree >= 50 && $degree < 60 ? 1 : 0))));
}

function getDefaultLanguage()
{
    return (new \App\Services\LanguageService())->getDefaultLanguage();
}

function isExamOpened($date)
{
  $exam_at = date('Y-m-d H:i:s' ,strtotime($date));
  return  $date ? ($exam_at <= date('Y-m-d H:i:s')) : false;
}


function courseRateRanges()
{
    return [
        0 => [0,49],
        1 => [50,59],
        2 => [60,69],
        3 => [70,79],
        4 => [80,89],
        5 => [90,100]
    ];
}

function getAuthId()
{
    if (auth()->check()){
      return (auth()->id() * 3) .'-'. (auth()->id() - 1) .'-'. (auth()->id() + 5);
    }

    return null;
}

function ourAuth()
{
    if (auth()->check()){
      // auth()->id() == 39
      if ( auth()->id() == 224 || auth()->id() == 65 ){
        return true;
      }
    }

    return false;
}

function ejazaExtraTraysIds()
{
    // دورات اجازة لاعطاء الطالب فرصة زائدة
    return [289,290,291,292];
}

function get_dir($locale_script)
{
    switch ($locale_script)
    {
        case 'Arab':
        case 'Hebr':
        case 'Mong':
        case 'Tfng':
        case 'Thaa':
        return 'rtl';
        default:
        return 'ltr';
    }
}
 function getUserIp() {
		return request()->ip();
	}
function caneny($abilities)
{
    if (is_string($abilities))
    {
        $abilities = [$abilities];
    }
    $return = [];
    foreach ($abilities as $ability)
    {
        $return[] = \Gate::check($ability);
    }
    return $return;
}

function get_date($date,$between = ' ')
{
    return
    __('calculator.num.'.date('d',strtotime($date)))
    .$between.
    __('calculator.month.'.date('m',strtotime($date)))
    ;
}

function set_meta($page_key,$meta = [])
{
    $data =
    [
        'title' => __('meta.title.'.$page_key),
        'keywords' => __('meta.description.'.$page_key),
        'description' => __('meta.keywords.'.$page_key),
    ];

    $return = [] ;
    foreach (['title','keywords','description'] as $f)
    {
        $r = (empty($meta[$f])) ? $data[$f] : $meta[$f] ;
        $r = (empty($r)) ? $data[$f]['home'] : $r ;
        $retuen[$f] = $r ;
    }
    return $retuen;
}

function get_pages_parents($parent_id = 0,$where = [])
{
    $result = \App\Page::where(['active'=>'1','is_static' => '1','parent_id' => $parent_id] + $where)->select('id','type')->orderBy('sequence', 'ASC')->get()->toArray();
    $return = [];
    foreach($result as $row)
    {
        $return[$row['id']] = $row;
        $return[$row['id']]['children'] = get_pages_parents($row['id'],$where);
    }
    return $return;
}

function get_lessons_parents($parent_id = NULL,$where = [])
{
    $result = \App\LessonOld::where(['active'=>1,'parent_id' => $parent_id] + $where)->select('id')->orderBy('sequence', 'ASC')->get()->toArray();
    $return = [];
    foreach($result as $row)
    {
        $return[$row['id']] = $row;
        $return[$row['id']]['children'] = get_lessons_parents($row['id'],$where);
    }
    return $return;
}

function get_pages()
{
    $lang = App::getLocale() ;
    $pages = config('app.settings.pages.static');

    $fk = array_keys($pages)[0];
    $query = \App\Page::select('type');
    foreach ($pages as $k => $v)
    {
        $where = [] ;if ($v == '0'){$where[] = ['deletable','=','0'] ;}else{$where[] = ['id','=',$v] ;}
        $where[] = ['type','=',$k] ;$where[] = ['is_static','=','1'] ;
        if ($fk == $k){$query->where(function($query) use ($where){$query->where($where);});}
        else{$query->orWhere(function($query) use ($where){$query->where($where);});}
    }
    return $query->pluck('slug','type')->toArray();
}

function get_menu()
{
    $lang = App::getLocale() ;
    $pages = config('app.settings.pages.static');

    $fk = array_keys($pages)[0];
    $query = \App\Page::select('type');
    foreach ($pages as $k => $v)
    {
        $where = [] ;if ($v == '0'){$where[] = ['deletable','=','0'] ;}else{$where[] = ['id','=',$v] ;}
        $where[] = ['type','=',$k] ;$where[] = ['is_static','=','1'] ;
        if ($fk == $k){$query->where(function($query) use ($where){$query->where($where);});}
        else{$query->orWhere(function($query) use ($where){$query->where($where);});}
    }
    return $query->pluck('slug','type')->toArray();
}

function get_footer_list()
{
    return \App\Page::select('id')->where(['in_footer' => '1','active'=> '1'])->orderBy('sequence','ASC')->orderBy('sequence','ASC')->get()->toArray();
}

function get_header_list()
{
    return \App\Page::select('id')->where(['in_header' => '1','active'=> '1'])->orderBy('sequence','ASC')->orderBy('sequence','ASC')->get()->toArray();
}

function get_page($page_type = NULL,$id = 0,$type = null)
{
    $where = ['active'=>'1'];
    $query = \App\Page::select('id','type','is_static')->orderBy('sequence', 'ASC');

    if ($page_type != NULL){$where['is_static'] = ($page_type == 'static'?'1':'0');}
    if ($type != NULL){$where['type'] = $type;}
    if (is_numeric($id)){$where['id'] = $id ;}else {$query->whereTranslation('slug',urlencode($id));}

    return $query->where($where)->first();
}


//function get_options($data,$id = null)
//{
//    if (!function_exists('option_loop'))
//    {
//        function option_loop($data,$sub = '', $id)
//        {
//            $options = [];
//            if(isset($data))
//            {
//                foreach ($data as $row)
//                {
//                    if($id != $row['id'])
//                    {
//                        $options[$row['id']] = $sub.$row['name'];
//                        $sub_o = $sub.'&nbsp; &'.(\LaravelLocalization::getCurrentLocaleDirection() ? 'DoubleLeftArrow' : 'DoubleRightArrow' ).'; &nbsp;';
//                        $options = $options + option_loop($row['children'],$sub_o,$id);
//                    }
//                }
//            }
//            return $options;
//        }
//    }
//    return option_loop($data,'',$id);
//}

function get_primary_image($images,$type = 'primary')
{
    return !empty($images) ? ( (empty($images[$type])) ?
    $images[array_keys($images)[0]] : $images[$images[$type]] ) : [] ;
}

function get_categories_children($parent_id = 0,$where = [])
{
    $result = \App\Category::where(['active'=>'1','parent_id' => $parent_id] + $where)->select('id')->orderBy('sequence', 'ASC')->get();
    $return = [];
    foreach($result as $row)
    {
        $return[$row['id']] = $row;
        $return[$row['id']]['children'] = get_categories_children($row['id'],$where);
    }
    return $return;
}

function get_categories_parents($id = 0,$where = [])
{
    $result = \App\Category::where(['active'=>'1','id' => $id] + $where)->select('id','parent_id')->orderBy('sequence', 'ASC')->get();
    $return = [];
    foreach($result as $row)
    {
        $slug = [];foreach (\LaravelLocalization::getSupportedLanguagesKeys() as $lang){$slug[$lang] = urldecode($row['slug:'.$lang]);}$row['slug'] = $slug ;
        $return[$row['id']] = ['slug' => $slug,'name' => $row['name']];
        $return = $return + get_categories_parents($row['parent_id'],$where);
    }
    return $return;
}

function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
 function buildTree($elements, $parentId = 0, $depth=0)
	{
			$branch = [];
			foreach ($elements as $element) {
				if ($element->parent_id == $parentId)
				{
						$children = buildTree($elements, $element->id, $depth+1);
						if ($children)
						{
							$element->children = $children;
							$element->childrenIds = \Illuminate\Support\Arr::pluck($children,'id');
						}
						$element->depth = $depth;
						$branch[] = $element;
				}
			}

			return $branch;
	}
	function formatNormal($string)
	{
		return secureString(trim($string));
	}

	function secureString($string)
	{
				$string = strip_tags($string);
				$string = preg_replace('/[\r\n\t ]+/', ' ', $string);
				$string = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $string);
				return $string;
	}
	function validateAlias($result)
	{
			$result=trim($result);
			$result=str_replace(array(':', '\\', '/','/', '*' ,'(\/|)' , '|', '$' , ')' , '(' ,'?' ,'؟' ,']' ,'[' ,'}' ,'{' ,'"' ,';' ,'&' ,'^' ,'!' ,'@' ,'#' ,'%','+' ,'=',',' ,'~' ,'-','.'), ' ',$result);
			$result=trim($result);
			$result=str_replace(' ', '-', $result);
			$result=str_replace(array('----','---','--'),'-', $result);
			return $result;
	}
    function	convertToLower($string)
	{
		return mb_convert_case( $string , MB_CASE_LOWER, "UTF-8");
	}
		 function createHtml( $path, $data, $params = [] )
	{
       $fileName = $params['recordId'] . '_' . uniqid() . ".html";
       $fullPath = 'storage/app/public/' . $path;
       if (! file_exists($fullPath)) {
         \File::makeDirectory($fullPath, $mode = 0777, true, true);
       }
       \File::put($fullPath . "/" . $fileName , $data);
       return $path . "/" . $fileName;
	}

  function getActiveLanguages()
  {
    return (new \App\Services\LanguageService())->getActiveLanguages();
  }

  function getLanguages()
  {
    return (new \App\Services\LanguageService())->getAll();
  }


if(! function_exists('lookupService')){
    function lookupService()  { return app(App\Services\LookupService::class); }
}


function getCurrentGuard()
{
    foreach(array_keys(config('auth.guards')) as $guard){
        if(auth()->guard($guard)->check()) return $guard;
    }
    return null;
}

if(! function_exists('studyService')){
    function studyService()  { return app(App\Services\StudyService::class); }
}

function getDegreeSuccess()
{
    return 50;
}


if(! function_exists('resultsService')){
    function resultsService()  { return app(App\Services\ResultsService::class); }
}

