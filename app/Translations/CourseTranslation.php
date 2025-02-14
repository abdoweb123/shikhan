<?php

namespace App\Translations;
use Illuminate\Database\Eloquent\Model;

class CourseTranslation extends Model
{
    protected $table = 'courses_translations';
    public $timestamps = true;
    protected $fillable = ['name','locale','alias','subject','duration','video_duration','header','meta_description','meta_keywords','date_at','content','updated_by']; // 'created_by',
}
