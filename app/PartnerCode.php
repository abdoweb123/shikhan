<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerCode extends Model
{
    protected $table="partner_codes";
    protected $fillable = [
       'id','partner_id','code','status'
    ];

    public function scopeActive($query)
    {
       return $query->where('status', 1 );
    }


}
