<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialInfo extends Model
{
    protected $fillable = [
        'language','link','status','social_id'
    ];


    protected $hidden = [

    ];

    protected  $table="social_info";
    public  $timestamps= false;


    

}
