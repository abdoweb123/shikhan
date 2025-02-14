<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class siteMap extends Model
{
    protected $fillable = [
        'file_name','language_alies'
    ];


    protected $hidden = [

    ];

    protected  $table="sitemap";
    public  $timestamps= true;




}
