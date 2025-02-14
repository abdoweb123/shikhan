<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionInfo extends Model
{
    protected $table='option_info';
    protected $fillable = [
        'option_id','language_id','title'
    ];


    
}
