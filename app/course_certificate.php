<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class course_certificate extends Model
{
    use SoftDeletes;
    public $table = "course_certificates";
    protected $fillable = ['course_id','subject','content','locale','created_by','created_at','updated_by','updated_at'];

    public function course()
    {
        return  $this->belongsTo('App\course','course_id');
    }
}
