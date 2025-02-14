<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermTestResult extends Model
{

    public $table = "term_tests_results";
    protected $fillable = ['user_id','site_id','term_id','no_test','degree','rate','locale','code','status','created_at','updated_at'];

    public function site()
    {
        return $this->belongsTo('App\site','site_id');
    }

    public function term()
    {
        return $this->belongsTo('App\Term','term_id');
    }


    public function member()
    {
        return  $this->belongsTo('App\member','user_id');
    }

    public function language()
    {
        return $this->belongsTo(language::class,'locale','alies');
    }

    public function isExamOpened()
    {
        $exam_at = date('Y-m-d H:i:s' ,strtotime($this->exam_at));
        return  $this->exam_at ? ($exam_at <= date('Y-m-d H:i:s')) : false;
    }




}
