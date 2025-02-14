<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'iso','arabic','sort','name','nicename',
    ];

    protected  $table="countries";
    public  $timestamps= false;

}
