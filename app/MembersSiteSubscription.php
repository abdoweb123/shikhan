<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembersSiteSubscription extends Model
{
    public $table = "members_sites_subscriptions";
    
    protected $fillable = [
        'site_id', 'site_new_flag', 'total', 'date'
    ];


}
