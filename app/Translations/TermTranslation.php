<?php

namespace App\Translations;
use Illuminate\Database\Eloquent\Model;

class TermTranslation extends Model
{
    protected $table = 'terms_translations';
//    public $timestamps = true;
    protected $fillable = ['term_id','locale','name','alias','trans_status']; // 'created_by',
    public $timestamps = false;
}
