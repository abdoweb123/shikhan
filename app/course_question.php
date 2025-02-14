<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class course_question extends Model
{
    use Translatable, SoftDeletes;
    protected $table = 'course_questions';
    public $translationForeignKey = 'question_id';
    public $translatedAttributes = ['name'];
    protected $casts = [
        'correct_answer' => 'array',
        'options' => 'array',
    ];

    protected $fillable = [
        'course_id','term_id','type','degree','correct_answer','required','options','sequence','status','deleted_at','created_by','updated_by'
    ];

    public $translationModel = 'App\Translations\QuestionTranslation';


    public function translation()
    {
        return $this->hasMany($this->translationModel, 'question_id','id');
    }

    public function course()
    {
        return $this->belongsTo('App\course','course_id');
    }

    public function answers()
    {
        return $this->hasMany('App\course_answer', 'question_id');
    }

    public function tests()
    {
        return $this->hasMany('App\course_test','question_id');
    }

    public function getCorrectAnswer()
    {
        return json_decode($this->correct_answer, true);
    }
}
