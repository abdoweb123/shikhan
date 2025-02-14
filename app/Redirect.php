<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = [
        'link','locale','model','redirect','model_id',
    ];

    protected  $table="redirects";
    public  $timestamps= false;

    public function scopeCourse($query)
    {
       return $query->where('model', 'course' );
    }

    public function scopeSite($query)
    {
       return $query->where('model', 'site' );
    }

}
