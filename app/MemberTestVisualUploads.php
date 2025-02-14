<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberTestVisualUploads extends Model
{
    protected $connection = 'util_db';
    public $table = "members_tests_visual_uploads";
    protected $fillable = ['course_test_visual_id','file','type','language','title'];

    protected $appends = ['value'];

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

    public function getValueAttribute()
    {
      return $this->file;
    }



}
