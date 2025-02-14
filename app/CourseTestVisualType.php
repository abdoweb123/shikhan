<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseTestVisualType extends Model
{

    protected $connection = 'elmacademy_db_util';
    public $table = "course_tests_visual_types";
    protected $fillable = ['title'];




}
