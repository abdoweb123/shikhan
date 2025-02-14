<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class menu_details extends Model
{
    public $table = "menu_details";
    protected $fillable = [
        'menu_id', 'name','info_id','cat_id','type','order','language_id',
    ];

    public function menu()
    {
        return  $this->belongsTo('App\menu','menu_id');
    }
    public function info_page()
    {
        return  $this->belongsTo('App\info_page','info_id');
    }
    public function category()
    {
        return  $this->belongsTo('App\category','cat_id');
    }
    public function category_description()
    {
        // return  $this->belongsTo('App\category_description','cat_id','category_id');
        return $this->hasMany('App\category_description','category_id','cat_id');
    }
}
