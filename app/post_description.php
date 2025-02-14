<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class post_description extends Model
{
    protected $fillable = [
        'language_id', 'post_id','userUpdate_id','title','meta_keywards','meta_description'
        ,'header','img','img_alt','tags','description','alies','order','status'
    ];

    protected $hidden = [];

    protected  $table="post_description";
    public  $timestamps= true;


    public function language()
    {
        return  $this->belongsTo('App\language','language_id');
    }
    public function post()
    {
        return  $this->belongsTo('App\post','post_id');
    }
    public function updated_user()
    {
        return  $this->belongsTo('App\user','userUpdate_id');
    }
    public function Des_op_selector()
    {
        return $this->hasMany('App\post_option_selector','post_description_id');
    }
    public function page_source()
    {
        return $this->belongsTo('App\page_source','post_description_id');
    }  
  

}
