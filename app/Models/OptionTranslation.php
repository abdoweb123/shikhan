<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OptionTranslation extends Model
{
    protected $table='option_translations';
    protected $fillable = [
        'option_id','locale_id','title'
    ];



}
