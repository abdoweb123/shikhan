<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberCoursesResult extends Model
{

    public $table = "member_courses_results";
    protected $fillable = [
        'user_id', 'site_id', 'course_id', 'term_id','locale','tests_count', 'test_no_test', 'test_course_degree', 'test_degree', 'test_course_rate', 'test_rate', 'test_id', 'test_locale', 'test_code', 'test_created_at','site_new_flag'
    ];


    public function site_translation()
    {
        return $this->belongsTo('App\Translations\SiteTranslation', 'site_id', 'site_id')->where('locale', app()->getLocale());
    }

    public function course_translation()
    {
        return $this->belongsTo('App\Translations\CourseTranslation', 'course_id', 'course_id')->where('locale', app()->getLocale());
    }

    public function scopeSuccessed($query)
    {
        return $query->where('test_degree', '>=',  pointOfSuccess());
    }

    public function scopeNotSuccessed($query)
    {
        return $query->where('test_degree', '<',  pointOfSuccess());
    }

}
