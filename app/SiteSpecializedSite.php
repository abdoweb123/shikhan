<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteSpecializedSite extends Model
{

    public $table = "site_specialized_site";
    protected $fillable = ['specialized_site_id','site_id'];

    public function specialized_site()
    {
        return $this->belongsTo('App\SpecializedSite', 'specialized_site_id');
    }

    public function site()
    {
        return $this->belongsTo('App\site', 'site_id');
    }



}
