<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemOptionValueSelector extends Model
{
    protected $table='item_option_value_selector';
    public $timestamps = false;
    protected $fillable = [
        'item_id','option_id','option_value_id','title'
    ];

    protected $casts = [
        'title' => 'array',
    ];


}
