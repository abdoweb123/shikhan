<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LessonTranslation extends Model
{
    protected $table = 'lesson_translations';
    public $timestamps = true;
    protected $fillable = ['lesson_id','locale','title','alias','html','video_duration','brief','header','meta_description','meta_keywords','trans_image','trans_status','video_duration','link_zoom','started_at'];

}
