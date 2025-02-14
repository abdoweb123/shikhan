<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberSiteCertificate extends Model
{
    public $table = "member_site_certificate";

    protected $fillable = [
        'site_id','term_id', 'user_id', 'locale', 'code'
    ];



}
