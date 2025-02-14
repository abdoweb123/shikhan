<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberExtraTray extends Model
{

    public $table = "members_extra_trays";
    protected $fillable = [
        'user_id', 'course_id', 'trays', 'locale'
    ];

    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo('App\course','course_id');
    }


}
