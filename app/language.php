<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class language extends Model
{
    protected $fillable = [
        'name','alies','status'
    ];

    protected $hidden = [

    ];

    protected  $table="language";
    public  $timestamps= true;


    public function category_description()
    {
        return $this->hasMany('App\category_description','language_id');
    }
    public function info_page_description()
    {
        return $this->hasMany('App\info_page_description','language_id');
    }
    public function post_description()
    {
        return $this->hasMany('App\post_description','language_id');
    }
    public  function page_block_program(){
        return $this->hasMany('App\page_block_program','language_id');
    }

    public function social_info()
    {
        return $this->hasMany('App\social_info','language_id');
    }
     public function block_items()
    {
        return $this->hasMany('App\block_items','language_id');
    }
    public function frinds()
    {
        return $this->hasMany('App\frinds','language_id');
    }

    public function Adv()
    {
        return $this->hasMany('App\Adv','language_id');
    }

    public function scopeDefault($query)
    {
       return $query->where('defualt', 1);
    }

    public function scopeActive($query)
    {
       return $query->where('status', 1);
    }
}
