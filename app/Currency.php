<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    protected $table = 'currencies';
    protected $fillable = ['name','code','symbol','sort','status','deleted_at'];

    public function scopeActive($query)
    {
       return $query->where('status', 1);
    }


}
