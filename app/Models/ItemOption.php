<?php

namespace App\Models;

//use App\Models\Option;
use Illuminate\Database\Eloquent\Model;

class ItemOption extends Model
{
    protected $table='item_option';
    public $timestamps = false;
    protected $fillable = [
        'item_id','option_id','locale','value','title'
    ];

    public function option()
    {
        return $this->belongsTo(Option::class,'option_id');
    }


}
