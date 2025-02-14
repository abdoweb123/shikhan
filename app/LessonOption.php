<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class LessonOption extends Model
{
    public $table = "lesson_options";
    protected $fillable = [
      'locale', 'lesson_id', 'option_id', 'kay', 'value', 'created_at',	'updated_at'
    ];



}
