<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    protected $table='option_values';
    public $timestamps = false;
    protected $fillable = [
        'option_id','titleGeneral','image','parent_id'
    ];

    public function options()
    {
        return $this->belongsTo('App\Option','option_id');
    }  

    public function option_value_info()
    {
        return $this->hasMany('App\OptionValueInfo','option_value_id');
    }



}
