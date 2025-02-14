<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseTestVisual extends Model
{

    protected $connection = 'elmacademy_db_util';
    public $table = "course_tests_visual";
    protected $fillable = ['user_id','site_id','course_id','language','rate','type_id','comment'];

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
        return  $this->belongsTo('App\member','user_id')->withTrashed();
    }

    public function language()
    {
        return $this->belongsTo(language::class,'locale','alies');
    }

    public function members_tests_visual_uploads()
    {
        return $this->hasMany('App\MemberTestVisualUploads','course_test_visual_id');
    }

    public function type()
    {
        return $this->belongsTo('App\CourseTestVisualType','type_id');
    }

    public function scopeSuccess($query)
    {
        return $query->where('rate', 1);
    }


}
