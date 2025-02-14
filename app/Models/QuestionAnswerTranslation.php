<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswerTranslation extends Model
{
    protected $table = 'question_answers_translations';
    public $timestamps = true;
    protected $fillable = ['question_answer_id','locale','title'];

}
