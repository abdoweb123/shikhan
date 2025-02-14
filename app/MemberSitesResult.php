<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberSitesResult extends Model
{

    public $table = "member_sites_results";
    protected $fillable = [
        'user_id', 'site_id', 'site_completed', 'locale' ,'courses_count', 'user_tests_count', 'user_finished_site', 'user_successed', 'user_max_test_datetime', 'user_site_degree', 'closed_exams_count','site_new_flag'
    ];


    public function site()
    {
        return $this->belongsTo('App\site', 'site_id', 'id');
    }

    public function site_translation()
    {
        return $this->belongsTo('App\Translations\SiteTranslation', 'site_id', 'site_id')->where('locale', app()->getLocale());
    }

    public function scopeSuccessed($query)
    {
       return $query->where('user_successed', 1);
    }



}
