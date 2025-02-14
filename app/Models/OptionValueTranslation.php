<?php

namespace App\Models\OptionValueTranslation;

use Illuminate\Database\Eloquent\Model;

class OptionValueTranslation extends Model
{
    protected $table='option_value_translations';
    public $timestamps = false;
    protected $fillable = [
        'option_value_id','locale_id','title'
    ];



}
