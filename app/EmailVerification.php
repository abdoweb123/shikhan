<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $fillable = [
        'token','user_id'
    ];

    protected  $table="email_verification";
    public  $timestamps= false;


}
