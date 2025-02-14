<?php

namespace App\Models;

use App\course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Lesson extends Model
{
    use Translatable;
    protected $table = 'lessons';
    public $useTranslationFallback = true;
    public $translationForeignKey = 'lesson_id';
    public $translatedAttributes = ['title','alias','html','header','meta_description','meta_keywords','trans_image','trans_status','video_duration','zoom_link','started_at'];
    public $translationModel = 'App\Models\LessonTranslation';
    protected $fillable = ['name','course_id','teacher_id','logo','sort','status_id','lesson_study_type_id','created_by_admin_id','created_by_teacher_id'];

    const FOLDER_HTML = 'lessons/html';
    const FOLDER_IMAGE =  'lessons';

    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    // ???????????????????????? less what is this used for
    public function courses()
    {
        return $this->morphToMany(course::class, 'courseable', 'course_track');
    }
    // -------------------------------------


    public function seen()
    {
        // is lesson seen
        return $this->morphOne(StudentSeen::class, 'seenable');
    }

    public function course()
    {
        return $this->belongsTo(course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function options()
    {
        return $this->hasMany(ItemOption::class, 'item_id');
    }

    public function lesson_study_type()
    {
        return $this->belongsTo(Lookup::class, 'lesson_study_type_id', 'id');
    }

    public function lesson_responses()
    {
        return $this->hasMany(LessonResponse::class);
    }

    public function scopeWithDetails($query)
    {
        return $query->with(['course:id', 'teacher:id']);
    }

    public function scopeWithActiveFullDetails($query)
    {
        return $query->with(['course:id', 'teacher:id', 'options.option']);
    }

    public function getDescription()
    {
        return  Storage::exists($this->html ?? 'nofile') ? file_get_contents(Storage::url($this->html)) : '' ;
    }


    public function isDataStudyType()
    {
        return $this->lesson_study_type_id == Lookup::getDataLessonStudyType();
    }

    public function isResearchStudyType()
    {
        return $this->lesson_study_type_id == Lookup::getResearchLessonStudyType();
    }

}
