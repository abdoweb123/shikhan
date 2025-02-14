<?php

namespace App\Translations;
use Illuminate\Database\Eloquent\Model;

class AnswerTranslation extends Model
{
    protected $table = 'course_answers_translations';
    public $timestamps = false;
    protected $fillable = ['name','locale','answer_id'];
}
