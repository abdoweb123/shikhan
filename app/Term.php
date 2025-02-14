<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\libraries\DateHelper;

class Term extends Model
{
    use Translatable;
    protected  $table="terms";
    public $useTranslationFallback = true;
    public $translationForeignKey = 'term_id';
    public $translatedAttributes = ['name','alias','trans_status'];
    public $translationModel = 'App\Translations\TermTranslation';
    protected $fillable = [
        'title','site_id','sort','type','exam_approved','question_period','max_trys','exam_at','certificate_template','params','is_active'
    ];

    public $timestamps= false;

    public function site()
    {
        return $this->belongsTo('App\site');
    }

    public function courses()
    {
        return $this->belongsToMany('App\course', 'course_site', 'term_id', 'course_id')->withPivot(['course_id','main_site','certificate_template_name']);
    }

    public function course_term_finished()
    {
        return $this->hasMany('App\course_test_result', 'term_id');
    }

    public function questions()
    {
        return $this->hasMany('App\course_question');
    }


    public function term_results()
    {
        return $this->hasMany('App\TermTestResult');
    }


    public function final_results()
    {
        return $this->hasMany('App\MemberTermsResult');
    }

    public function tests()
    {
        return $this->hasMany('App\TermTest');
    }

    public function scopeExamApproved($query)
    {
       return $query->where('exam_approved', 1 );
    }

    public function isExamApproved()
    {
       return $this->exam_approved==1;
    }

    public function getMaxTrys()
    {
        return $this->max_trys;
    }

    public function getLogoPathAttribute()
    {
       return '';
    }

    public function isExamOpened()
    {
      $exam_at = date('Y-m-d H:i:s' ,strtotime($this->exam_at));
      return  $this->exam_at ? ($exam_at <= date('Y-m-d H:i:s')) : false;
    }

    public function getExamAtDay()
    {
        if (!$this->attributes['exam_at'] ) {return '';}
        return __('dates.'.date('D', strtotime($this->attributes['exam_at'])));
    }

    public function getExamAtHijri()
    {
        if (!$this->attributes['exam_at'] ) {return '';}
        $date = DateHelper::GregorianToHijri( strtotime( $this->attributes['exam_at']));
        return DateHelper::DateToShowHijri($date);
    }

    public function scopeValid($query)
    {
       return $query->where('status',1);
    }

}
