<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class OptionValue extends Model
{
    protected $table='option_values';
    public $useTranslationFallback = true;
    public $translationForeignKey = 'option_value_id';
    public $translatedAttributes = ['locale_id','title'];
    public $translationModel = 'App\Models\OptionValueTranslation';
    protected $fillable = [
        'option_id','name','image','parent_id'
    ];

    public $timestamps = false;

    public function options()
    {
        return $this->belongsTo('App\Option','option_id');
    }

    public function translation()
    {
        return $this->hasMany($this->translationModel);
    }

    // public function option_value_info()
    // {
    //     return $this->hasMany('App\OptionValueInfo','option_value_id');
    // }



}
