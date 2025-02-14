<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class course_subscription extends Model
{
    public $table = "course_subscriptions";
    protected $fillable = ['course_id','user_id','created_at','updated_at'];
    public $timestamps = true;

    public function course()
    {
        return  $this->belongsTo('App\course','course_id');
    }

    public function member()
    {
        return  $this->belongsTo('App\member','user_id');
    }
}
