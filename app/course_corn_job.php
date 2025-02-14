<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class course_corn_job extends Model
{
    use SoftDeletes;
    public $table = "course_corn_jobs";
    protected $fillable = ['course_id','frequency','languages','count','status','created_by','created_at','updated_by','updated_at'];
    protected $casts = [
        'languages' => 'array',
    ];

    public function course()
    {
        return  $this->belongsTo('App\course','course_id');
    }
}
