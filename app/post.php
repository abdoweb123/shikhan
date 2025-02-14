<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    protected $fillable = [
       'page_source_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected  $table="post";
    public  $timestamps= true;

    /**
     * @return array
     */
    public function child()
    {
        return $this->hasMany('App\post','parent_id');

//            $children = $this->hasMany('App\post','parent_id');
//            foreach($children as $child) {
//                $child->id = $this;
//            }
//            return  $children;
    }
    public function child_postDescription()
    {
        return $this->child()->with('post_description');
    }

    public function parent()
    {
        return $this->belongsTo('App\post','parent_id');
    }

    public function page_source()
    {
        return $this->belongsTo('App\page_source','page_source_id');

    }
    public function post_description()
    {
        return $this->hasMany('App\post_description','post_id');
    }

    public function post_description_row()
    {
        return $this->belongsTo('App\post_description','id','post_id');
        // return $this->hasMany('App\post_description','post_id');
    }
    public function category()
    {
        return $this->belongsToMany('App\category','category_post_selector');
    }
    public function type(){
        return $this->belongsTo('App\post_type','type_id');
    }
    public  function page_block_program(){
        return $this->hasMany('App\page_block_program','post_id');
    }

     public function comment()
    {
        return $this->hasMany('App\comment','post_id');
    }

    public function Options()
    {
        return $this->hasMany('App\post_option','post_id');
    }

    public function option_values()
    {
        return $this->belongsToMany('App\option_values', 'post_option_value_selector', 'post_id','option_value_id');
    }



}
