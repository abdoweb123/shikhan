<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TestTranslation extends Model
{
    protected $table = 'test_translations';
    public $timestamps = true;
    protected $fillable = ['test_id','locale','title','alias','trans_status'];



}
