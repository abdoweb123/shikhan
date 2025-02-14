<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Test extends Model
{
    use Translatable;
    protected  $table="tests";
    public $useTranslationFallback = true;
    public $translationForeignKey = 'test_id';
    public $translatedAttributes = ['name','alias','trans_status'];
    public $translationModel = 'App\Translations\TestTranslation';
    protected $fillable = [
        'name','status','testable_id','testable_type','sort','type','percentage'
    ];

    public $timestamps= false;

    public function testable(): MorphTo
    {
        return $this->morphTo();
    }


}
