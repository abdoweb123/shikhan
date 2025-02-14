<?php

namespace App\helpers;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class helper
{

	public static function getUserIp()
	{
		return request()->ip();
	}


	public static function FormatString( string $str=null )
  {
      if ($str)
      {
        	$str = str_replace('أ', 'ا', $str);
        	$str = str_replace('إ', 'ا', $str);
        	$str = str_replace('آ', 'ا', $str);
        	$str = str_replace('لإ', 'لا', $str);
        	$str = str_replace('لأ', 'لا', $str);
        	$str = str_replace('لآ', 'لا', $str);
        	$str = str_replace('ى', 'ي', $str);
        	$str = str_replace('ة', 'ه', $str);

          $str = str_replace('ِ','', $str);
          $str = str_replace('ُ','', $str);
          $str = str_replace('ٓ','', $str);
          $str = str_replace('ٰ','', $str);
          $str = str_replace('ْ','', $str);
          $str = str_replace('ٌ','', $str);
          $str = str_replace('ٍ','', $str);
          $str = str_replace('ً','', $str);
          $str = str_replace('ّ','', $str);
          $str = str_replace('َ','', $str);

        	$str = str_replace('é', '', $str);
					$str = str_replace('è', '', $str);
					$str = str_replace('à', '', $str);
					$str = str_replace('ç', '', $str);
					$str = str_replace('ù', '', $str);
					$str = str_replace('â', '', $str);
					$str = str_replace('’', '', $str);
					$str = str_replace('ô', '', $str);

      }

      return $str;
  }

  public static function formatNormal($string)
  {
    return self::FormatString(self::secureString( self::convertManySpacesToOne( trim($string) ) ));
  }

	public static function convertManySpacesToOne($string)
  {
		return preg_replace('/\s+/', ' ', $string);
	}

  public static function secureString($string)
  {
        $string = strip_tags($string);
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
        $string = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $string);
        return $string;
  }

  public static function removeHtmlTags($string)
  {
    return filter_var($string, FILTER_SANITIZE_STRING);
  }

  public static function convertToLower($string)
  {
    return mb_convert_case( $string , MB_CASE_LOWER, "UTF-8");
  }

	public static function validateAlias($result)
	{
			$result=trim($result);
			$result=str_replace(array(':', '\\', '/','/', '*' ,'(\/|)' , '|', '$' , ')' , '(' ,'?' ,'؟' ,']' ,'[' ,'}' ,'{' ,'"' ,';' ,'&' ,'^' ,'!' ,'@' ,'#' ,'%','+' ,'=',',' ,'~' ,'-','.'), ' ',$result);
			$result=trim($result);
			$result=str_replace(' ', '-', $result);
			$result=str_replace(array('----','---','--'),'-', $result);
			return $result;
	}




	public static function buildTree($elements, $parentId = 0, $depth=0)
	{
			$branch = [];
			foreach ($elements as $element) {
				if ($element->parent_id == $parentId)
				{
						$children = self::buildTree($elements, $element->id, $depth+1);
						if ($children)
						{
							$element->children = $children;
							$element->childrenIds = Arr::pluck($children,'id');
						}
						$element->depth = $depth;
						$branch[] = $element;
				}
			}

			return $branch;
	}

	public static function buildTreeRoot($objects , $dont=null , array &$result=array() , $parent=0 , $depth=0)
	{
			foreach ($objects as $key => $object)
			{
					if (($object->parent_id == $parent) && ($dont!=$object->id))
					{
							$object->depth = $depth;
							array_push($result,$object);
							unset($objects[$key]);
						 self::buildTreeRoot($objects, $dont ,$result,$object->id, $depth + 1);
					}
			}
			return $result;
	}

	public static function treeToRoot($tree,$nest)
	{
			$root = [];
			foreach ($tree as $child) {
				$root[] = $child;
				if ( isset( $child->$nest )) {
					$root = array_merge($root,self::treeToRoot($child->$nest,$nest));
				}
			}

			return $root;
	}

	public static function generateRandomString($length, $params = [])
  {
      $upper = isset($params['upper']) ? true : false;

      if ($upper){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      } else {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      }

      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }


	public static function createHtml( $oldFile, $path, $data, $params = [] )
	{

			$oldFile ? Storage::delete($oldFile) : '';

			$fileName = $path . '/' . $params['recordId'] . '_' . uniqid() . ".html";
			Storage::put($fileName, $data ?? '');
			return $fileName;

	}





}
