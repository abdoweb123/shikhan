<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermTest extends Model
{

    public $table = "term_tests";
    protected $fillable = ['term_test_result_id','question_id','answer_id','date','no_test','user_id','course_id'];
    public $timestamps = false;
    protected $dates = [
        'date',
    ];

    protected $connection = 'elmacademy_db_util';

    // protected $casts = [
    //     'answer_ids' => 'array',
    // ];

    public function term()
    {
        return  $this->belongsTo('App\Term','term_id');
    }

    public function member()
    {
        return  $this->belongsTo('App\member','user_id');
    }

    public function question()
    {
        return $this->belongsTo('App\course_question', 'question_id');
    }

    public function answers()
    {
        return $this->hasMany('App\course_answer', 'answer_id');
    }
}
