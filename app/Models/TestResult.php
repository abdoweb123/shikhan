<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{

    protected $table = 'test_results';
    protected $fillable = ['student_id','course_id','test_id','degree','rate','test_no','percentage','locale'];
    public $timestamps = false;

    public function isSuccessed()
    {
      return $this->degree >= getDegreeSuccess();
    }

    public function getRate()
    {
        return \App\helpers\domainHelper::getTestRatesTitles()[$this->rate];
    }


}
