<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestTime extends Model
{
  protected  $table="test_times";
  protected $fillable = [
      'student_id','test_id','test_duration','elapsed_time','start_time'
  ];

  public $timestamps= false;
  protected $casts = [
      'start_time'  => 'datetime',
  ];


}
