<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class course_test_result extends Model
{


    use SoftDeletes;
    public $table = "course_tests_results";
    protected $fillable = ['no_test','site_id','course_id','term_id','user_id','course_degree','degree','course_rate','rate','locale','flag','created_by','created_at','updated_by','updated_at'];

    public function site()
    {
        return $this->belongsTo('App\site','site_id');
    }

    public function course()
    {
        return $this->belongsTo('App\course','course_id');
    }

    public function member()
    {
        return  $this->belongsTo('App\member','user_id');
    }

    public function language()
    {
        return $this->belongsTo(language::class,'locale','alies');
    }

    public function getDirAttribute()
    {
        return @$this->language->dir;
    }


}
