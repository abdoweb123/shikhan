<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $fillable = [
        'parent_id', 'page_source_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected  $table="category";
    public  $timestamps= true;

    /**
     * @return array
     */
    public function child()
    {
        return $this->hasMany('App\category','parent_id');
    }
    public function parent()
    {
        return $this->belongsTo('App\category','parent_id');
    }
    public function page_source()
    {
        return $this->belongsTo('App\page_source','page_source_id');

    }
    public function category_description()
    {
        return $this->hasMany('App\category_description','category_id');
    }
    public function post()
    {
       return $this->belongsToMany('App\post','category_post_selector');
    }
    // 
    // public function post_description()
    // {
    //     return $this->belongsTo('App\post_description','id','post_id');
    //     return $this->belongsToMany('App\post','category_post_selector','course_id','post_id');
    // }

     public function block_items()
    {
        return $this->hasMany('App\block_items','catg_id');
    }

     public  function menu_details(){
        return $this->hasMany('App\menu_details',"cat_id");
    }
}
