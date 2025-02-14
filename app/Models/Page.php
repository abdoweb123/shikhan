<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table='pages';
    protected $fillable = [
        'title_general','parent_id','is_active'
    ];

    const FOLDER = 'pages';
    const FOLDER_HTML = 'pages/html';
    const PAGE = 'pages';

    public function translation( $language = null )
    {
        $language = $language ?? app()->getLocale();
        return $this->hasMany('App\Models\PageInfo','page_id')->where('language','=',$language);
    }

    public function translations()
    {
        return $this->hasMany('App\Models\PageInfo','page_id');
    }

    public function activeTranslation()
    {
        $language =  app()->getLocale();
        return $this->hasMany('App\Models\PageInfo','page_id')->where('language','=',$language)->where('is_active',1);
    }

    public function page_info()
    {
        return $this->hasMany('App\Models\PageInfo','page_id');
    }

    public function active_page_info()
    {
        return $this->hasMany('App\Models\PageInfo','page_id')->where('is_active',1);
    }




    public function scopeDetails($query,$language = null)
    {
        if ($language != 'all') {
          $language = $language ?? app()->getLocale();
        }

        return $query->with(['page_info' => function($q) use($language) {
          if ($language != 'all') {
            return $q->where('language',$language);
          }
        }]);
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function getImagePath()
    {
        return asset('storage/app/public/'.$this->image);
    }

}
