<?php

namespace App\Models;

use App\member;
use Illuminate\Database\Eloquent\Model;

class StudentSeen extends Model
{
    protected $table = 'student_course_seens';
    protected $fillable = ['student_id','seenable_id','seenable_type'];

    public function student()
    {
      return $this->belongsTo(member::class);
    }

    public function seenable()
    {
        return $this->morphTo();
    }
    public function scopeLesson($query)
    {
        return $query->where('seenable_type', 'lessons');
    }
    public function scopeTest($query)
    {
        return $query->where('seenable_type', 'tests');
    }
    public function isLesson()
    {
        return $this->seenable_type == 'lessons';
    }
    public function isTest()
    {
        return $this->seenable_type == 'tests';
    }


}
