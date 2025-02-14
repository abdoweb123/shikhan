<?php

namespace App\Translations;
use Illuminate\Database\Eloquent\Model;

class QuestionTranslation extends Model
{
    protected $table = 'course_questions_translations';
    public $timestamps = false;
    protected $fillable = ['name','locale','question_id'];
}
