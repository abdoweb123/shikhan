<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category_description extends Model
{
    protected $fillable = [
        'language_id', 'category_id','userUpdate_id','title','meta_keywards','meta_description'
        ,'header','img','img_alt','tags','description','alies','order','status'
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [

    ];

    protected  $table='category_description';
    public  $timestamps= true;

    /**
    * @return array
    */
    public function language()
    {
        return  $this->belongsTo('App\language','language_id');
    }
    public function category()
    {
        return  $this->belongsTo('App\category','category_id');
    }
    public function post()
    {
       return $this->belongsToMany('App\post','category_post_selector','post_id','category_id');
    }
    public function updated_user()
    {
        return  $this->belongsTo('App\user','userUpdate_id');
    }

    public  function menu_details()
    {
        return $this->hasMany('App\menu_details','cat_id','category_id');
    }
}
