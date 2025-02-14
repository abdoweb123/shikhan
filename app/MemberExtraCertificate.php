<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberExtraCertificate extends Model
{
    protected  $table="member_extra_certificates";
    protected $fillable = ['user_id', 'extra_certificate_id', 'code'];

    public  $timestamps= false;


}
