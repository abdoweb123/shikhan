<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionValueInfo extends Model
{
    protected $table='option_value_info';
    public $timestamps = false;
    protected $fillable = [
        'option_value_id','language_id','title'
    ];

        

}
