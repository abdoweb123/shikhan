<?php

namespace App\Translations;
use Illuminate\Database\Eloquent\Model;

class TeacherTranslation extends Model
{
    protected $table = 'teacher_translations';
    protected $fillable = ['teacher_id','locale','title','alias','description','header','meta_keywords','meta_description','qualification','specialization','position'];
    // public $timestamps = false;
}
