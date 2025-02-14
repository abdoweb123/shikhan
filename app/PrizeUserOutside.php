<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class PrizeUserOutside extends Model
{
    protected  $table="prizes_users_outside";
    protected $fillable = [
        'user_id','course_id','outside'
    ];

    const ADDED_POINTS = 3; // %
    
    public function user()
    {
        return $this->belongsTo('App\member','user_id');
    }

    public function course()
    {
        return $this->belongsTo('App\course','course_id');
    }

}
