<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteSubscription extends Model
{
    public $table = "site_subscriptions";
    protected $fillable = ['site_id','user_id'];



}
