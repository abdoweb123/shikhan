<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Auth;
class Lesson extends Model
{
    const FOLDER = 'lessons';
    const FOLDER_IMAGE = 'lessons/images';
    const FOLDER_PDF = 'lessons/pdf';
    const FOLDER_SOUND = 'lessons/sound';
    const FOLDER_HTML = 'lessons/html';
    const PAGE = 'lesson';

    use Translatable, SoftDeletes;
    public $table = "lessons";
    public $useTranslationFallback = true;
    public $translationForeignKey = 'lesson_id';
    public $translatedAttributes = ['title'	,'alias','image'	,	'pdf',	'sound'	,'video','video_duration','html',	'brief','link_zoom','header','meta_description','started_at','meta_keywords','ip',	'access_user_id',	'trans_status'];
    public $translationModel = 'App\Translations\LessonTranslation';
    protected $fillable = [	'title_general'	,'image_general','course_id','teacher_id',	'sort',	'is_active'	,'created_at','updated_by','updated_at'];
    protected $casts = [
        'languages' => 'array',
    ];

    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    public function course()
    {
        return  $this->belongsTo('App\course','course_id');
    }

    public function getLogoPathAttribute()
    {
        $imag= (substr($this->logo, 0, 4) === 'http') ? $this->image : (\Storage::exists($this->image ?? '') ? url(\Storage::url($this->image ?? '')) : $this->image_general  );
        return $imag==null ? asset('assets/img/default/site.png') : $imag;
    }

    public static function default_logo()
    {
        $name = str_random(20);
        Storage::disk('storage')->copy('framework/backup/default/sites.png', 'app/public/sites/site-'.$name.'.png');
        return 'sites/site-'.$name.'.png';
    }
     public function teacher()
    {
        return $this->belongsTo('App\Teacher','teacher_id');
    }
     public function iscompleted()
    {
        return Auth::guard('web')->user()->lessons()->find($this->id) == null ? false : true;
    }

    public function Options()
    {
        return $this->hasMany('App\ItemOption','item_id');
    }

    public function option_values()
    {
        return $this->belongsToMany('App\OptionValue', 'item_option_value_selector', 'item_id','option_value_id');
    }

    public function lessonVideosDuration()
    {
        return $this->sum('video_duration');
    }

    public function courses()
    {
        return $this->morphToMany(course::class, 'courseable', 'course_track');
    }

}
