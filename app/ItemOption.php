<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemOption extends Model
{
    protected $table='item_option';
    public $timestamps = false;
    protected $fillable = [
        'item_id','option_id','locale','value','title'
    ];

    public function options()
    {
        return $this->belongsTo('App\Option','option_id');
    }

}
