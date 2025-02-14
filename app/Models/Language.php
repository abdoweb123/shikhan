<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name','alias','status'
    ];

    protected  $table="language";
    public  $timestamps= true;

    public function scopeDefault($query)
    {
       return $query->where('defualt', 1);
    }

    public function scopeActive($query)
    {
       return $query->where('status', 1);
    }
}
