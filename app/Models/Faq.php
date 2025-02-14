<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
class Faq extends Model
{ 
    use Translatable;
     protected $table='faqs';
    public $useTranslationFallback = true;
    public $translationForeignKey = 'faq_id';
    public $translatedAttributes = ['question','answer'];
    public $translationModel = 'App\Translations\FaqTranslation';
    protected $fillable = ['ip','access_user_id','is_active'];
   
   

    const FILE_FOLDER = 'faqs';
    const FILES_TABLE_NAME = 'faqs';
    const PAGE = 'faq';

    protected $casts = [
      'question' => 'array',
      'answer' => 'array',
    ];

    // public function getQuestionAttribute($value,$language = null)
    // {
    //   if (! $language) { $language = app()->getLocale(); }
    //
    //   $value = json_decode($value,true);
    //   return isset($value[$language]) ? $value[$language] : null;
    // }
    //
    // public function getAnswerAttribute($value,$language = null)
    // {
    //   if (! $language) { $language = app()->getLocale(); }
    //
    //   $value = json_decode($value,true);
    //   return isset($value[$language]) ? $value[$language] : null;
    // }

}
