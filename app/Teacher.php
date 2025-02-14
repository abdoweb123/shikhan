<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class Teacher extends Model implements Authenticatable
{
    use Translatable, AuthenticableTrait;

    public $useTranslationFallback = true;
    public $translationForeignKey = 'teacher_id';
    public $translatedAttributes = ['title', 'alias', 'description', 'header', 'meta_description', 'meta_keywords', 'qualification', 'specialization', 'position'];
    public $translationModel = 'App\Translations\TeacherTranslation';
    protected $fillable = [
       'id','name','email','password','image','birthdate','country_id','description','number_rated','rated','header','meta_description','meta_keywords','updated_at','created_at','is_active','sort'
    ];

    const FOLDER = 'teachers';
    const FOLDER_IMAGE = 'teachers/images';
    const FOLDER_PDF = 'teachers/pdf';
    const FOLDER_SOUND = 'teachers/sound';
    const FOLDER_HTML = 'teachers/html';

    protected $hidden = [];

    protected $table="teachers";
    public $timestamps= true;

    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    public function lessons()
    {
        return $this->hasMany('App\LessonOld','teacher_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Country','country_id');
    }

    public function scopeActive($query)
    {
       return $query->where('is_active', 1 );
    }

   public function getLogoPathAttribute()
    {

        return (substr($this->image, 0, 4) === 'http') ? $this->image : (\Storage::exists($this->image ?? 'nofile') ? url(\Storage::url($this->image)) : asset('assets/img/default/teachers.jpg')  );

    }


}
