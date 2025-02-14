<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Rate extends Model
{
    public $table = "rates";

    protected $fillable = ['teacher_id','user_id','rated','created_at','updated_at'];


}
