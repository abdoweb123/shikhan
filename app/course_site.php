<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class course_site extends Model
{
    protected $table = 'course_site';

    protected $fillable = ['site_id','course_id','term_id','short_link'];
    public $timestamps = false;

    // protected $casts = [
    //     'certificate_template' => 'array',
    // ];


}
