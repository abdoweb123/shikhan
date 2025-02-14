<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberQuizTime extends Model
{
    protected $connection= 'elmacademy_db_util';
    protected  $table="member_quiz_time";
    protected $fillable = [
        'user_id','site_id','course_id','term_id','questions_count','question_period','elapsed_time','start_time'
    ];

    public $timestamps= false;
    protected $casts = [
        'start_time'  => 'datetime',
    ];
    // public function Adv()
    // {
    //     return $this->hasMany('App\Adv','language_id');
    // }

}
