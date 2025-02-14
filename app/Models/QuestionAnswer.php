<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class QuestionAnswer extends Model
{
    use Translatable;
    protected $table = 'question_answers';
    public $useTranslationFallback = true;
    public $translationForeignKey = 'question_answer_id';
    public $translatedAttributes = ['title'];
    public $translationModel = 'App\Models\QuestionAnswerTranslation';
    protected $fillable = ['question_id','sequence','sort','status'];

    const FOLDER_HTML = 'question_answers/html';
    const FOLDER_IMAGE =  'question_answers';

    public function translations()
    {
        return $this->hasMany($this->translationModel);
    }

    public function isCorrectAnswer($correctAnswers = [])
    {
        return in_array($this->id, $correctAnswers) ? 1 : 0;
    }

    public function scopeActive($query)
    {
       return $query->where('status', \App\Models\Lookup::getActiveStatus());
    }
}
