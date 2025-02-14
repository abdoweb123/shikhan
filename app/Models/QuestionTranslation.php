<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuestionTranslation extends Model
{
    protected $table = 'question_translations';
    public $timestamps = true;
    protected $fillable = ['question_id','locale','title','trans_status'];

}
