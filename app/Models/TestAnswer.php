<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{

    protected $table = 'test_answers';
    protected $fillable = ['student_id','test_id','test_result_id','question_id','question_original_degree','answer_id','answer','files','notes','degree','teacher_id'];




}
