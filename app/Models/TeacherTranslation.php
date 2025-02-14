<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TeacherTranslation extends Model
{
    protected $table = 'teacher_translations';
    public $timestamps = false;
    protected $fillable = ['locale','title','alias','qualification','specialization','position','description','header','meta_description','meta_keywords','trans_image','trans_status','created_by'];

}
