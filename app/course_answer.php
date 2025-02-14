<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class course_answer extends Model
{
    use Translatable,SoftDeletes;
    protected $table = 'course_answers';
    public $translationForeignKey = 'answer_id';
    public $translatedAttributes = ['name'];
    public $translationModel = 'App\Translations\AnswerTranslation';

    protected $fillable = [
        'question_id','sequence','status'
    ];

    public function translation()
    {
        return $this->hasMany($this->translationModel,'answer_id','id');
    }

    public function question()
    {
        return $this->belongsTo('App/course_question', 'question_id');
    }

    public function tests()
    {
        return $this->hasMany('App\course_test','answer_id');
    }

    public function isCorrectAnswer($correctAnswers = [])
    {
        return in_array($this->id, $correctAnswers) ? 1 : 0;
    }


}
