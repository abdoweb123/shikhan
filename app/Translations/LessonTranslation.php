<?php

namespace App\Translations;
use Illuminate\Database\Eloquent\Model;

class LessonTranslation extends Model
{
    protected $table = 'lesson_translations';
    protected $fillable = ['lesson_id','locale','title','alias','image','video_duration','html','link_zoom','started_at','brief','header','meta_description','meta_keywords','trans_status','ip','access_user_id'];
    // 'pdf','sound','video',
    public $timestamps = true;
}
