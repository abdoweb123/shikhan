<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class PrizeUser extends Model
{
    protected  $table="prizes_users";
    protected $fillable = [
        'user_id'
    ];


    public function users()
    {
        return $this->hasMany('App\User','user_id');
    }
}
