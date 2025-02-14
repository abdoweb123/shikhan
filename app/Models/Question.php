<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Question extends Model
{
    use Translatable;
    protected $table = 'questions';
    public $useTranslationFallback = true;
    public $translationForeignKey = 'question_id';
    public $translatedAttributes = ['title','alias','description','header','meta_description','meta_keywords','trans_image','trans_status '];
    public $translationModel = 'App\Models\QuestionTranslation';
    protected $fillable = ['test_id','type','degree', 'duration', 'correct_answers','required','options','sequence','status','created_by_admin_id','created_by_teacher_id'];

    const FOLDER_HTML = 'questions_old/html';
    const FOLDER_IMAGE =  'questions_old';

    protected $casts = [
        'correct_answers' => 'array',
        'options' => 'array',
    ];


    const TYPE_DROPLIST = 'drop_list';
    const TYPE_MULTISELECT = 'multi_select';
    const TYPE_ESSAY = 'essay';


    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class, 'question_id');
    }

    // types --------------------
    public static function types()
    {
      return  [
        self::TYPE_DROPLIST => __('domain.'.self::TYPE_DROPLIST),
        self::TYPE_MULTISELECT => __('domain.'.self::TYPE_MULTISELECT),
        self::TYPE_ESSAY => __('domain.'.self::TYPE_ESSAY),
      ];
    }

    public function isDropList()
    {
      return $this->type == self::TYPE_DROPLIST;
    }

    public function isMultiSelect()
    {
      return $this->type == self::TYPE_MULTISELECT;
    }

    public function isEssay()
    {
      return $this->type == self::TYPE_ESSAY;
    }
    // ---------------------

    public function isRequired()
    {
      return $this->required == 1;
    }



    public function scopeActive($query)
    {
       return $query->where('status', \App\Models\Lookup::getActiveStatus());
    }

    // if (Str::startsWith($this->attributes['image'], ['http://', 'https://'])) {
    // return $this->attributes['image'];
    // }
    // return \Storage::disk('public')->url($this->attributes['image']);

}
