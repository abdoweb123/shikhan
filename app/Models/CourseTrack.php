<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseTrack extends Model
{
    protected $table = 'course_track';
    protected $fillable = ['course_id','courseable_id','courseable_type','sort'];
    public $timestamps= false;
    // public function coursetrackable()
    // {
    //     return $this->morphTo();
    // }

    public function courseable()
    {
        return $this->morphTo();
    }


    public function scopeLesson($query)
    {
        return $query->where('courseable_type', 'lessons');
    }
    public function scopeTest($query)
    {
        return $query->where('courseable_type', 'tests');
    }
    public function isLesson()
    {
        return $this->courseable_type == 'lessons';
    }
    public function isTest()
    {
        return $this->courseable_type == 'tests';
    }


    public function studentSeen()
    {
        return $this->hasOne(StudentSeen::class, 'seenable_id')->where('seenable_type', 'courseable_type');
    }

}
